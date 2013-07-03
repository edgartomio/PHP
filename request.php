<?phph
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
?>    