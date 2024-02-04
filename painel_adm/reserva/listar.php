<?php 
require_once("../../conexao.php");
require_once("campos.php");

echo <<<HTML
<table id="{$pagina}" class="table table-striped table-light table-hover my-4">
<thead>
<tr>
<th>Nº Reserva</th>
<th>Hóspede</th>
<th>Data CheckIn</th>
<th>Data CheckOut</th>
<th>Número Crianças</th>
<th>Número Adultos</th>
<th>Status Pagamento</th>
<th>Valor</th>
<th>Ações</th>
</tr>
</thead>
<tbody>
HTML;


$query = $pdo->query("SELECT * from $pagina order by id_reserva desc ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
for($i=0; $i < @count($res); $i++){
	foreach ($res[$i] as $key => $value){} 

		$id = $res[$i]['id_reserva'];
		$cp1 = $res[$i]['data_checkin'];
		$cp2 = $res[$i]['data_checkout'];
		$cp3 = $res[$i]['num_criancas'];
		$cp4 = $res[$i]['num_adulto'];
		$cp5 = $res[$i]['status_pagamento'];
		$cp6 = $res[$i]['valor'];
		$cp7 = $res[$i]['num_reserva'];	
		
		$query1 = $pdo->query("SELECT h.nome as nome FROM hospede h
		inner join reserva r on r.id_hospede = h.id_hospede
		where r.id_reserva = $id");
		$res1 = $query1->fetchAll(PDO::FETCH_ASSOC);
		if(@count($res1) > 0){
			$nome = $res1[0]['nome'];
		}

		if ($cp5 == "0"){
			$status = "Á Pagar";
		} else if ($cp5 == "1"){
			$status = "Pago";
		}

echo <<<HTML
	<tr>
	<td>{$cp7}</td>		
	<td>{$nome}</td>	
	<td>{$cp1}</td>	
	<td>{$cp2}</td>	
	<td>{$cp3}</td>
	<td>{$cp4}</td>	
	<td>{$status}</td>	
	<td>{$cp6}</td>									
	<td>	
	<a href="#" onclick="excluir('{$id}' , '{$cp1}')" title="Excluir Registro">	<i class="bi bi-trash text-danger"></i> </a>		
	</td>
	</tr>
HTML;
} 
echo <<<HTML
</tbody>
</table>
HTML;

?>

<script>
$(document).ready(function() {    
	$('#<?=$pagina?>').DataTable({
		"ordering": false,
		"stateSave": true,
	});

} );


function editar(id, cp1, cp2, cp3, cp4, cp5, cp6, cp7){	

	$('#id').val(id);
	$('#<?=$campo1?>').val(cp1);
	$('#<?=$campo2?>').val(cp2);
	$('#<?=$campo3?>').val(cp3);
	$('#<?=$campo4?>').val(cp4);
	$('#<?=$campo5?>').val(cp5);
	$('#<?=$campo6?>').val(cp6);
	$('#<?=$campo7?>').val(cp7);	
	
	$('#tituloModal').text('Editar Registro');
	var myModal = new bootstrap.Modal(document.getElementById('modalForm'), {		});
	myModal.show();
	$('#mensagem').text('');
}



function limparCampos(){
	$('#id').val('');
	$('#<?=$campo1?>').val('');
	
	$('#<?=$campo2?>').val('');
	$('#<?=$campo3?>').val('');
	$('#<?=$campo4?>').val('');
	
	$('#<?=$campo5?>').val('');
	
	$('#<?=$campo6?>').val('');
	$('#<?=$campo7?>').val('');	

	$('#mensagem').text('');
	
}



function mostrarDados(id, cp1, cp2, cp3, cp4, cp5, cp6, cp7){
	
	$('#campo1').text(cp1);
	$('#campo2').text(cp2);
	$('#campo3').text(cp3);
	$('#campo4').text(cp4);
	$('#campo5').text(cp5);
	$('#campo6').text(cp6);
	$('#campo7').text(cp7);	
	
	var myModal = new bootstrap.Modal(document.getElementById('modalDados'), {		});
	myModal.show();
	
}

</script>




