<?php
/*
If you see this text in your browser, PHP is not configured correctly on this webhost.
Contact your hosting provider regarding PHP configuration for your site.
*/

require_once('form_throttle.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	if (formthrottle_too_many_submissions($_SERVER["REMOTE_ADDR"]))
	{
		echo '{"MusePHPFormResponse": { "success": false,"error": "Too many recent submissions from this IP"}}';
	}
	else
	{
		emailFormSubmission();
	}
}


function emailFormSubmission()
{
	$to = 'zoomax.com.ua@yandex.ua';
	$subject = 'Заявка с сайта: zoomix.com.ua';

	$message = '<!DOCTYPE><html><head><meta charset=UTF-8"><title>' . htmlentities($subject,ENT_COMPAT,'UTF-8') . '</title></head>';
	$message .= '<body style="background-color: #ffffff; color: #000000; font-style: normal; font-variant: normal; font-weight: normal; font-size: 12px; line-height: 18px; font-family: helvetica, arial, verdana, sans-serif;">';
	// $message .= '<h2 style="background-color: #eeeeee;">Посланы новые данные</h2><table cellspacing="0" cellpadding="0" width="100%" style="background-color: #ffffff;">';
	// $message .= '<tr><td valign="top" style="background-color: #ffffff;"><b>Форма:</b></td><td>' . htmlentities($_REQUEST["subject"],ENT_COMPAT,'UTF-8') . '</td></tr>';
	$message .= '<div><b>Имя: </b>' . htmlentities($_REQUEST["name"],ENT_COMPAT,'UTF-8').'</div>';
	$message .= '<div><b>Телефон: </b>' . htmlentities($_REQUEST["phone"],ENT_COMPAT,'UTF-8').'</div>';
	$message .= '<div><b>Что интересует: </b>' . htmlentities($_REQUEST["message"],ENT_COMPAT,'UTF-8').'</div>';

	// $message .= '<div style="background-color: #eeeeee; font-size: 10px; line-height: 11px;">Форма прислана с сайта: *'. '</div>';
	$message .= '<div style="background-color: #eeeeee; font-size: 10px; line-height: 11px;">Visitor IP address: ' . htmlentities($_SERVER["REMOTE_ADDR"],ENT_COMPAT,'UTF-8') . '</div>';
	$message .= '</body></html>';
	$message = cleanupMessage($message);
	$formEmail = cleanupEmail($_REQUEST['Email']);




	$headers = 'From:  info@landing-page-templates.net' . "\r\n" . 'Reply-To: ' . $formEmail .  "\r\n" .'X-Mailer: Adobe Muse 7.0.314 with PHP/' . phpversion() . "\r\n" . 'Content-type: text/html; charset=utf-8' . "\r\n";

	$sent = @mail($to, $subject, $message, $headers);

	if($sent)
	{
    	header('location: thank.html');
	   exit;
	}
	else
	{
		echo '{"MusePHPFormResponse": { "success": false,"error": "Failed to send email"}}';
	}
}

function cleanupEmail($email)
{
	$email = htmlentities($email,ENT_COMPAT,'UTF-8');
	$email = preg_replace('=((<CR>|<LF>|0x0A/%0A|0x0D/%0D|\\n|\\r)\S).*=i', null, $email);
	return $email;
}

function cleanupMessage($message)
{
	$message = wordwrap($message, 70, "\r\n");
	return $message;
}
?>
