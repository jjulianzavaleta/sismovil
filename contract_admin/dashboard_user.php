<?php
include("../phps/conexion.php");
include("../phps/dcontract_dashboard.php");

$userid                 = $_GET['id'];

$cant_vigentes          = getContratosByEstados(array(4),date("Y"),$userid,true);
$cant_concluido         = getContratosByEstados(array(5),date("Y"),$userid);
$cant_proceso           = getContratosByEstados(array(0,0.3,0.6,0.8,1,2,3),date("Y"),$userid);
$cant_anulado           = getContratosAnulados(date("Y"),$userid);
$porvencer_15diasOmenos = getContratosPorVencer(15,true,$userid);
$porvencer_30diasOmenos = getContratosPorVencer(30,true,$userid);

$data_por_estado1        = getRegistradosbyMonth(date("Y"),array(0,0.3,0.6,0.8,1,2,3,4,5),false,false,false,$userid);//registrados
$data_por_estado2        = getRegistradosbyMonth(date("Y"),array(5),true,true,false,$userid);//concluidos
$data_por_estado3        = getRegistradosbyMonth(date("Y"),array(0,0.3,0.6,0.8,1,2,3,4,5),false,false,true,$userid);//anulados
$max_value_por_estado    = max(max($data_por_estado1),max($data_por_estado2),max($data_por_estado3))+5;


