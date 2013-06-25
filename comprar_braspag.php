<?php
include 'header.php';

$id = base64_decode($_GET['id']);

if(!ctype_digit($id) OR $id < '1'){
                                echo"
		<script language=\"JavaScript\">
		alert('Compra inválida!');
		window.location='index.php';
		</script>
		";
		exit; 
                        }else{
                            $sql_compra = $con->prepare("SELECT o.titulo, f.* FROM faturas f
                            LEFT JOIN ofertas o ON o.id = f.id_compra
                            WHERE f.id = :id");
                            $sql_compra->bindParam(':id', $id, PDO::PARAM_INT);
                            if(!$sql_compra->execute()){
                                echo"
		<script language=\"JavaScript\">
		alert('Erro ao carregar os dados da compra!');
		window.location='index.php';
		</script>
		";
                            }elseif($sql_compra->rowCount() == '0'){
                                echo"
		<script language=\"JavaScript\">
		alert('Compra inválida!');
		window.location='index.php';
		</script>
		";
                            }
                        }
$dados_compra = $sql_compra->fetch(PDO::FETCH_ASSOC);

$numero_cartao = explode('-',$_SESSION['cartao']['numero']);
$vencimento = explode('-',$_SESSION['cartao']['vencimento']);
$mes_vencimento = $vencimento['1'];
$ano_vencimento = $vencimento['0'];

$sql_quantidade_parcelas = $con->prepare("SELECT braspag_parcelas FROM configuracao WHERE id = '1'");
$sql_quantidade_parcelas->execute();
$quantidade_parcelas = explode(',',$sql_quantidade_parcelas->fetchColumn());

switch($dados_compra['cartao_bandeira']){
    case '18': $indice_array = '0'; break;
    case '71': $indice_array = '1'; break;
    case '73': $indice_array = '2'; break;
    case '120': $indice_array = '3'; break;
    case '122': $indice_array = '4'; break;       
    case '42': $indice_array = '5'; break;
    case '20': $indice_array = '6'; break;
    case '57': $indice_array = '7'; break;
    case '62': $indice_array = '8'; break;
    case '90': $indice_array = '9'; break;
    case '55': $indice_array = '10'; break;
    case '58': $indice_array = '11'; break;
    case '37': $indice_array = '12'; break;
    case '44': $indice_array = '13'; break;
    default: echo 'Erro: Método de pagamento inválido!'; exit;
}

$qtd_parcelas = $quantidade_parcelas[$indice_array];
?>
<script type="text/javascript" src="js/comprar.js"></script>
    <body class="COMPRAR">
        <header>
        	<div class="wrapper">
       	    	<a href="index.html"><img src="admin/fotos/<?php echo $dados_cliente['imgSite'];?>" width="310" height="59"><span style="font-size:11px; float:right; margin-top:20px; margin-right:5px;">Voltar a página inicial</span></a>
            </div>
    	</header>
        <script type="text/javascript" src="js/ui.checkbox.js"></script>
        <!-- Início do conteúdo dinâmico -->
        <div id="middle" class="padraoFormularios">
        	<div class="wrapper">
            	<!-- CONTEÚDO PRINCIPAL DINÂMICO -->
                    <!-- Título principal da pagina -->
                    <div class="barraPadrao1"><strong>Finalizar</strong> compras</div>
                    <!-- Section com o conteúdo -->
                    
              <section class="CONTENT formasPagamento margintop25">
                <h3 class="destaquePadrao1">Formas de pagamento</h3>
                        <div class="right margintop15" id="formas_pagto">
                            <div id="finalizarDados">
                                <strong>Número do cartão</strong><br>
                                <input type="text" style="width:60px" name="f_cartao_numero_1" maxlength="4" value="<?php echo $numero_cartao['0'];?>"/>
                                <input type="text" style="width:60px" name="f_cartao_numero_2" maxlength="4" value="<?php echo $numero_cartao['1'];?>"/>
                                <input type="text" style="width:60px" name="f_cartao_numero_3" maxlength="4" value="<?php echo $numero_cartao['2'];?>"/>
                                <input type="text" style="width:60px" name="f_cartao_numero_4" maxlength="4" value="<?php echo $numero_cartao['3'];?>"/>
                                <hr>
                                <strong>Titular do cartão</strong><br>
                                <input type="text" style="width:280px" name="f_cartao_nome_titular" value="<?php echo $_SESSION['cartao']['titular'];?>">
                                <hr>
                                <div class="left datas">
                                <strong>Data de validade</strong><br>
                                <select name="f_cartao_mes">
                                    <option value="01" <?php if($mes_vencimento == '01'){echo 'selected="selected"';}?>>01</option>
                                    <option value="02" <?php if($mes_vencimento == '02'){echo 'selected="selected"';}?>>02</option>
                                    <option value="03" <?php if($mes_vencimento == '03'){echo 'selected="selected"';}?>>03</option>
                                    <option value="04" <?php if($mes_vencimento == '04'){echo 'selected="selected"';}?>>04</option>
                                    <option value="05" <?php if($mes_vencimento == '05'){echo 'selected="selected"';}?>>05</option>
                                    <option value="06" <?php if($mes_vencimento == '06'){echo 'selected="selected"';}?>>06</option>
                                    <option value="07" <?php if($mes_vencimento == '07'){echo 'selected="selected"';}?>>07</option>
                                    <option value="08" <?php if($mes_vencimento == '08'){echo 'selected="selected"';}?>>08</option>
                                    <option value="09" <?php if($mes_vencimento == '09'){echo 'selected="selected"';}?>>09</option>
                                    <option value="10" <?php if($mes_vencimento == '10'){echo 'selected="selected"';}?>>10</option>
                                    <option value="11" <?php if($mes_vencimento == '11'){echo 'selected="selected"';}?>>11</option>
                                    <option value="12" <?php if($mes_vencimento == '12'){echo 'selected="selected"';}?>>12</option>
                                </select>
                                 <select name="f_cartao_ano">
                                    <?php
                                    $ano_final = date('Y', strtotime('+10 year'));
                                    for($i=date('Y'); $i <= $ano_final; $i++){
                                        $sel = ($ano_vencimento == $i)?'selected="selected"':'';
                                        echo '<option value="'.$i.'" '.$sel.'>'.$i.'</option>';
                                    }
                                    ?>
                                </select>
                                </div>
                                <div class="left">
                                <strong>Código de segurança</strong><br>
                                <input type="text" style="width:60px" name="f_cartao_codigo" value="<?php echo $_SESSION['cartao']['cod_segurancao'];?>"/> <a href="" title="mais informações"><img src="img/maisInfo.png" style="vertical-align:middle"></a>
                                </div>
                                <div class="clear"></div>
                                <hr>
                                <p class="destaquePadrao2" id="quantidade_parcelas">
                                    Quantidade de parcelas
                                    <select style="padding-right:7px; width:100px" id="qtd_parcelas" name="f_cartao_qtd_parcelas" onChange="calcula_parcela(this.value);">
                                    <?php
                                    for($i = 1; $i<=$qtd_parcelas; $i++){
                                        $sel = ($_SESSION['cartao']['parcelas'] == $i)?'selected="selected"':'';
                                        echo '<option value="'.$i.'" '.$sel.'>'.$i.'</option>';
                                    }
                                    ?>
                                    </select> <strong style="font-size:14px" id="valor_parcela"><span id="valor_total_parcelas"><?php echo $_SESSION['cartao']['parcelas'];?></span>x de R$<span id="valor_total_vouchers_com_frete"><?php echo number_format($dados_compra['valor_total_com_desconto']/$_SESSION['cartao']['parcelas'], '2', ',', '.');?></span></strong>
                                </p>
                                <input id="botao_pagar" type="submit" class="padraoBotaoFinalizar margintop10" value="Pagar" style="width:100%" onClick="pagar_braspag(<?php echo $dados_compra['id'];?>);"><span id="finalizar"></span>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </section>
                    <input id="valor_total_vouchers_com_frete_input" type="hidden" value="<?php echo $dados_compra['valor_total_com_desconto'];?>" />
                    <script>
						function createGTitle(obj, texto){
							obj.parent().append("<div class='newTitle'>"+ texto +"<img src='img/arrowTitle.png' class='arrow'></div>");
							objNewTittle = obj.parent().find(".newTitle");
							objNewTittle.css({top: (obj.parent().position().top - objNewTittle.height() - 50 ), left: (obj.parent().position().left + (obj.parent().width() / 2 ) ), marginLeft: (-(objNewTittle.width()/2)) })
							objNewTittle.animate({top: (objNewTittle.position().top + 30) + "px"}, 1000);
							objNewTittle.fadeIn("fast");
						}
						function removeGTitle(obj, texto){
							obj.parent().find(".newTitle").fadeOut("fast", function(){ $(this).parent().find(".newTitle").remove()  })
						}
						$(function(){
							$(".faq a[title]").mouseenter(function(){
								createGTitle($(this), $(this).attr("title"));
							})
							$(".faq a[title]").mouseleave(function(){
								removeGTitle($(this))
							})
						});
					</script>
                            <?php
                            $sql_faqs = $con->prepare("SELECT id, label, texto FROM faqs WHERE exibe_comprar = 's' ORDER BY label");
                            $sql_faqs->execute();
                            if($sql_faqs->rowCount() > '0'){
                                echo '<section class="faq margintop10">
            	               <ul>
                               <li style="margin:0"><img src="img/perguntasFrequentes.png" width="184" height="47"></li>';
                                while($faqs = $sql_faqs->fetch(PDO::FETCH_ASSOC)){
                                    echo '<li><a name="baloes" title="'.strip_tags($faqs['texto'],'<br>').'">'.$faqs['label'].'</a></li>';
                                }
                                echo '</ul>
                                </section>';
                            }
                            ?>
                </div>
        </div>
        <!-- Fim do conteúdo dinâmico -->
        <footer>
        	<div class="wrapper">
            	<div class="left">
                	© 2013 FlexCompras, Todos os direitos reservados
                </div>
            	<div class="flexer right">
                    <img class="sprites left" src="img/spacer.png" width="29" height="27">
                    <a href="http://www.flexer.com.br" target="_blank">agência digital</a><br>
                    <a href="http://www.comprascoletiva.com.br/" target="_blank">compra coletiva</a>
            	</div>
                <div class="clear"></div>
            </div>
        </footer>
                <script>
    $(document).ready(function () {
        <?php
        if(isset($_SESSION['cartao']['numero'])){
            echo 'pagar_braspag('.$dados_compra['id'].');'; 
        }
        ?>
    });
    </script>
    </body>
</html>
