<?php
session_name('usuario');
session_start();

require 'admin/conexao.php';

foreach($_POST as $ind=>$val){
    $$ind = trim($val);    
}

$id = $_POST['fatura'];

if(!ctype_digit($id) OR $id < '1'){
    echo 'Compra inválida!';
    exit; 
}else{
    $sql_compra = $con->prepare("SELECT o.titulo, f.* FROM faturas f
    LEFT JOIN ofertas o ON o.id = f.id_compra
    WHERE f.id = :id");
    $sql_compra->bindParam(':id', $id, PDO::PARAM_INT);
        if(!$sql_compra->execute()){
            echo 'Erro ao carregar os dados da compra!';
            exit;
        }elseif($sql_compra->rowCount() == '0'){
            echo 'Compra inválida!';
            exit;
        }
}


   
if(strlen($f_cartao_numero_1) == '' OR !ctype_digit($f_cartao_numero_1) OR strlen($f_cartao_numero_2) == '' OR !ctype_digit($f_cartao_numero_2) OR strlen($f_cartao_numero_3) == '' OR !ctype_digit($f_cartao_numero_3) OR strlen($f_cartao_numero_4) == '' OR !ctype_digit($f_cartao_numero_4)){
    echo 'erro|Erro: O número do cartão é inválido!';
    exit;
}

$datavencimento = date('Y-m');
$data_vencimento_teste = $f_cartao_ano.'-'.$f_cartao_mes;
if($datavencimento > $data_vencimento_teste){
    echo 'erro|Erro: Data de validade inválida!';
    exit;
}

if($f_cartao_nome_titular == ''){
    echo 'erro|Erro: Informe o nome do titular do cartão!';
    exit;
}

$dados_compra = $sql_compra->fetch(PDO::FETCH_ASSOC);
$bandeira = $dados_compra['cartao_bandeira'];

//print_r($dados_compra);


$valor_venda = str_replace('.','',$dados_compra['valor_total_com_desconto']);
//$valor_venda = $dados_compra['valor_total_com_desconto'];

        
$sql_lista_pagto = $con->prepare("SELECT braspag_merchantid FROM configuracao WHERE id = '1'");
$sql_lista_pagto->execute();
$dados_braspag = $sql_lista_pagto->fetch(PDO::FETCH_ASSOC);


$cartao = $f_cartao_numero_1.$f_cartao_numero_2.$f_cartao_numero_3.$f_cartao_numero_4;

$data_vencimento = $f_cartao_mes.'/'.$f_cartao_ano;

if($f_cartao_qtd_parcelas == '1'){
    $produto = 1;
}else{
    $produto = 2;
}

$bandeira = '997'; 


ini_set("soap.wsdl_cache_enabled", "0");
$SoapClient = new SoapClient("https://homologacao.pagador.com.br/pagador/index.asp");
$parametros = array();
$parametros["merchantId"] = $dados_braspag['braspag_merchantid'];
$parametros["orderId"] = $dados_compra['id'];
$parametros["customerName"] = $f_cartao_nome_titular;
$parametros["amount"] = $valor_venda;
$parametros["paymentMethod"] = $bandeira;
$parametros["holder"] = $f_cartao_nome_titular;
$parametros["cardNumber"] = $cartao;
$parametros["expiration"] = $data_vencimento;
$parametros["securityCode"] = $f_cartao_codigo;
$parametros["numberPayments"] = $f_cartao_qtd_parcelas;
$parametros["typePayment"] = $produto;
$Retorno = $SoapClient->Authorize($parametros);
    
    
    $Count=0;
foreach($Retorno->AuthorizeResult as $livro){
 if($Count == 0){
	 $Valor = $livro;
 }else if($Count == 1){
	 $Autorizacao = $livro;
 }else if($Count == 2){
	 $Mensagem = $livro;
 }else if($Count == 3){
	 $CodigoRetorno = $livro;
 }else if($Count == 4){
	 $Status = $livro;
 }else if($Count == 5){
	 $IdTransacao = $livro;
 }
 $Count++;
}

if($Status == 1){
    if(trim($Autorizacao) != ""){
        /*
    	$cSQL = "INSERT INTO retorno_braspag
    						 (id_fatura,
    						  id_transacao,
    						  valor,
    						  autorizacao,
    						  mensagem,
    						  codigo_retorno,
    						  status_retorno,
    						  dt_registro)
    				   VALUES (".trim($CdFatura).",
    						   ".trim($IdTransacao).",
    						   ".trim($Valor).",
    						   ".trim($Autorizacao).",
    						   '".trim($Mensagem)."',
    						   ".$CodigoRetorno.",
    						   ".$Status.",
    						   current_timestamp)";
    	
    	mysql_query($cSQL,$DataBase) or die(mysql_error()."<br>".$cSQL);
    	*/
    	if(($Status == 2) || ($Status == 8) || ($Status == "")){
            echo 'erro|Não foi possível finalizar a compra, o pedido foi negado!';
    	}else{
    		echo 'Solicitação processada com sucesso!';
    	}
    }
}else{
    echo 'erro|Não foi possível finalizar a compra, o pedido foi negado!';
        print_r($Retorno);
        echo '<hr>Número de afiliação: '.$dados_braspag['braspag_merchantid'];
        echo '<hr>';
        print_r($parametros);
}
?>