?>

    <link rel="stylesheet" href="vendors/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendors/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="assets/css/style.css">

    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>

    <!-- Right Panel -->

    <div id="right-panel" class="right-panel">


        <div class="content mt-3">

			<div class="col-sm-12 mb-4">
				<div class="card-group">
					   <div class="card col-lg-2 col-md-6 no-padding bg-flat-color-1">
						<div class="card-body">
							<div class="h1 text-muted text-right mb-4">
								<i class="fa fa-gear text-light"></i>
							</div>

							<div class="h4 mb-0 text-light">
								<span ><?=$cant_vigentes?></span>
							</div>
							<small class="text-uppercase font-weight-bold text-light">Vigentes total</small>
							<div class="progress progress-xs mt-3 mb-0 bg-light" style="width: 40%; height: 5px;"></div>
						</div>
						<div class="card-footer">
							<small class="text-light">
								<a href="../contract_reportes/index.php?optSelec=1&chk_vigente=1&fechaIni=<?=date("Y")?>-01-01&fechaFin=<?=date("Y")?>-12-31">Ver detalle <i class="fa fa-arrow-circle-right"></i></a>
							</small>
						</div>
					</div>

					<div class="card col-lg-2 col-md-6 no-padding no-shadow">
						<div class="card-body bg-flat-color-2">
							<div class="h1 text-muted text-right mb-4">
								<i class="fa fa-check text-light"></i>
							</div>
							<div class="h4 mb-0 text-light">
								<span><?=$cant_concluido?></span>
							</div>
							<small class="text-uppercase font-weight-bold text-light">Concluido <?=date("Y")?></small>
							<div class="progress progress-xs mt-3 mb-0 bg-light" style="width: 40%; height: 5px;"></div>
						</div>
						<div class="card-footer">
							<small class="text-light">
								<a href="../contract_reportes/index.php?optSelec=1&chk_concluido=1&fechaIni=<?=date("Y")?>-01-01&fechaFin=<?=date("Y")?>-12-31">Ver detalle <i class="fa fa-arrow-circle-right"></i></a>
							</small>
						</div>
					</div>
					<div class="card col-lg-2 col-md-6 no-padding no-shadow">
						<div class="card-body bg-flat-color-3">
							<div class="h1 text-right mb-4">
								<i class="fa fa-legal text-light"></i>
							</div>
							<div class="h4 mb-0 text-light">
								<span><?=$cant_proceso?></span>
							</div>
							<small class="text-light text-uppercase font-weight-bold">En Proceso <?=date("Y")?></small>
							<div class="progress progress-xs mt-3 mb-0 bg-light" style="width: 40%; height: 5px;"></div>
						</div>
						<div class="card-footer">
							<small class="text-light">
								<a href="../contract_reportes/index.php?optSelec=1&chk_registrado=1&chk_pendelaboracion=1&chk_pendaprobacionusuario=1&chk_val_legal_acepta=1&chk_val_jef_log=1&chk_val_jefarea=1&chk_colectarfirmas=1&fechaIni=<?=date("Y")?>-01-01&fechaFin=<?=date("Y")?>-12-31">Ver detalle <i class="fa fa-arrow-circle-right"></i></a>
							</small>
						</div>
					</div>
					<div class="card col-lg-2 col-md-6 no-padding no-shadow">
						<div class="card-body bg-flat-color-5">
							<div class="h1 text-right text-light mb-4">
								<i class="fa fa-trash"></i>
							</div>
							<div class="h4 mb-0 text-light">
								<span><?=$cant_anulado?></span>
							</div>
							<small class="text-uppercase font-weight-bold text-light">Anulado <?=date("Y")?></small>
							<div class="progress progress-xs mt-3 mb-0 bg-light" style="width: 40%; height: 5px;"></div>
						</div>
						<div class="card-footer">
							<small class="text-light">
								<a href="../contract_reportes/index.php?optSelec=1&chk_anulado=1&fechaIni=<?=date("Y")?>-01-01&fechaFin=<?=date("Y")?>-12-31">Ver detalle <i class="fa fa-arrow-circle-right"></i></a>
							</small>
						</div>
					</div>
					<div class="card col-lg-2 col-md-6 no-padding no-shadow">
						<div class="card-body bg-flat-color-4">
							<div class="h1 text-light text-right mb-4">
								<i class="fa fa-exclamation-triangle"></i>
							</div>
							<div class="h4 mb-0 text-light"><?=$porvencer_15diasOmenos?></div>
							<small class="text-light text-uppercase font-weight-bold">Vence en ~15días</small>
							<div class="progress progress-xs mt-3 mb-0 bg-light" style="width: 40%; height: 5px;"></div>
						</div>
						<div class="card-footer">
							<small class="text-light">
								<a href="../contract_reportes/index.php?optSelec=3&chk_vence15=1&fechaIni=<?=date("Y")?>-01-01&fechaFin=<?=date("Y")?>-12-31">Ver detalle <i class="fa fa-arrow-circle-right"></i></a>
							</small>
						</div>
					</div>
					<div class="card col-lg-2 col-md-6 no-padding no-shadow">
						<div class="card-body bg-flat-color-4">
							<div class="h1 text-light text-right mb-4">
								<i class="fa fa-exclamation-triangle"></i>
							</div>
							<div class="h4 mb-0 text-light"><?=$porvencer_30diasOmenos?></div>
							<small class="text-light text-uppercase font-weight-bold">Vence en ~30 días</small>
							<div class="progress progress-xs mt-3 mb-0 bg-light" style="width: 40%; height: 5px;"></div>
						</div>
						<div class="card-footer">
							<small class="text-light">
								<a href="../contract_reportes/index.php?optSelec=3&chk_vence30=1&fechaIni=<?=date("Y")?>-01-01&fechaFin=<?=date("Y")?>-12-31">Ver detalle <i class="fa fa-arrow-circle-right"></i></a>
							</small>
						</div>
					</div>
					</div>
					<!--/.col-->
				</div>
			</div>


            <div class="col-xl-6">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <h4 class="card-title mb-0">Histórico por estado</h4>
                                <div class="small text-muted">Año 2019</div>
                            </div>
                        </div>
                        <!--/.row-->
                        <div class="chart-wrapper mt-4">
                            <canvas id="byestado" style="height: 250px;" ></canvas>
                        </div>

                    </div>                    
                </div>
            </div>


            

        </div> <!-- .content -->
    </div><!-- /#right-panel -->

    <!-- Right Panel -->

    <script src="vendors/jquery/dist/jquery.min.js"></script>   
    <script src="vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>


    <script src="vendors/chart.js/dist/Chart.bundle.min.js"></script>
    <script src="assets/js/dashboard.js"></script>
    <script src="assets/js/widgets.js"></script>    

	<script>
		( function ( $ ) {
    "use strict";


// const brandPrimary = '#20a8d8'
const brandSuccess = '#4dbd74'
const brandInfo = '#63c2de'
const brandDanger = '#f86c6b'

function convertHex (hex, opacity) {
  hex = hex.replace('#', '')
  const r = parseInt(hex.substring(0, 2), 16)
  const g = parseInt(hex.substring(2, 4), 16)
  const b = parseInt(hex.substring(4, 6), 16)

  const result = 'rgba(' + r + ',' + g + ',' + b + ',' + opacity / 100 + ')'
  return result
}

function random (min, max) {
  return Math.floor(Math.random() * (max - min + 1) + min)
}

    var elements = 12
   var data1 = [
				<?=$data_por_estado1['enero']?>, 
				<?=$data_por_estado1['febrero']?>, 
				<?=$data_por_estado1['marzo']?>, 
				<?=$data_por_estado1['abril']?>, 
				<?=$data_por_estado1['mayo']?>, 
				<?=$data_por_estado1['junio']?>, 
				<?=$data_por_estado1['julio']?>, 
				<?=$data_por_estado1['agosto']?>,
				<?=$data_por_estado1['septiembre']?>,
				<?=$data_por_estado1['octubre']?>,
				<?=$data_por_estado1['noviembre']?>,
				<?=$data_por_estado1['diciembre']?>] //registrados
				
    var data2 = [<?=$data_por_estado2['enero']?>, 
				<?=$data_por_estado2['febrero']?>, 
				<?=$data_por_estado2['marzo']?>, 
				<?=$data_por_estado2['abril']?>, 
				<?=$data_por_estado2['mayo']?>, 
				<?=$data_por_estado2['junio']?>, 
				<?=$data_por_estado2['julio']?>, 
				<?=$data_por_estado2['agosto']?>,
				<?=$data_por_estado2['septiembre']?>,
				<?=$data_por_estado2['octubre']?>,
				<?=$data_por_estado2['noviembre']?>,
				<?=$data_por_estado2['diciembre']?>]  //concluidos
				
    var data3 = [<?=$data_por_estado3['enero']?>, 
				<?=$data_por_estado3['febrero']?>, 
				<?=$data_por_estado3['marzo']?>, 
				<?=$data_por_estado3['abril']?>, 
				<?=$data_por_estado3['mayo']?>, 
				<?=$data_por_estado3['junio']?>, 
				<?=$data_por_estado3['julio']?>, 
				<?=$data_por_estado3['agosto']?>,
				<?=$data_por_estado3['septiembre']?>,
				<?=$data_por_estado3['octubre']?>,
				<?=$data_por_estado3['noviembre']?>,
				<?=$data_por_estado3['diciembre']?>]   //vigentes


    //Traffic Chart
    var ctx = document.getElementById( "byestado" );
    //ctx.height = 200;
    var myChart = new Chart( ctx, {
        type: 'line',
        data: {
            labels: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre' ],
            datasets: [
            {
              label: 'Registrados',
              backgroundColor: convertHex(brandInfo, 10),
              borderColor: brandSuccess,
              pointHoverBackgroundColor: 'green',
              borderWidth: 2,
              data: data1
          },
          {
              label: 'Concluidos',
              backgroundColor: 'transparent',
              borderColor: brandInfo,
              pointHoverBackgroundColor: '#2271b3',
              borderWidth: 2,
              data: data2
          },
          {
              label: 'Anulados',
              backgroundColor: 'transparent',
              borderColor: brandDanger,
              pointHoverBackgroundColor: 'red',
              borderWidth: 1,
              borderDash: [8, 5],
              data: data3
          }
          ]
        },
        options: {
            maintainAspectRatio: true,
            legend: {
                display: true
            },
            responsive: true,
            scales: {
                xAxes: [{
                  gridLines: {
                    drawOnChartArea: false
                  }
                }],
                yAxes: [ {
                      ticks: {
                        beginAtZero: true,
                        maxTicksLimit: 5,
                        stepSize: Math.ceil(<?=$max_value_por_estado?> / 5),
                        max: <?=$max_value_por_estado?>
                      },
                      gridLines: {
                        display: true
                      }
                } ]
            },
            elements: {
                point: {
                  radius: 0,
                  hitRadius: 10,
                  hoverRadius: 4,
                  hoverBorderWidth: 3
              }
          }


        }
    } );
	
	
	


} )( jQuery );
	</script>


