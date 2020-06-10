<?php
//инициализируем новый обьект класса User
$userDataOperations = new User;

$result = $userDataOperations->checkData($_POST['Firstname'],$_POST['Lastname'],$_POST['email']);


//класс работы с данными пользователя
class User {
	
	private $connectBD;

	//вызываем конструктор класса, в котором устанавлеваем соединение с базой данных через свйоство класса connectBD
	function __construct()
	{
		//вызываем функцию соединения с базой
		$this->connectBD = $this->getConnection();
	}

	//функция соединения с бд
	private function getConnection(){
		
		$conn = new PDO("mysql:host=localhost;dbname=taskdb", 'root', '');
		
		return $conn;
	}

	//В данной функции принимаем данные от POST запроса, проверяем их на валидность, и если всё ок - продолжаем дальше
	public function checkData($first_name,$last_name,$email){
		
		if(empty($first_name)) exit ("Ошибка! Введите корректное имя!");
		if(empty($last_name)) exit ("Ошибка! Введите корректную фамилию!");
		if(empty($email) || !$this->checkEmail($email)) exit ("Ошибка! Введите корректную почту!");

		$userId = $this->addDates($first_name,$last_name,$email);

		if($userId && (intval($userId) > 0)){

			//инициализируем новый обьект класса Moder
		$send = new Moder;
	//вызываем функцию отправки письма для обьекта
	$result = $send->sendEmail($first_name,$last_name,$email);
	//проверяем отправилось ли пиьсмо
	if($result){
		echo $userId;
	} else {
		echo "Проблема с отправкой письма на почту";
		echo $userId;
	}
}
}

//проверка валидности почты
public function checkEmail($email){
	if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
		return true;
	} else {
		return false;
	}
}

		
	//функция добавления данных в бд
	public function addDates($first_name,$last_name,$email){
		try {
			$query = "INSERT INTO tasktable VALUES (NULL, :Firstname, :Lastname, :email)";
			$msg = $this->connectBD -> prepare($query);
			$msg -> execute (['Firstname' => $first_name, 'Lastname' => $last_name, 'email' => $email]);

			$vis_id=$this->connectBD -> lastInsertId();
			return $vis_id;
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}
}

//класс отправки письма
class Moder {
	public function sendEmail($first_name,$last_name,$email){

		$first_name = htmlspecialchars($first_name);
		$last_name = htmlspecialchars($last_name);
		$email = htmlspecialchars($email);
		$first_name = urldecode($first_name);
		$last_name = urldecode($last_name);
		$email = urldecode($email);
		$first_name = trim($first_name);
		$last_name = trim($last_name);
		$email = trim($email);
		$to = "test@developer-alliance.com";
		$subject = "Signup form details";
		$message = "First name:". $first_name . " </br>   Last name: ". $last_name . " </br>   Email:". $email ." </br>";
		$headers = "Content-type: text/html; charset=windows-1251 \r\n";
		$headers .= "From: matjuhins.jaroslavs@gmail.com";
		$headers .= "Reply please to matjuhins.jaroslavs@gmail.com";

        //вызываем функцию отправки
        $result = mail($to, $subject, $message, $headers);
        if($result){
			return true;
		} else {
			return false;
		}
    }
}
?>

