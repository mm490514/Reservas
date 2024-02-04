<?php
require_once("../conexao.php");
if ($_SERVER["REQUEST_METHOD"] === "POST"   ) {

$cpf = $_POST["cpf"];

$query = $pdo->prepare("SELECT * FROM hospede WHERE cpf = :cpf");
$query->bindParam(':cpf', $cpf, PDO::PARAM_STR);
$query->execute();
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = count($res);


if ($total_reg > 0) {
    echo 'valido';
} else {
    echo 'invalido';
}

} 

?>

