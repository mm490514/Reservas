<?php 
require_once("../../conexao.php");
require_once("campos.php");

echo <<<HTML
<table id="{$pagina}" class="table table-striped table-light table-hover my-4">
<thead>
<tr>
<th>Número</th>
<th>Andar</th>
<th>Categoria</th>
<th>Capacidade Adultos</th>
<th>Capacidade Crianças</th>
<th>Status</th>
<th>Valor</th>	
<th>Ações</th>
</tr>
</thead>
<tbody>
HTML;


$query = $pdo->query("SELECT * from $pagina order by id_quarto desc ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
for($i=0; $i < @count($res); $i++){
	foreach ($res[$i] as $key => $value){} 

		$id = $res[$i]['id_quarto'];
		$cp1 = $res[$i]['numero'];
		$cp2 = $res[$i]['andar'];
		$cp3 = $res[$i]['id_categoria'];
		$cp4 = $res[$i]['capacidade_adultos'];
		$cp5 = $res[$i]['capacidade_criancas'];
		$cp6 = $res[$i]['status'];
		$cp7 = $res[$i]['valor'];	
		
		$query1 = $pdo->query("SELECT * from categoria where id_categoria = '$cp3' ");
		$res1 = $query1->fetchAll(PDO::FETCH_ASSOC);
		if(@count($res1) > 0){
			$nome_cat = $res1[0]['nome'];
		}else{
			$nome_cat = 'Sem Categoria';
		}

		if ($cp6 == "0"){
			$status = "Liberado";
		} else if ($cp6 == "1"){
			$status = "Ocupado";
		} else {
			$status = "Manutenção";
		}

echo <<<HTML
	<tr>
	<td>
	
	{$cp1}
	</td>		
	<td>{$cp2}</td>	
	<td>{$nome_cat}</td>	
	<td>{$cp4}</td>	
	<td>{$cp5}</td>
	<td>{$status}</td>	
	<td>{$cp7}</td>										
	<td>
	<a href="#" onclick="editar('{$id}', '{$cp1}', '{$cp2}', '{$cp3}', '{$cp4}', '{$cp5}', '{$cp6}', '{$cp7}')" title="Editar Registro">	<i class="bi bi-pencil-square text-primary"></i> </a>
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




