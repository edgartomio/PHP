<?php

$param = '<EnviarInstrucao>

   <InstrucaoUnica>

      <Razao>'.$dados_compra['0']['titulo'].'</Razao>

      <IdProprio>'.$id.'</IdProprio>

      <FormasPagamento>

         <FormaPagamento>BoletoBancario</FormaPagamento>

         <FormaPagamento>CarteiraMoIP</FormaPagamento>

         <FormaPagamento>CartaoCredito</FormaPagamento>

         <FormaPagamento>DebitoBancario</FormaPagamento>

         <FormaPagamento>FinanciamentoBancario</FormaPagamento>

      </FormasPagamento>

      <Boleto>

         <DiasExpiracao Tipo="Corridos">5</DiasExpiracao>

      </Boleto>

      <Valores>

         <Valor moeda="BRL">'.$dados_compra['0']['valor_total_com_desconto'].'</Valor>

      </Valores>

      <Mensagens>

         <Mensagem>'.$dados_compra['0']['titulo'].'</Mensagem>

      </Mensagens>

      <Pagador>

	  <Nome>'.$_SESSION['usuario']['nome'].'</Nome>

	  <Email>'.$_SESSION['usuario']['email'].'</Email>

	  </Pagador>

      <UrlRetorno>'.$lista_pagto['0']['moip_url_usuario'].'</UrlRetorno> 

      <UrlNotificacao>'.$lista_pagto['0']['moip_url_status'].'</UrlNotificacao>

   </InstrucaoUnica>

</EnviarInstrucao>';



$curl = curl_init();


curl_setopt($curl, CURLOPT_URL,

"https://www.moip.com.br/ws/alpha/EnviarInstrucao/Unica");

curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

curl_setopt($curl, CURLOPT_USERPWD, $token . ":" . $key);

curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0");

curl_setopt($curl, CURLOPT_POST, true);

curl_setopt($curl, CURLOPT_POSTFIELDS, $param);

curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


$ret = curl_exec($curl);

$err = curl_error($curl);

curl_close($curl);



$resposta_moip = simplexml_load_string($ret);

$token_resposta = $resposta_moip->Resposta->Token;



$url = 'https://www.moip.com.br/ws/alpha/EnviarInstrucao/Unica'.$token_resposta;

?>