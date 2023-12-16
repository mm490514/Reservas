<?php
require_once("../conexao.php");
var_dump(isset($_POST["quartoId"]));
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["quartoId"])) {

$quartoId = $_POST["quartoId"]; 

$query = $pdo->query("SELECT * from quarto where id_quarto = '$quartoId'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

if($total_reg > 0){
	$status = $res[0]['status'];
	if ($status == 0){
		echo 'Este quarto já esta linberado';
		exit();
	} else {
        $query = $pdo->prepare("UPDATE quarto SET status = :s WHERE id_quarto = '$quartoId'");
        $query->bindValue(":s", "0"); 
        $query->execute();
    
        echo json_encode(['success' => true]);
    }
    }
} 

?>
