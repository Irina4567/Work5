<?php

if (isset($_GET['exit'])) $exit = true; else $exit = false;
if (isset($_POST['submit'])) $submit = true; else $submit = false;

if (isset($_POST['login'])) $login = $_POST['login']; else $login = "";
if (isset($_POST['pass'])) $pass = $_POST['pass']; else $pass = "";

if($user === true) {
  echo '<div class="alert alert-success"><h3 class="text-center">Вы успешно вошли в систему'.$name .'!</h3></div><style>.page-form{display:none;}</style>';
}

 if($exit){
 	session_destroy();

 	header('Location:'. HOST .'?mode=auth');
 	exit;
 }

 if($submit)
 {
	if(empty($login))
		$err[] = 'Не введен Логин';
  else if (!loginValid($login))
    $err[] = 'Недопустимо длинное значение для поля Логин';

	if(empty($pass))
		$err[] = 'Не введен Пароль';

	if(count($err) > 0)
		echo showErrorMessage($err);
	else
	{
		$sql = "SELECT *
				FROM `".TABLE_REG."`
				WHERE `login` = '$login'
				AND `status` = 1";
		$stmt = $db->prepare($sql);
		$stmt->execute();
		$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if(count($rows) > 0)
		{
			if(md5(md5($pass).$rows[0]['salt']) == $rows[0]['pass'])
			{
				$_SESSION['user'] = true;
        $_SESSION['name'] = ', '.$rows[0]['name'];
        $_SESSION['id'] = $rows[0]['id'];

				header('Location:'. HOST .'?mode=auth');
				exit;
			}
			else
				echo showErrorMessage('Неверный пароль!');
		}else{
			echo showErrorMessage('Логин <b>'. $login .'</b> не найден!');
		}
	}
 }

?>