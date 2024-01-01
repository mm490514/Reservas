<?php
require_once("../conexao.php");
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["quartoId"])) {

    $quartoId = $_POST["quartoId"];
    $status = $_POST["status"];
    
    if ($status == 1) {

        $query = $pdo->query("SELECT * from reserva where id_quarto = '$quartoId' and data_checkin <= current_date");
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_reg = @count($res);

        if ($total_reg > 0) {
            $query = $pdo->prepare("UPDATE quarto SET status = :s WHERE id_quarto = :quartoId");
            $query->bindValue(":s", $status);
            $query->bindValue(":quartoId", $quartoId);
            $query->execute();
            $response = ["data" => "ok"];          
        } else {
            $response = ["data" => "N"];
        }
    } else {
        $query = $pdo->query("SELECT * from quarto where id_quarto = '$quartoId'");
        $res = $query->fetchAll(PDO::FETCH_ASSOC);
        $total_reg = @count($res);

        if ($total_reg > 0) {
            
            $query = $pdo->prepare("UPDATE reserva SET status_reserva = 1 WHERE CURRENT_DATE() BETWEEN data_checkin AND data_checkout AND id_quarto = :quartoId");            
            $query->bindValue(":quartoId", $quartoId);
            $query->execute();            
           
            $query = $pdo->prepare("UPDATE quarto SET status = :s WHERE id_quarto = :quartoId");
            $query->bindValue(":s", $status);
            $query->bindValue(":quartoId", $quartoId);
            $query->execute();
            
            $response = ["data" => "ok"]; 
        } else {
            $response = ["data" => "N"];      
        }
    }
}
echo json_encode($response);
exit();
?>