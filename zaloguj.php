<?php

	session_start();
	
	if ((!isset($_POST['email'])) || (!isset($_POST['haslo'])))
	{
		header('Location: logowanie.php');
		exit();
	}
	
	require_once "connect.php";
	
	$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
	
	if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}
	else
	{
		$email = $_POST['email'];
		$haslo = $_POST['haslo'];
		
		$email = htmlentities($email,ENT_QUOTES,"UTF-8");
		$haslo = htmlentities($haslo,ENT_QUOTES,"UTF-8");
		
		
		if ($rezultat = @$polaczenie->query(sprintf("SELECT * FROM users WHERE email='%s' AND password='%s'", mysqli_real_escape_string($polaczenie,$email),mysqli_real_escape_string($polaczenie,$haslo))))
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
	}

?>

 