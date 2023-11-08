<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "../phps/setup.php";
require "../phps/conexion.php";
require "../phps/dcontract_contratos.php";
require "../phps/dvales_jobs.php";
require '../phps/libreriasphp/PHPMailer/PHPMailerAutoload.php';
require "../phps/dcontract_notificaciones.php";
require "contract_process/actions.php";
require "contract_process/alerts.php";
require "vales_process/actions.php";

exectAlerts_contratos();
exectActions_contratos();
exectActions_vales();