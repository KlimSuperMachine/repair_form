<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require 'phpmailer/src/Exception.php';
	require 'phpmailer/src/PHPMailer.php';

	$mail = new PHPMailer(true);
	$mail->CharSet = 'UTF-8';
	$mail->setLanguage('ru', 'phpmailer/language/');
	$mail->IsHTML(true);

	//От кого письмо
	$mail->setFrom('klimvorobev@ksm2019.ru', 'Заявка на ремонт с GitHub');
	//Кому отправить
	$mail->addAddress('klimvorobev@mail.ru');
	//Тема письма
	$mail->Subject = 'Заявка на ремонт с GitHub';

	//Рука
	$wd = "Передний";
	if($_POST['wd'] == "rwd"){
		$wd = "Задний";
	} else if($_POST['wd'] == "4wd"){
		$wd = "Полный";
	}

	//Тело письма
	$body = '<h1>Заявка</h1>';
	
	if(trim(!empty($_POST['name']))){
		$body.='<p><strong>Имя:</strong> '.$_POST['name'].'</p>';
	}
	if(trim(!empty($_POST['email']))){
		$body.='<p><strong>E-mail:</strong> '.$_POST['email'].'</p>';
	}
	if(trim(!empty($_POST['model']))){
		$body.='<p><strong>Модель:</strong> '.$_POST['model'].'</p>';
	}
	if(trim(!empty($_POST['year']))){
		$body.='<p><strong>Год:</strong> '.$_POST['year'].'</p>';
	}
	if(trim(!empty($_POST['wd']))){
		$body.='<p><strong>Привод:</strong> '.$wd.'</p>';
	}
	if(trim(!empty($_POST['workshop']))){
		$body.='<p><strong>Цех:</strong> '.$_POST['workshop'].'</p>';
	}
	if(trim(!empty($_POST['message']))){
		$body.='<p><strong>Описание неисправности:</strong> '.$_POST['message'].'</p>';
	}
	
	//Прикрепить файл
	if (!empty($_FILES['image']['tmp_name'])) {
		//путь загрузки файла
		$filePath = __DIR__ . "/files/" . $_FILES['image']['name']; 
		//грузим файл
		if (copy($_FILES['image']['tmp_name'], $filePath)){
			$fileAttach = $filePath;
			$body.='<p><strong>Фото в приложении</strong>';
			$mail->addAttachment($fileAttach);
		}
	}

	$mail->Body = $body;

	//Отправляем
	if (!$mail->send()) {
		$message = 'Ошибка';
	} else {
		$message = 'Данные отправлены!';
	}

	$response = ['message' => $message];

	header('Content-type: application/json');
	echo json_encode($response);
?>