<?php 
@session_start();
require_once("conexao.php");

$login = $_POST['email'];
$senha = $_POST['password'];

$query = $pdo->prepare("SELECT * from usuarios where login = :login and senha = :senha ");
$query->bindValue(":login", "$login");
$query->bindValue(":senha", "$senha");
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

if($total_reg > 0){
	$nivel = $res[0]['nivel'];

	//VARIAVEIS DE SESSÃO
	$_SESSION['nivel_usuario'] = $res[0]['nivel'];
	$_SESSION['id_usuario'] = $res[0]['id'];
	$_SESSION['nome_usuario'] = $res[0]['nome'];

	if($nivel == 'Administrador' || $nivel == 'Comum'){
		echo "<script>window.location='painel_adm'</script>";
	}
}else{
	echo "<script>window.alert('Dados Incorretos!')</script>";
	echo "<script>window.location='index.php'</script>";
}

 ?>