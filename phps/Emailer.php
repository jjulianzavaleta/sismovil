<?php

function create_smtp_creation(){
	$mail = new PHPMailer;
	//$mail->SMTPDebug = 3;                                // Enable verbose debug output
	//$mail->Debugoutput = function($str, $level) {echo "debug level $level; message: $str";};
	$mail->isSMTP();                                       // Set mailer to use SMTP
	$mail->Host = 'alaec.chimuagropecuaria.com.pe';       					   // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                                // Enable SMTP authentication
	$mail->Username = 'contratos@chimuagropecuaria.com.pe';// SMTP username
	$mail->Password = 'cH555moo010320#';                   // SMTP password
	$mail->SMTPSecure = 'ssl';                             // Enable TLS encryption, `ssl` also accepted
	$mail->Port = 465;                                     // TCP port to connect to
	
	$mail->setFrom('contratos@chimuagropecuaria.com.pe', 'Notificaciones Contratos');
	
	
	return $mail;
}

function addAttachment(&$mail,$location){
	global $dir_subida_general;	
	$mail->addAttachment($dir_subida_general."/".$location);
}

function addEmailDestination(&$mail,$email){	
	$mail->addAddress($email);                  			// Name is optional	
	$mail->addBCC('contratos@chimuagropecuaria.com.pe');    // Copia oculta para el mismo correo
}

function addEmailReplyTo(&$mail,$email){		            // Name is optional
	$mail->addReplyTo($email);	
}

function addCC(&$mail,$email){	
	$mail->addCC($email);	
}

function setEmailData(&$mail,$asunto,$body){
	$mail->isHTML(true);                                    // Set email format to HTML

	$mail->Subject = $asunto;
	$mail->Body    = $body;
	//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
}

function sendEmail(&$mail){	

	if(!$mail->send()) {
		$error_msg = 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
		return array(
					"status" => "error",
		            "error"  =>  $error_msg
					);
	} else {
		return true;
	}
}