<?php
require_once("../conexao.php");
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["datacheckin"])) {

$checkin = $_POST["datacheckin"];
$checkout = $_POST["datacheckout"];  
$idQuarto = $_POST["idQuarto"]; 

// Criar objetos DateTime para cada data
$datetime1 = new DateTime($checkin);
$datetime2 = new DateTime($checkout);

// Calcular a diferença entre as duas datas
$diferenca = $datetime1->diff($datetime2);

$diasDeDiferenca = $diferenca->days + 1;


$query = $pdo->query("SELECT * FROM quarto where id_quarto = '$idQuarto'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

if ($total_reg > 0) {
    $options = '<option value="">Selecione um quarto</option>';
    foreach ($res as $quarto) {
       $valor = $quarto['valor'];
    }

    $valorTotal = $diasDeDiferenca * $valor;

    echo $valorTotal;
} 

} 

?>

