<?php
	session_start();

	header('Content-Type: text/html; charset=UTF8');
	error_reporting(E_ALL);

	ob_start();

  if (isset($_GET['key'])) $key = true; else $key = false;
  if (isset($_GET['newPass'])) $newPass = true; else $newPass = false;

	$mode = isset($_GET['mode'])  ? $_GET['mode'] : 'sql';
	$user = isset($_SESSION['user']) ? $_SESSION['user'] : false;
  $name = isset($_SESSION['name']) ? $_SESSION['name'] : '';
  $id = isset($_SESSION['id']) ? $_SESSION['id'] : -1;
	$err = array();

	include './config.php';
	include './func/funct.php';
	include './bd.php';

	switch($mode)
	{
		case 'reg':
			include './scripts/reg/reg.php';
			include './scripts/reg/reg_form.html';
		break;

		case 'auth':
			include './scripts/auth/auth.php';
			include './scripts/auth/auth_form.html';
		break;

    case 'lk':
			include './scripts/lk/lk.html';
      include './scripts/lk/lk.php';
		break;

    case 'sql':
			include './scripts/sql/sql.html';
		break;


		case 'reminder';
			include './scripts/reminder/reminder.php';

			if($key)
			{
        $active_hex = $key;
				$sql = "SELECT COUNT(*) AS `total`
						FROM `".TABLE_REG."`
						WHERE `active_hex` = '$active_hex'";
				$stmt = $db->prepare($sql);
				if($stmt->execute())
				{
					$row = $stmt->fetch(PDO::FETCH_ASSOC);
					if($row['total'] == 0)
						include './scripts/reminder/form_reminder.html';
					else
					{
						include './scripts/reminder/new_pass_form.html';
					}
				}
			}
			else
			{
				if(!$newPass)
					include './scripts/reminder/form_reminder.html';
			}
		break;

	}

	$content = ob_get_contents();
	ob_end_clean();
  include './html/index.html';
?>