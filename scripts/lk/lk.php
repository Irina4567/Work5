<?php

if (isset($_POST['submit'])) $submit = true; else $submit = false;

if (isset($_POST['name'])) $name = $_POST['name']; else $name = "";
if (isset($_GET['status'])) $status = $_GET['status']; else $status = "";

 if($status and $status == 'ok')
 echo '<div class="alert alert-success"><h4 class="text-center">Вы успешно сменили ФИО! </h4></div><style>.page-main {display:none;}</style>';

 if($submit)
 {
  if(empty($name))
    $err[] = 'Поле Имя не может быть пустым!';
  else if (!nameValid($name))
    $err[] = 'Недопустимо длинное значение для поля Имя';

	if(count($err) > 0)
		echo showErrorMessage($err);
	else
	{
      $id = $_SESSION['id'];
      $sql = "UPDATE `".TABLE_REG."`
      SET `name` = '$name'
      WHERE `id` = '$id'";
      $stmt = $db->prepare($sql);
      if($stmt->execute()) {
        $_SESSION['name'] = $name;
        header('Location:'. HOST .'?mode=lk&status=ok');
        exit;
      } else {
        echo 'Запись не произошла!';
      }
    }
 }

?>