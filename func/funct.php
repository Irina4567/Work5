<?php
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

 function sendMessageMail($to, $from, $title, $message)
 {
  // Настройки PHPMailer
  $mail = new PHPMailer\PHPMailer\PHPMailer();
  try {
      $mail->isSMTP();
      $mail->CharSet = "UTF-8";
      $mail->Debugoutput = function($str, $level) {$GLOBALS['status'][] = $str;};
      $mail->Mailer = 'smtp';
      $mail->Host       = 'ssl://smtp.yandex.ru';
      $mail->Username   = 'irina.pavliuchenkova@yandex.ru';
      $mail->Password   = 'tdshoreemooktpri';
      $mail->Port       = 465;
      $mail->setFrom('irina.pavliuchenkova@yandex.ru', 'IRINA');
      $mail->addAddress($to);

      $mail->SMTPAuth = true;
      $mail->SMTPSecure = 'ssl';
      $mail->SMTPOptions = array (
          'ssl' => array(
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true)
      );

      $mail->isHTML(true);
      $mail->Subject = $title;
      $mail->Body = $message;

      if ($mail->send()) {$result = "success";}
      else {$result = "error";}

      } catch (Exception $e) {
          $result = "error";
          $status = "Сообщение не было отправлено. Причина ошибки: {$mail->ErrorInfo}";
      }
      $status = $mail->ErrorInfo;

      if ($result = "success") return true;
      else return 'Ошибка отправки письма!';
 }

 function showErrorMessage($data)
 {
    $err = '<div class="alert alert-danger"><ul>'."\n";

	if(is_array($data))
	{
		foreach($data as $val)
			$err .= '<li>'. $val .'</li>'."\n";
	}
	else
		$err .= '<li>'. $data .'</li>'."\n";

	$err .= '</ul></div>'."\n";

    return $err;
 }

 function salt()
 {
	$salt = substr(md5(uniqid()), -8);
	return $salt;
 }

/** Проверка валидации email
* @param string $email
* return boolian
*/
 function emailValid($email){
  if(function_exists('filter_var')){
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
      return true;
    }else{
      return false;
    }
  }else{
    if(!preg_match("/^[a-z0-9_.-]+@([a-z0-9]+\.)+[a-z]{2,6}$/i", $email)){
      return false;
    }else{
      return true;
    }
  }
 }

 function nameValid($name){
  $length = 55;
  if(strlen($name) < $length){
      return true;
  } else {
    return false;
  }
 }

 function loginValid($login){
  $length = 55;
  if(strlen($login) < $length){
      return true;
  } else {
    return false;
  }
 }

 ?>