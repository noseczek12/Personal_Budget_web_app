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
		
		$query = $db->prepare(sprintf("SELECT * FROM users WHERE email='email' AND password='haslo'"));
		
		if ($rezultat = $query->execute())
		{
			$ilu_userow = $rezultat->num_rows;
			if($ilu_userow>0)
			{
				$wiersz = $rezultat->fetch_assoc();
				
				if(password_verify($haslo, $wiersz['password']))
				{
						$_SESSION['zalogowany'] = true;
						
						
						$_SESSION['id'] = $wiersz['id'];
						$_SESSION['nick'] = $wiersz['nick'];
						$_SESSION['email'] = $wiersz['email'];
						$_SESSION['haslo'] =$wiersz['haslo'];
						
						unset($_SESSION['blad']);
						$rezultat->free();
						
						header('Location: menu_glowne.php');
				}
				
				else
						{
							$_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
							header('Location: logowanie.php');
						}
			}
			else
			{
				$_SESSION['blad'] = '<span style="color:red">Nieprawidłowy login lub hasło!</span>';
				header('Location: logowanie.php');
			}
		}
		
		$polaczenie->close();

?>

 