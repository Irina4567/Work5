<?php

  if (isset($_POST['reminder'])) $reminder = true; else $reminder = false;
  if (isset($_GET['send'])) $send = $_GET['send']; else $send = false;

  if (isset($_GET['key'])) $key = $_GET['key']; else $key = "";

  if (isset($_POST['name'])) $name = $_POST['name']; else $name = "";
  if (isset($_POST['login'])) $login = $_POST['login']; else $login = "";
  if (isset($_POST['email'])) $email = $_POST['email']; else $email = "";
  if (isset($_POST['pass'])) $pass = $_POST['pass']; else $pass = "";
  if (isset($_POST['pass2'])) $pass2 = $_POST['pass2']; else $pass2 = "";
  if (isset($_GET['newpass'])) $newpass_g = $_GET['newpass']; else $newpass_g = "";

  if (isset($_POST['newPass'])) $newpass_p = true; else $newpass_p = false;

 if($send and $send == 'ok')
 	echo '<div class="alert alert-success"><h4 class="text-center">Ваш запрос на восстановление пароля отправлен на указаный вами email!</h4></div><style>.page-main {display:none;}</style>';

 	if($newpass_g and $newpass_g == 'ok')
 		echo '<div class="alert alert-success"><h4 class="text-center">Ваш пароль успешно изменен, проверьте свой email!</h4></div><style>.page-main {display:none;}</style>';

if($reminder){
    if(emailValid($email)){
		$sql = "SELECT *
				FROM `".TABLE_REG."`
				WHERE `status` = 1
				AND `email` = '$email'";
		$stmt = $db->prepare($sql);
		if($stmt->execute())
		{
			$rows = $stmt->fetch(PDO::FETCH_ASSOC);
			if(!empty($rows))
			{
				$title = 'Вы запросили восстановление пароля';
				$message = 'Для смены пароля Вам нужно пройти по ссылке <a href="'. HOST .'?mode=reminder&key='. $rows['active_hex'] .'">'. HOST .'?mode=reminder&key='. $rows['active_hex'] .'</a>';

				sendMessageMail($email, MAIL_AUTOR, $title, $message);
				header('Location:'. HOST .'?mode=reminder&send=ok');
				exit;
			}
			else
			{
				echo showErrorMessage('Нет такого пользователя!');
			}
		}
		else
		{
				echo showErrorMessage('Что-то пошло не так :(');
		}
	}
	else
	{
		echo showErrorMessage('Не верные данные!');
	}

}

if($newpass_p)
{
	if(empty($pass))
		$err[] = 'Поле Пароль не может быть пустым';

	if(empty($pass2))
		$err[] = 'Поле Подтверждения пароля не может быть пустым';

	if($pass != $pass2)
		$err[] = 'Пароли не совподают!';

	if(count($err) > 0)
		echo showErrorMessage($err);
	else
	{
		$sql = "SELECT
					*
				FROM `".TABLE_REG."`
				WHERE `status` = '1'
				AND `active_hex` = '$key'";

		$stmt = $db->prepare($sql);
		if($stmt->execute())
		{
			$rows = $stmt->fetch(PDO::FETCH_ASSOC);
			$email = $rows['email'];
			$pass = md5(md5($pass).$rows['salt']);
      $id = $rows['id'];
			$active_hex = md5($pass);
			$sql = "UPDATE `".TABLE_REG."`
					SET
						`pass` = '$pass',
						`active_hex` = '$active_hex'
					WHERE `id` = '$id'";
			$stmt_2 = $db->prepare($sql);
			if($stmt_2->execute())
			{
					$title = 'Пароль был изменен';
					$message = 'Вы успешно сменили пароль.
					<p>Для входа в систему перейдите по ссылке <a href="'. HOST .'?mode=auth">'. HOST .'?mode=auth</a></p>';

					sendMessageMail($email, MAIL_AUTOR, $title, $message);

					header('Location:'. HOST .'?mode=reminder&newpass=ok');
					exit;
			}
		}
	}
}

?>