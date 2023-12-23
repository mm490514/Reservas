<?php 
require_once("../../conexao.php");
require_once("campos.php");

echo <<<HTML
<table id="{$pagina}" class="table table-striped table-light table-hover my-4">
<thead>
<tr>
<th>{$campo1}</th>
<th>{$campo2}</th>
<th>{$campo3}</th>
<th>{$campo4}</th>
<th>{$campo5}</th>
<th>{$campo6}</th>
<th>{$campo7}</th>	
<th>Ações</th>
</tr>
</thead>
<tbody>
HTML;


$query = $pdo->query("SELECT * from $pagina order by id_hospede desc ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
for($i=0; $i < @count($res); $i++){
	foreach ($res[$i] as $key => $value){} 

		$id = $res[$i]['id_hospede'];
		$cp1 = $res[$i]['nome'];
		$cp2 = $res[$i]['cpf'];
		$cp3 = $res[$i]['data_nascimento'];
		$cp4 = $res[$i]['celular'];
		$cp5 = $res[$i]['email'];
		$cp6 = $res[$i]['profissao'];
		$cp7 = $res[$i]['nacionalidade'];				

echo <<<HTML
	<tr>
	<td>
	
	{$cp1}
	</td>		
	<td>{$cp2}</td>	
	<td>{$cp3}</td>	
	<td>{$cp4}</td>	
	<td>{$cp5}</td>
	<td>{$cp6}</td>	
	<td>{$cp7}</td>										
	<td>
	<a href="#" onclick="editar('{$id}', '{$cp1}', '{$cp2}', '{$cp3}', '{$cp4}', '{$cp5}', '{$cp6}', '{$cp7}')" title="Editar Registro">	<i class="bi bi-pencil-square text-primary"></i> </a>
	<a href="#" onclick="excluir('{$id}' , '{$cp1}')" title="Excluir Registro">	<i class="bi bi-trash text-danger"></i> </a>	

	<a class="mx-1" href="#" onclick="mostrarDados('{$id}', '{$cp1}', '{$cp2}', '{$cp3}', '{$cp4}', '{$cp5}', '{$cp6}', '{$cp7}')" title="Ver Dados do Cliente">
	<i class="bi bi-exclamation-square"></i></a>

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




