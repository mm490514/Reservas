<?php 
require_once("../../conexao.php");
require_once("campos.php");
$id = @$_POST['id-excluir'];

$pdo->query("DELETE from $pagina where id_quarto = '$id'");
echo 'Excluído com Sucesso';

 ?>