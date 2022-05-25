<?php

if (isset($_GET['key'])) $key = $_GET['key']; else $key = "";
  if (isset($_POST['submit'])) $submit = true; else $submit = false;

  if (isset($_POST['name'])) $name = $_POST['name']; else $name = "";
  if (isset($_POST['login'])) $login = $_POST['login']; else $login = "";
  if (isset($_POST['email'])) $email = $_POST['email']; else $email = "";
  if (isset($_POST['pass'])) $pass = $_POST['pass']; else $pass = "";
  if (isset($_POST['pass2'])) $pass2 = $_POST['pass2']; else $pass2 = "";
  if (isset($_GET['status'])) $status = $_GET['status']; else $status = "";
  if (isset($_GET['active'])) $active = $_GET['active']; else $active = "";

 if($status and $status == 'ok')
 	echo '<div class="alert alert-success"><h4 class="text-center">Вы успешно зарегистрировались! Пожалуйста активируйте свой аккаунт!</h4><h4 class="text-center">Переходите по ссылке, отправленной на указанный почтновый ящик!</h4></div><style>.page-main{display:none;}</style>';

 if($active and $active == 'ok')
	echo '<div class="alert alert-success"><h4 class="text-center">Ваш аккаунт на '.HOST.' успешно активирован!</h4></div>
	<a class="form__link text-center text-center-block" href="'.HOST.'?mode=auth">Перейти на форму входа</a>
	<style>.page-main {display:none;}</style>';

 if($key)
 {
    $sql = "SELECT *
        FROM `".TABLE_REG."`
        WHERE `active_hex` = '$key'
        AND `status` <> 1";
    $stmt = $db->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if(count($rows) == 0)
      $err[] = 'Ключ активации не верен!';

    if(count($err) > 0)
      echo showErrorMessage($err);
    else
    {
      $login = $rows[0]['login'];
      $email = $rows[0]['email'];

      $sql = "UPDATE `".TABLE_REG."`
          SET `status` = 1
          WHERE `login` = '$login'";
      $stmt = $db->prepare($sql);
      $stmt->execute();

      $title = 'Ваш аккаунт успешно активирован';
      $message = 'Поздравляю Вас, Ваш аккаунт на '.HOST.' успешно активирован';

      sendMessageMail($email, MAIL_AUTOR, $title, $message);

      header('Location:'. HOST .'?mode=reg&active=ok');
      exit;
	}
 }
 if($submit)
 {

  if(empty($name))
    $err[] = 'Поле ФИО не может быть пустым!';
  else if (!nameValid($name))
    $err[] = 'Недопустимо длинное значение для поля ФИО';

  if(empty($login))
    $err[] = 'Поле Логин не может быть пустым!';
  else if (!loginValid($login))
    $err[] = 'Недопустимо длинное значение для поля Логин';

	if(empty($email))
		$err[] = 'Поле Email не может быть пустым!';
	else
	{
		if(emailValid($email) === false)
           $err[] = 'Не правильно введен E-mail'."\n";
	}

	if(empty($pass))
		$err[] = 'Поле Пароль не может быть пустым';

	if(empty($pass2))
		$err[] = 'Поле Подтверждения пароля не может быть пустым';

	if(count($err) > 0)
		echo showErrorMessage($err);
	else
	{
		if($pass != $pass2)
			$err[] = 'Пароли не совподают';
	    if(count($err) > 0)
			echo showErrorMessage($err);
		else
		{
			$sql = "SELECT `login`
					FROM `".TABLE_REG."`
					WHERE `login` = '$login'";
      $sql_2 = "SELECT `email`
      FROM `".TABLE_REG."`
      WHERE `email` = '$email'";
			$stmt = $db->prepare($sql);
			$stmt->execute();
			$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

      $stmt_2 = $db->prepare($sql_2);
			$stmt_2->execute();
			$rows_2 = $stmt_2->fetchAll(PDO::FETCH_ASSOC);

			if(count($rows) > 0) {
        $err[] = 'К сожалению Логин: <b>'. $login .'</b> занят!';
      }
			if(count($rows_2) > 0) {
        $err[] = 'К сожалению email: <b>'. $email .'</b> занят!';
      }

			if(count($err) > 0)
				echo showErrorMessage($err);

			else
			{
				$salt = salt();
				$pass = md5(md5($pass).$salt);
				$hex = md5($salt);
				$sql="INSERT INTO `".TABLE_REG."`(`name`, `login`, `pass`, `email`, `salt`, `active_hex`, `status`) VALUES ('$name','$login','$pass','$email','$salt','$hex','0')";
				$stmt = $db->prepare($sql);
				if($stmt->execute())
				{
					$url = HOST .'?mode=reg&key='. md5($salt);
					$title = 'Регистрация';
					$message = 'Для активации Вашего акаунта пройдите по ссылке
					<a href="'. $url .'">'. $url .'</a>';

					sendMessageMail($email, MAIL_AUTOR, $title, $message);

					header('Location:'. HOST .'?mode=reg&status=ok');
					exit;
				}
				else
				{
					echo 'Запись не произошла, смотрим ЛОГи сервера!';
				}
			}
		}
	}
 }

?>