<?php 
require_once("config.php");
$data_atual = date('Y-m-d');
date_default_timezone_set('America/Sao_Paulo');

try {
	$pdo = new PDO("mysql:dbname=$banco;host=$servidor;charset=utf8", "$usuario", "$senha");
} catch (Exception $e) {
	echo 'Não foi possível conectar ao banco de dados! <br><br>'.$e;

}


$nome_sistema = '';
$email_adm = '';
$nome_admin = '';
$endereco_site = '';
$telefone_fixo = '';
$telefone_whatsapp = '';

$cnpj_site = '';
$rodape_relatorios = '';
//VARIAVEIS PARA CONTAS A RECEBER   OBS NAO COLOQUE % NOS VALORES
$valor_multa = 2; // esse valor de 2 corresponde a 2% sobre o valor da venda
$valor_juros_dia = 0.15; // aqui será 0.15 % ao dia sobre o valor da venda;
$dias_carencia = 0;

$frequencia_automatica = 'Não'; //Caso você utilize sim e tenha uma conta que foi lançada como mensal, todo mês será gerado uma nova conta independentemente se a anterior foi paga.

$relatorio_pdf = 'Sim'; //Se estiver sim o relatório vai sair em pdf, caso contrário será um relatório html.
$fonte_comprovante = '11';
$impressao_automatica = 'Sim';

$query = $pdo->query("SELECT * FROM config");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
if(@count($res) == 0){
	$pdo->query("INSERT INTO config SET nome_sistema = '$nome_sistema', email_adm = '$email_adm', endereco_site = '$endereco_site', telefone_fixo = '$telefone_fixo', telefone_whatsapp = '$telefone_whatsapp', cnpj_site = '$cnpj_site', rodape_relatorios = '$rodape_relatorios', valor_multa = '$valor_multa', valor_juros_dia = '$valor_juros_dia', frequencia_automatica = '$frequencia_automatica', relatorio_pdf = '$relatorio_pdf', fonte_comprovante = '$fonte_comprovante', logo = 'logoqbonita', icone = 'icone.png', dias_carencia = '$dias_carencia', alerta = curDate(), impressao_automatica = '$impressao_automatica'");
}else{
	$nome_sistema = $res[0]['nome_sistema'];
	$email_adm = $res[0]['email_adm'];
	$endereco_site = $res[0]['endereco_site'];
	$telefone_fixo = $res[0]['telefone_fixo'];
	$telefone_whatsapp = $res[0]['telefone_whatsapp'];
	$cnpj_site = $res[0]['cnpj_site'];
	$rodape_relatorios = $res[0]['rodape_relatorios'];
	$valor_multa = $res[0]['valor_multa'];
	$valor_juros_dia = $res[0]['valor_juros_dia'];
	$frequencia_automatica = $res[0]['frequencia_automatica'];
	$relatorio_pdf = $res[0]['relatorio_pdf'];
	$fonte_comprovante = $res[0]['fonte_comprovante'];
	$logo = $res[0]['logo'];
	$icone = $res[0]['icone'];
	$dias_carencia = $res[0]['dias_carencia'];
	$alerta = $res[0]['alerta'];
	$impressao_automatica = $res[0]['impressao_automatica'];

	$telefone_whatsapp_link = '55'.preg_replace('/[ ()-]+/' , '' , $telefone_whatsapp);
}

 ?>