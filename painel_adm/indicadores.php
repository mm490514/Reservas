<?php

require_once('../conexao.php');

$hoje = date('Y-m-d');
$mes_atual = Date('m');
$ano_atual = Date('Y');
$dataInicioMes = $ano_atual . "-" . $mes_atual . "-01";

$query = $pdo->query("SELECT *  from quarto where status = '1'");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$quartosOcupados = @count($res);

$query = $pdo->query("SELECT *  from quarto");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$quartosLivres = @count($res);

$taxaOcup = (100 * $quartosOcupados) / $quartosLivres;

$primeiroDia = date("Y-m-01");
$ultimoDia = date("Y-m-t");

$query = $pdo->query("SELECT sum(valor) as receita FROM reserva WHERE data_checkout >= '$primeiroDia' AND data_checkout <= '$ultimoDia'");
$res = $query->fetch(PDO::FETCH_ASSOC);
$receitas = 1;
if ($res) {
	$receitas = $res['receita'];
}

$query = $pdo->query("SELECT *  from hospede");
$res = $query->fetchAll(PDO::FETCH_ASSOC);
$hospedes = @count($res);

$query = $pdo->query("SELECT  
SUM(num_adulto + num_criancas) AS total_hospedes
FROM quarto q 
LEFT JOIN (
SELECT *
FROM reserva
WHERE CURRENT_DATE() BETWEEN data_checkin AND data_checkout
AND status_reserva = 0
) r ON r.id_quarto = q.id_quarto 
LEFT JOIN hospede_reserva hr ON hr.id_reserva = r.id_reserva 
LEFT JOIN hospede h ON h.id_hospede = hr.id_hospede");
$res = $query->fetch(PDO::FETCH_ASSOC);
$hospedes = 0;
if ($res) {
	$hospedes = $res['total_hospedes'];
}

$query = $pdo->query("SELECT count(*) as dias FROM reserva WHERE data_checkout >= '$primeiroDia' AND data_checkout <= '$ultimoDia'");
$res = $query->fetch(PDO::FETCH_ASSOC);
$dias = 1;
if ($res) {
	$dias = $res['dias'];
}

if ($receitas == 0){
$receitas = 1;
}

if ($dias == 0){
	$dias = 1;
}
$media = $receitas / $dias;

$query = $pdo->query("SELECT data_checkin, data_checkout FROM reserva WHERE data_checkout >= '$primeiroDia' AND data_checkout <= '$ultimoDia'");
$reservas = $query->fetchAll(PDO::FETCH_ASSOC);
$qtdeReservas = 0;
$totalDias = 0;

foreach ($reservas as $reserva) {
	$checkin = new DateTime($reserva['data_checkin']);
	$checkout = new DateTime($reserva['data_checkout']);

	$diferenca = $checkout->diff($checkin);

	$totalDias += $diferenca->days;
	$qtdeReservas++;
}

function consultarValor($pdo, $primeiroDia, $ultimoDia, $status_pagamento)
{
	$query = $pdo->prepare("SELECT sum(valor) as total FROM reserva WHERE data_checkout >= :primeiroDia AND data_checkout <= :ultimoDia AND status_pagamento = :status");
	$query->bindParam(':primeiroDia', $primeiroDia);
	$query->bindParam(':ultimoDia', $ultimoDia);
	$query->bindParam(':status', $status_pagamento);
	$query->execute();
	$res = $query->fetch(PDO::FETCH_ASSOC);

	return ($res && isset($res['total'])) ? $res['total'] : 0;
}

$recebido = consultarValor($pdo, $primeiroDia, $ultimoDia, '1');
$receber = consultarValor($pdo, $primeiroDia, $ultimoDia, '0');



?>


<link href="https://fonts.googleapis.com/css?family=Montserrat&display=swap" rel="stylesheet">

<div class="container-fluid">
	<section id="minimal-statistics">
		<div class="row mb-2">
			<div class="col-12 mt-3 mb-1">
				<h4 class="text-uppercase">Principais Indicadores</h4>

			</div>
		</div>




		<div class="row mb-4">

			<div class="col-xl-3 col-sm-6 col-12">
				<div class="card">
					<div class="card-content">
						<div class="card-body">
							<div class="row">
								<div class="align-self-center col-3">
									<i class="bi bi-stack text-success fs-1 float-start"></i>
								</div>
								<div class="col-9 text-end">
									<h3> <span class="text-success"><?php echo number_format($taxaOcup, 1) . "%" ?></span></h3>
									<span>Taxa de Ocupação</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-3 col-sm-6 col-12">
				<div class="card">
					<div class="card-content">
						<div class="card-body">
							<div class="row">
								<div class="align-self-center col-3">
									<i class="bi bi-credit-card text-success fs-1 float-start"></i>
								</div>
								<div class="col-9 text-end">
									<h3> <span class="text-success"><?php echo "R$" . number_format($receitas, 2)  ?></span></h3>
									<span>Receitas</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>


			<div class="col-xl-3 col-sm-6 col-12">
				<div class="card">
					<div class="card-content">
						<div class="card-body">
							<div class="row">
								<div class="align-self-center col-3">
									<i class="bi bi-people text-success fs-1 float-start"></i>
								</div>
								<div class="col-9 text-end">
									<h3> <span class="text-success"><?php echo $hospedes ?></span></h3>
									<span>Nº de hóspedes</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>


			<div class="col-xl-3 col-sm-6 col-12">
				<div class="card">
					<div class="card-content">
						<div class="card-body">
							<div class="row">
								<div class="align-self-center col-3">
									<i class="bi bi-graph-up text-warning fs-1 float-start"></i>
								</div>
								<div class="col-9 text-end">
									<h3><span class="text-warning"><?php echo "R$" . $media ?></span></h3>
									<span>Diaria media</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</section>

	<section id="minimal-statistics">
		<div class="row mb-4">

			<div class="col-xl-3 col-sm-6 col-12">
				<div class="card">
					<div class="card-content">
						<div class="card-body">
							<div class="row">
								<div class="align-self-center col-3">
									<i class="bi bi-box-arrow-right text-success fs-1 float-start"></i>
								</div>
								<div class="col-9 text-end">
									<h3> <span class="text-success"><?php echo $totalDias ?></span></h3>
									<span>Diárias</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-xl-3 col-sm-6 col-12">
				<div class="card">
					<div class="card-content">
						<div class="card-body">
							<div class="row">
								<div class="align-self-center col-3">
									<i class="bi bi-calendar-week text-dark fs-1 float-start"></i>
								</div>
								<div class="col-9 text-end">
									<h3> <span class="text-dark"><?php echo $qtdeReservas ?></span></h3>
									<span>Reservas</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>


			<div class="col-xl-3 col-sm-6 col-12">
				<div class="card">
					<div class="card-content">
						<div class="card-body">
							<div class="row">
								<div class="align-self-center col-3">
									<i class="bi bi-hand-thumbs-up text-success fs-1 float-start"></i>
								</div>
								<div class="col-9 text-end">
									<h3> <span class="text-success"><?php echo "R$" . $recebido ?></span></h3>
									<span>Recebido</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>


			<div class="col-xl-3 col-sm-6 col-12">
				<div class="card">
					<div class="card-content">
						<div class="card-body">
							<div class="row">
								<div class="align-self-center col-3">
									<i class="bi bi-hand-thumbs-down text-danger fs-1 float-start"></i>
								</div>
								<div class="col-9 text-end">
									<h3><span class="text-danger"><?php echo "R$" . $receber ?></span></h3>
									<span>À receber</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</section>

	<?php
	$query = $pdo->query("SELECT
    date_range.date,
    COUNT(reserva.id_reserva) AS total_reservas_por_dia    
	FROM (
		SELECT DATE_ADD('$primeiroDia', INTERVAL t4.i*10000 + t3.i*1000 + t2.i*100 + t1.i*10 + t0.i DAY) AS date
		FROM
		(SELECT 0 AS i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) AS t0,
		(SELECT 0 AS i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) AS t1,
		(SELECT 0 AS i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) AS t2,
		(SELECT 0 AS i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) AS t3,
		(SELECT 0 AS i UNION SELECT 1 UNION SELECT 2 UNION SELECT 3 UNION SELECT 4 UNION SELECT 5 UNION SELECT 6 UNION SELECT 7 UNION SELECT 8 UNION SELECT 9) AS t4
		) date_range
		LEFT JOIN reserva ON date_range.date BETWEEN reserva.data_checkin AND reserva.data_checkout
		WHERE date_range.date <= '$ultimoDia'
		GROUP BY date_range.date
		ORDER BY date_range.date;");
	$res = $query->fetch(PDO::FETCH_ASSOC);

	$labels = []; // Array para armazenar as datas
	$data = [];   // Array para armazenar as contagens de reservas

	// Processar os resultados da consulta
	while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
		// Adicionar a data e a contagem de reservas aos arrays
		$labels[] = $res['date'];
		$data[] = $res['total_reservas_por_dia'];
	}

	


	?>


	<section id="stats-subtitle">
		<div class="row mb-2">
			<div class="col-12 mt-3 mb-1">
				<h4 class="text-uppercase">Gráfico de ocupação</h4>

			</div>
		</div>
		<div style="width: 1600px;">
			<canvas id="myChart" width="1200" height="300"></canvas>
		</div>

		<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

		<script>
			const ctx = document.getElementById('myChart');

			new Chart(ctx, {
				type: 'line',
				data: {
					labels: <?php echo json_encode($labels); ?>, 
					datasets: [{
						label: 'Ocupação',						
						data: <?php echo json_encode($data); ?>, 
						borderWidth: 3,
						borderColor: 'red' 
					}]
				},
				plugins: {
					title: {
						display: true,
						text: 'Título do Gráfico'
					}
        		},
				options: {
					scales: {
						y: {
							beginAtZero: true,
							max: 6, // Definindo o limite máximo do eixo y como 6
							title: {
							display: true,
                    		text: 'Rótulo do Eixo Y'
                			}
						}						
					}
				}
			});
		</script>





		<style type="text/css">
			#principal {
				width: 100%;
				height: 100%;
				margin-left: 10px;
				font-family: Verdana, Helvetica, sans-serif;
				font-size: 14px;

			}

			#barra {
				margin: 0 2px;
				vertical-align: bottom;
				display: inline-block;
				padding: 5px;
				text-align: center;

			}

			.cor1,
			.cor2,
			.cor3,
			.cor4,
			.cor5,
			.cor6,
			.cor7,
			.cor8,
			.cor9,
			.cor10,
			.cor11,
			.cor12 {
				color: #FFF;
				padding: 5px;
			}

			.cor1 {
				background-color: #FF0000;
			}

			.cor2 {
				background-color: #0000FF;
			}

			.cor3 {
				background-color: #FF6600;
			}

			.cor4 {
				background-color: #009933;
			}

			.cor5 {
				background-color: #FF0000;
			}

			.cor6 {
				background-color: #0000FF;
			}

			.cor7 {
				background-color: #FF6600;
			}

			.cor8 {
				background-color: #009933;
			}

			.cor9 {
				background-color: #FF0000;
			}

			.cor10 {
				background-color: #0000FF;
			}

			.cor11 {
				background-color: #FF6600;
			}

			.cor12 {
				background-color: #009933;
			}
		</style>
	</section>


</div>