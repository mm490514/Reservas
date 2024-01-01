<?php
require_once("../conexao.php");
var_dump(isset($_POST["quartoId"]));
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["datacheckin"])) {

$checkin = $_POST["datacheckin"];
$checkout = $_POST["datacheckout"];  
$numCriancas = $_POST["numeroCriancas"];  
$numAdultos = $_POST["numeroAdultos"];  



$query = $pdo->query("SELECT q.id_quarto, CONCAT('Quarto ', q.numero, ' - ', c.nome) AS quarto
FROM quarto q
LEFT JOIN reserva r ON q.id_quarto = r.id_quarto
inner join categoria c on c.id_categoria = q.id_categoria
WHERE (
(r.data_checkin > '$checkout' OR r.data_checkout < '$checkin')
OR r.id_reserva IS NULL
)
AND q.capacidade_adultos >= $numAdultos
AND q.capacidade_criancas >= $numCriancas");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$total_reg = @count($res);

if ($total_reg > 0) {
    $options = '<option value="">Selecione um quarto</option>';
    foreach ($res as $quarto) {
        $options .= '<option value="' . $quarto['id_quarto'] . '">' . $quarto['quarto'] . '</option>';
    }
    echo $options;
} else {
    echo 'Nenhum quarto disponível para os critérios selecionados.';
}

} 

?>

