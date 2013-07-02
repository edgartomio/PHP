<?php $parameters = new stdClass();
$parameters->merchantId = 'C4881CB3-9953-9D5E-380A-DE1E3B7F8976'; //aqui merchantId
$parameters->request = array(
	 'nome = comprador',
 'valor = 100'
);


/**
 * Aqui enviamos a requisiчуo
 */
try {
	$braspag = new SoapClient( 'https://homologacao.pagador.com.br/BraspagGeneralService/BraspagGeneralService.asmx?WSDL',
		array(
			'trace'			=> 1,
			'exceptions'	=> 1,
			'style'			=> SOAP_DOCUMENT,
			'use'			=> SOAP_LITERAL,
			'soap_version'	=> SOAP_1_1,
			'encoding'		=> 'UTF-8'
	)
);

	/**
 	* A variсvel $EncryptRequestResult abaixo conterс o conteњdo criptografado se tudo ocorrer bem
 	*/
	
$EncryptRequestResponse = $braspag->EncryptRequest( $parameters );

echo $EncryptRequestResponse->EncryptRequestResult; //Exibindo o conteњdo criptografado
} catch( SoapFault $fault ){
		echo 'Ocorreu um erro: ' , $fault->getMessage();
}

?>