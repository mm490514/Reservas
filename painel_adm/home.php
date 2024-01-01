<?php 

require_once('../conexao.php');

$hoje = date('Y-m-d');
$mes_atual = Date('m');
$ano_atual = Date('Y');
$dataInicioMes = $ano_atual."-".$mes_atual."-01";


$query = $pdo->query("SELECT 
q.id_quarto,
q.numero, 
h.nome, 
SUM(num_adulto + num_criancas) AS total_hospedes, 
r.data_checkin, 
r.data_checkout, 
q.status 
FROM quarto q 
LEFT JOIN (
SELECT *
FROM reserva
WHERE CURRENT_DATE() BETWEEN data_checkin AND data_checkout
AND status_reserva = 0
) r ON r.id_quarto = q.id_quarto 
LEFT JOIN hospede_reserva hr ON hr.id_reserva = r.id_reserva 
LEFT JOIN hospede h ON h.id_hospede = hr.id_hospede
GROUP BY q.numero, h.nome, r.data_checkin, r.data_checkout, q.status

");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

$querystatus = $pdo->query("SELECT status, COUNT(*) as total FROM quarto GROUP BY status");
$resultados = $querystatus->fetchAll(PDO::FETCH_ASSOC);

$total_livre = 0;
$total_ocupado = 0;
$total_manutencao = 0;

foreach ($resultados as $restatus) {
    if ($restatus['status'] == 0) {
        $total_livre = $restatus['total'];
    } elseif ($restatus['status'] == 1) {
        $total_ocupado = $restatus['total'];
    } elseif ($restatus['status'] == 2) {
        $total_manutencao = $restatus['total'];
    }
}

$querycheck = $pdo->query("SELECT 
SUM(CASE WHEN r.data_checkin = CURRENT_DATE THEN 1 ELSE 0 END) as total_entra_hoje,
SUM(CASE WHEN r.data_checkout = CURRENT_DATE AND q.status = '1' THEN 1 ELSE 0 END) as total_sai_hoje
FROM reserva r
INNER JOIN quarto q ON q.id_quarto = r.id_quarto
WHERE q.status = '0' OR (q.status = '1' AND r.data_checkout = CURRENT_DATE)
");
$resultado = $querycheck->fetch(PDO::FETCH_ASSOC);

$total_entra_hoje = $resultado['total_entra_hoje'];
$total_sai_hoje = $resultado['total_sai_hoje'];


?>



<!-- Seu código HTML -->
<link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
<br/>
<br/>

<div class="container">
<div>
<button type="button" class="btn btn-success">
  Livre <span class="badge badge-light"><?php echo $total_livre; ?></span>
</button>
<button type="button" class="btn btn-danger">
  Ocupado <span class="badge badge-light"><?php echo $total_ocupado; ?></span>
</button>
<button type="button" class="btn btn-secondary">
  Manutenção <span class="badge badge-light"><?php echo $total_manutencao; ?></span>
</button>
<button type="button" class="btn btn-info" style="color: white;"> 
  Entra Hoje <span class="badge badge-light"><?php echo $total_entra_hoje; ?></span>
</button>
<button type="button" class="btn btn-warning" style="color: white;">
  Sai hoje <span class="badge badge-light"><?php echo $total_sai_hoje; ?></span>
</button>
</div>
</br>
  <div class="row">
    <?php foreach ($res as $quarto) { ?>
      <div class="col-md-3">
        <div class="card bg-light mb-3 <?php echo getStatusClass($quarto['status'], $quarto['data_checkin'], $quarto['data_checkout']); ?>" style="max-width: 18rem;">
          <div class="card-header <?php echo getStatusClass($quarto['status'], $quarto['data_checkin'], $quarto['data_checkout']); ?> text-white">QUARTO <?php echo $quarto['numero']; ?></div>
          <div class="card-body">
            <p class="card-text"><i class="bi bi-person-badge-fill"></i> <?php echo $quarto['nome'] ?? '-'; ?></p>            
            <p class="card-text"><i class="bi bi-calendar4-week"></i> 
              <?php 
                if ($quarto['data_checkin'] && $quarto['data_checkout']) {
                  echo date('d/m/Y', strtotime($quarto['data_checkin'])) . ' - ' . date('d/m/Y', strtotime($quarto['data_checkout']));
                } else {
                  echo '-';
                }
              ?>
            </p>
            <div class="card-body text-center">
            <?php 
              if ($quarto['status'] == '0') {
                  echo "<button type='button' class='btn btn-outline-danger hospedar-btn' data-quarto-id='" . $quarto["id_quarto"] . "'><i class='bi bi-house'></i> Hospedar</button>";
                  echo "<button type='button' class='btn btn-outline-primary manutencao-btn' data-quarto-id='" . $quarto['id_quarto'] . "'><i class='bi bi-hammer'></i></button>";
              } else {
                  echo "<button type='button' class='btn btn-outline-success liberar-btn' data-quarto-id='" .$quarto['id_quarto'] . "'><i class='bi bi-unlock'></i> Liberar</button>  ";
              }
              
            ?>            
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>

<!-- Modal de Liberar -->
<div class="modal fade" id="confirmarModal" tabindex="-1" role="dialog" aria-labelledby="confirmarModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmarModalLabel">Confirmar Liberação</h5>       
      </div>
      <div class="modal-body">
        Tem certeza que deseja liberar este quarto?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>        
        <button type="button" class="confirm-action btn btn-primary" data-action="liberar" data-status="0">Liberar</button>     
      </div>
    </div>
  </div>
</div>

<!-- Modal de Manutenção -->
<div class="modal fade" id="confirmarManutencao" tabindex="-1" role="dialog" aria-labelledby="confirmarManutencaoLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmarManutencaoLabel">Confirmar Manutenção</h5>       
      </div>
      <div class="modal-body">
        Tem certeza que deseja colocar este quarto em manutenção?
      </div>
      <div class="modal-footer">        
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>      
        <button type="button" class="confirm-action btn btn-primary" data-action="manutencao " data-status="2">Manutenção</button>  
      </div>
    </div>
  </div>
</div>

<!-- Modal de Hospedar -->
<div class="modal fade" id="confirmarHosp" tabindex="-1" role="dialog" aria-labelledby="confirmarHospedarLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmarHospedarLabel">Confirmar Hospedagem</h5>       
      </div>
      <div class="modal-body">
        Tem certeza que deseja hospedar nesse quarto ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>        
        <button type="button" class="confirm-action btn btn-primary" data-action="hospedar" data-status="1">Hospedar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Sucesso -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center w-100" id="successModalLabel">
          <i class="bi bi-check-circle display-1 text-success d-block mx-auto"></i>
        </h5>        
      </div>
      <div class="modal-body text-center">        
        <p>Operação realizada com Sucesso!</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal de Erro -->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center w-100" id="errorModalLabel">
          <i class="bi bi-x-circle display-1 text-danger d-block mx-auto"></i>
        </h5>        
      </div>
      <div class="modal-body text-center">        
        <p>Não é possível hospedar sem reserva!</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>






<?php

function getStatusClass($status, $data_checkin, $data_checkout) {
  $dataAtual = date('Y-m-d');
    if ($status == 0) {
      if ($data_checkin == $dataAtual){
        return 'bg-info';
      }  else {
        return 'bg-success';
      }
    } elseif ($status == 1) {
      if ($data_checkout == $dataAtual){
        return 'bg-warning';
      } else {
        return 'bg-danger';        
      }
    } elseif ($status == 2) {
        return 'bg-secondary';
    } else {
        return '';
    }
}

?>
<script>
  $(document).ready(function() {
    var quartoId;

    // Ao clicar no botão de liberar, captura o ID do quarto e abre o modal
    $('.liberar-btn').click(function() {
      quartoId = $(this).data('quarto-id');
      $('#confirmarModal').modal('show');
    });

    $('.hospedar-btn').click(function() {     
      quartoId = $(this).data('quarto-id');
      $('#confirmarHosp').modal('show');
    });

    $('.manutencao-btn').click(function() {
      quartoId = $(this).data('quarto-id');
      $('#confirmarManutencao').modal('show');
    });

   
    $('.confirm-action').click(function() {
      var action = $(this).data('action');
      var status = $(this).data('status');
      
      var modalID = '#confirmar' + action.charAt(0).toUpperCase() + action.slice(1);
      
      $(modalID).modal('hide'); 

      atualizarStatus(quartoId, status);
    });

    
    function atualizarStatus(id, statusq) {
    $.ajax({
        url: 'atualizar_status.php',
        method: 'POST',
        data: { quartoId: id, status: statusq },
        dataType: 'json', // Define o tipo de dados esperado como JSON
        success: function(response) {
            // Verifica se a resposta é válida
            if (response && response.data) {
                if (response.data === "ok") {
                    $('#successModal').modal('show');
                } else if (response.data === "N") {
                   $('#errorModal').modal('show');
                } else {
                    alert("Resposta inesperada: " + response.data);
                }
            } else {
                alert("Resposta inválida ou inesperada.");
            }
        },
        error: function(xhr, status, error) {
           
        }
    });
  }
  
  $('.modal-footer .btn-secondary').click(function() {
      $('#confirmarModal').modal('hide');
      $('#successModal').modal('hide');
      location.reload();
    });
   
  });
</script>


