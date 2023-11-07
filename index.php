<?php
include("secureVerifications.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
		<meta charset="utf-8" />
		<title>SISVENMOV Beta</title>

		<meta name="description" content="Login - SCOPE Admin" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<!--basic styles-->
        <link rel="shortcut icon" href="assets/images/ico_fv.ico">
		<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
		<link href="assets/css/bootstrap-responsive.min.css" rel="stylesheet" />
		<link rel="stylesheet" href="assets/css/font-awesome.min.css" />

		<!--[if IE 7]>
		  <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css" />
		<![endif]-->

		<!--page specific plugin styles-->

		<!--fonts-->

		<!--ace styles-->

		<link rel="stylesheet" href="assets/css/ace.min.css" />
		<link rel="stylesheet" href="assets/css/ace-responsive.min.css" />

		<!--[if lt IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->
	</head>

	<body class="login-layout">
   
		<div class="container-fluid" id="main-container">
			<div id="main-content">
				<div class="row-fluid">
					<div class="span12">
						<div class="login-container">
							<div class="space-6"></div>

							<div class="row-fluid">
								<div class="position-relative">
									<div id="login-box" class="visible widget-box no-border">
										<div class="widget-body">
											<div class="widget-main">
												<img src="assets/images/logo.png">
                                                
                                                <h5 class="header blue lighter bigger">
													<i class="icon-coffee grey"></i>
													Ingrese su Informacion
												</h5>
												<div class="space-6"></div>

												<form name="input" action="phps/loginValidar.php" method="post" autocomplete="off">
													<div class="span12">
													 <select class="span12 valid" id="login_mode" name="login_mode">
														<option value="1">Paviferia</option>
														<option value="2">Active Directory</option>
														<option value="3">Consumidor ValesApp</option>
													 </select>
													</div>
													<fieldset>
														<label>
															<span class="block input-icon input-icon-right">
																<input type="text" name="user"
                                                                       autofocus class="span12" placeholder="Usuario" autocomplete="off" required/>
																<i class="icon-user"></i>
															</span>
														</label>
														<label>
															<span class="block input-icon input-icon-right">
																<input type="password" name="pass" class="span12" placeholder="Contraseña" required />
																<i class="icon-lock"></i>
															</span>
														</label>
														<div class="space"></div>
														<div class="row-fluid">
															<button type="submit" class="span12 btn btn-small btn-primary">
																<i class="icon-key"></i>
																Iniciar Sesion
															</button>
														</div>
													</fieldset>
												</form>
											</div><!--/widget-main-->
									</div><!--/login-box-->
								</div><!--/position-relative-->
							</div>
						</div>
					</div><!--/span-->
				</div><!--/row-->
			</div>
		</div><!--/.fluid-container-->

		<!--basic scripts-->

            <script src="assets/js/jquery-1.9.1.min.js"></script>
		<script type="text/javascript">
			window.jQuery || document.write("<script src='assets/js/jquery-1.9.1.min.js'>"+"<"+"/script>");
		</script>

		<!--page specific plugin scripts-->

		<!--inline scripts related to this page-->

		<script type="text/javascript">
			function show_box(id) {
			 $('.widget-box.visible').removeClass('visible');
			 $('#'+id).addClass('visible');
			}
		</script>
	</body>

</html>
