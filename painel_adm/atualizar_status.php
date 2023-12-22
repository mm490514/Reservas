<?php
require_once("../conexao.php");
var_dump(isset($_POST["quartoId"]));
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["quartoId"])) {

$quartoId = $_POST["quartoId"];
$status = $_POST["status"];  



$query = $pdo->query("SELECT * from quarto where id_quarto = '$quartoId'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

if($total_reg > 0){	
	
$query = $pdo->prepare("UPDATE quarto SET status = :s WHERE id_quarto = '$quartoId'");
$query->bindValue(":s", "$status"); 
$query->execute();

echo "ok";
    
}
} 

?>
