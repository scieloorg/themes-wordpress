<?php
date_default_timezone_set("Brazil/East");
// Alterar de acordo com as configurações locais
$FROM_NAME = "SciELO NEWS";
$FROM_EMAIL = "anderson.attilio@scielo.org";
$TO_NAME = "Administrador do SciELo Eventos";
$TO_EMAIL = "equipe@scielo.org";
$GMAIL_USERNAME = "suporte.aplicacao@scielo.org";
$GMAIL_PASSWORD = "******";
$URL_PAGETV = "http://news.scielo.org/tv/";

// adicionado por jtak
$content = file_get_contents($URL_PAGETV . "?type=json&period=nextweek");

$json_str = json_decode($content,1);

if(empty($json_str)) {
    die;
}

//include the file
require_once('PHPMailerAutoload.php');

$phpmailer = new PHPMailer();
$phpmailer->CharSet = 'UTF-8';
$phpmailer->IsSMTP(); // telling the class to use SMTP
$phpmailer->Host = "ssl://smtp.gmail.com"; // SMTP server
$phpmailer->SMTPAuth = true;                  // enable SMTP authentication
$phpmailer->Port = 465;          // set the SMTP port for the GMAIL server; 465 for ssl and 587 for tls
$phpmailer->Username = $GMAIL_USERNAME; // Gmail account username
$phpmailer->Password = $GMAIL_PASSWORD;        // Gmail account password

$phpmailer->SetFrom($FROM_EMAIL, $FROM_NAME); //set from name

$phpmailer->Subject = "SciELO - Eventos da Semana";

$body = "Bom dia, <br/><br/>";
$body .= "Os eventos desta semana da equipe SciELO são: <br/><br/>";

// (use lastweek ao invés de nextweek no parâmetro period para pegar os eventos dos últimos 7 dias)
$content = file_get_contents($URL_PAGETV . "?type=json&period=nextweek");
foreach(json_decode($content) as $event) {

	$start_date = date("d/m/Y \à\s H:i", $event->start);
	$body .= "<p>" . "$start_date" . "<br/>" . "<strong style='font-size:120%;'>" . $event->post->post_title . "</strong>" . "<br/>" . $event->post->post_content . "</p>";
}

$body .= "<br/>Atenciosamente, <br/>";
$body .= "SciELO Eventos<br/>";

$phpmailer->AddAddress($TO_EMAIL, $TO_NAME);

$phpmailer->AddAddress($TO_EMAIL, $TO_NAME);
$phpmailer->AddCC('juliotak@gmail.com', 'Júlio');

$phpmailer->MsgHTML($body);
if(!$phpmailer->Send()) {
  echo "Mailer Error: " . $phpmailer->ErrorInfo;
} else {
  echo "Mensagem enviada!";
}
