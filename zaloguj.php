<?php

	session_start();
	
	if ((!isset($_POST['email'])) || (!isset($_POST['haslo'])))
	{
		header('Location: logowanie.php');
		exit();
	}
	
	require_once "database.php";
	
		$email = $_POST['email'];
		$haslo = $_POST['haslo'];
		
		$email = htmlentities($email,ENT_QUOTES,"UTF-8");
		$haslo = htmlentities($haslo,ENT_QUOTES,"UTF-8");
		
		$sql = 'SELECT id,username, password,email FROM users WHERE email = :email';
		
		$query = $db->prepare($sql);
		$query->bindValue(':email', $email, PDO::PARAM_STR);
		$query->execute();
		
		$wiersz = $query->fetch();
		//var_dump($wiersz);
		//exit();
				
		if($wiersz && password_verify($haslo, $wiersz['password']))
		{
						$_SESSION['zalogowany'] = true;
						$_SESSION['id'] = $wiersz['id'];
						$_SESSION['nick'] = $wiersz['username'];
						$_SESSION['haslo'] =$wiersz['password'];
					//	$_SESSION['email'] =$wiersz['email'];
						
						
						unset($_SESSION['blad']);
						
						header('Location: menu_glowne.php');
		}
				
		else
		{
						$_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
						header('Location: logowanie.php');
		}

?>

 