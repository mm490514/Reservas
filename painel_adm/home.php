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
LEFT JOIN reserva r ON r.id_quarto = q.id_quarto 
LEFT JOIN hospede_reserva hr ON hr.id_reserva = r.id_reserva 
LEFT JOIN hospede h ON h.id_hospede = hr.id_hospede
GROUP BY q.numero, h.nome, r.data_checkin, r.data_checkout, q.status;
");
$res = $query->fetchAll(PDO::FETCH_ASSOC);

?>


<!-- Seu código HTML -->
<link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">
<br/>
<br/>
<div class="container">
  <div class="row">
    <?php foreach ($res as $quarto) { ?>
      <div class="col-md-3">
        <div class="card bg-light mb-3 <?php echo getStatusClass($quarto['status']); ?>" style="max-width: 18rem;">
          <div class="card-header <?php echo getHeaderClass($quarto['status']); ?> text-white">QUARTO <?php echo $quarto['numero']; ?></div>
          <div class="card-body">
            <p class="card-text"><i class="bi bi-person-badge-fill"></i> <?php echo $quarto['nome'] ?? '-'; ?></p>
            <p class="card-text"><i class="bi bi-people-fill"></i> <?php echo $quarto['num_adulto'] ?? '-'; ?></p>
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
            <button type="button" class="btn btn-outline-success liberar-btn" data-quarto-id="<?php echo $quarto['id_quarto']; ?>">Liberar</button>
            <button type="button" class="btn btn-outline-primary">Manutenção</button>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>

<!-- Modal de confirmação -->
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
        <button type="button" class="btn btn-primary" id="confirmarLiberar">Liberar</button>
      </div>
    </div>
  </div>
</div>


<?php
// Função para retornar a classe do status
function getStatusClass($status) {
    if ($status == 0) {
        return 'bg-success';
    } elseif ($status == 1) {
        return 'bg-danger';
    } elseif ($status == 2) {
        return 'bg-secondary';
    } else {
        return '';
    }
}

// Função para retornar a classe do cabeçalho (header) com base no status
function getHeaderClass($status) {
    if ($status == 0) {
        return 'bg-success';
    } elseif ($status == 1) {
        return 'bg-danger';
    } elseif ($status == 2) {
        return 'bg-secondary';
    } else {
        return 'bg-light'; // Classe padrão caso o status não seja reconhecido
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

    // Se confirmar a liberação, realiza a ação
    $('#confirmarLiberar').click(function() {
      $('#confirmarModal').modal('hide'); // Fecha o modal
      // Executa a função para atualizar o status
      alert(quartoId);
      liberarQuarto(quartoId);
    });

    function liberarQuarto(id) {
      $.ajax({
        url: 'atualizar_status.php',
        method: 'POST',        
        data: { quartoId: id },
        success: function(response) {
          
        },
        error: function(xhr, status, error) {          
        }
      });
    }

    $('.modal-footer .btn-secondary').click(function() {
      $('#confirmarModal').modal('hide');
    });
  });
</script>


