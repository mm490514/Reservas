<?php 
require_once("../../conexao.php");
require_once("campos.php");

echo <<<HTML
<table id="{$pagina}" class="table table-striped table-light table-hover my-4">
<thead>
<tr>
<th>Nome</th>
<th>Descrição</th>	
<th>Ações</th>
</tr>
</thead>
<tbody>
HTML;


$query = $pdo->query("SELECT * from $pagina order by id_categoria desc ");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
for($i=0; $i < @count($res); $i++){
	foreach ($res[$i] as $key => $value){} 

		$id = $res[$i]['id_categoria'];
		$cp1 = $res[$i]['nome'];
		$cp2 = $res[$i]['descricao'];					

echo <<<HTML
	<tr>
	<td>
	
	{$cp1}
	</td>		
	<td>{$cp2}</td>											
	<td>
	<a href="#" onclick="editar('{$id}', '{$cp1}', '{$cp2}')" title="Editar Registro">	<i class="bi bi-pencil-square text-primary"></i> </a>
	<a href="#" onclick="excluir('{$id}' , '{$cp1}')" title="Excluir Registro">	<i class="bi bi-trash text-danger"></i> </a>	

	<a class="mx-1" href="#" onclick="mostrarDados('{$id}', '{$cp1}', '{$cp2}')" title="Ver Dados do Cliente">
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


function editar(id, cp1, cp2){	

	$('#id').val(id);
	$('#<?=$campo1?>').val(cp1);
	$('#<?=$campo2?>').val(cp2);		
	
	$('#tituloModal').text('Editar Registro');
	var myModal = new bootstrap.Modal(document.getElementById('modalForm'), {		});
	myModal.show();
	$('#mensagem').text('');
}



function limparCampos(){
	$('#id').val('');
	$('#<?=$campo1?>').val('');
	
	$('#<?=$campo2?>').val('');	

	$('#mensagem').text('');
	
}



function mostrarDados(id, cp1, cp2){

	
	
	$('#campo1').text(cp1);
	$('#campo2').text(cp2);
	
	var myModal = new bootstrap.Modal(document.getElementById('modalDados'), {		});
	myModal.show();
	
}

</script>




