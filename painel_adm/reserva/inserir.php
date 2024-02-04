<?php 
require_once("../../conexao.php");
require_once("campos.php");



$cp1 = $_POST[$campo1]; 
$cp2 = $_POST[$campo2];
$cp3 = $_POST[$campo3];
$cp4 = $_POST[$campo4];
$cp5 = $_POST[$campo5];
$cp6 = $_POST[$campo6];
$cp7 = $_POST[$campo7];
$cp8 = $_POST[$campo8];
$cp9 = $_POST["CPF"];




$id = @$_POST['id'];

//VALIDAR CAMPO
$query = $pdo->query("SELECT * from $pagina where num_reserva = '$cp7'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);	
$id_reg = @$res[0]['id_reserva'];
if($total_reg > 0 and $id_reg != $id){
	echo 'Este reserva já está cadastrado!!';
	exit();
}

$query = $pdo->prepare("SELECT * FROM hospede WHERE cpf = :cpf");
$query->bindParam(':cpf', $cp9, PDO::PARAM_STR);
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);

foreach ($res as $hosp) {
	$id_hospede = $hosp['id_hospede'];
}

if($id == ""){
	$query = $pdo->prepare("INSERT INTO $pagina set data_checkin = :campo1, data_checkout = :campo2, num_criancas = :campo3, num_adulto = :campo4, status_pagamento = :campo5, valor = :campo6,
	 num_reserva = :campo7, id_quarto = :campo8, status_reserva = :campo9, id_hospede = :campo10");
}else{
	$query = $pdo->prepare("UPDATE $pagina set data_checkin = :campo1, data_checkout = :campo2, num_criancas = :campo3, num_adulto = :campo4, status_pagamento = :campo5,
	 valor = :campo6, num_reserva = :campo7, id_quarto = :campo8, id_hospede = :campo10 WHERE id_reserva = '$id'");
}

$query->bindValue(":campo1", "$cp1");
$query->bindValue(":campo2", "$cp2");
$query->bindValue(":campo3", "$cp3");
$query->bindValue(":campo4", "$cp4");
$query->bindValue(":campo5", "$cp5");
$query->bindValue(":campo6", "$cp6");
$query->bindValue(":campo7", "$cp7");
$query->bindValue(":campo8", "$cp8");
$query->bindValue(":campo9", "0");
$query->bindValue(":campo10", "$id_hospede");
$query->execute();

if (!$id){
	$query = $pdo->query("SELECT * from $pagina order by id_reserva desc limit 1");
	$res = $query->fetchAll(PDO::FETCH_ASSOC);
	$id_reserva = @$res[0]['id_reserva'];
} else {
	$id_reserva = $id;
}

echo 'Salvo com Sucesso';

 ?>