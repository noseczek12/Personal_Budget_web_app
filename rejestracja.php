<?php

	session_start();
	
	if(isset($_POST['email']))
	{
		//Udana walidacja? Załóżmy, że tak!
		$wszystko_OK=true;
		
		//Poprawność nicka
		$nick = $_POST['nick'];
		
		//Sprawdzenie długości nicka
		if(strlen($nick)<3 || strlen($nick)>20)
		{
			$wszystko_OK=false;
			$_SESSION['e_nick']="Nick musi posiadać od 3 do 20 znaków!";
		}
		
		if(ctype_alnum($nick)==false)
		{
			$wszystko_OK=false;
			$_SESSION['e_nick']="Nick może składać się tylko z liter i cyfr (bez polskich znaków!)";
		}
		
		//sprawdż poprawność adresu e-mail
		$email =$_POST['email'];
		$emailB=filter_var($email,FILTER_SANITIZE_EMAIL);
		
		if((filter_var($emailB,FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email))
		{
			$wszystko_OK=false;
			$_SESSION['e_email']="Podaj poprawny adres e-mail";
		}
		
		//sprawdź poprawność hasła
		$haslo1 = $_POST['haslo1'];
		$haslo2 = $_POST['haslo2'];
		
		if((strlen($haslo1)<8) ||(strlen($haslo1)>20))
		{
			$wszystko_OK=false;
			$_SESSION['e_haslo']="Hasło musi posiadać od 8 do 20 znaków!";
		}
		
		if($haslo1!=$haslo2)
		{
			$wszystko_OK=false;
			$_SESSION['e_haslo']="Podane hasła nie są identyczne!";
		}
		
		$haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);
		
		
		mysqli_report(MYSQLI_REPORT_STRICT);
		try{
			
		require_once "database.php";
		
		$result = $db->prepare("SELECT id FROM users WHERE email='$email'");
		$ile_takich_maili = $rezultat->num_rows;
		
		if($ile_takich_maili>0)
		{
			$wszystko_OK=false;
			$_SESSION['e_email']="Istnieje już konto przypisane do adresu e-mail!";
		}
		
		$result = $db->prepare("SELECT id FROM users WHERE username='$nick'");
				
		$ile_takich_nickow = $rezultat->num_rows;
		if($ile_takich_nickow>0)
		{
			$wszystko_OK=false;
			$_SESSION['e_nick']="Istnieje już użytkownik o takim nicku! Wybierz inny!";
		}
		
		if($wszystko_OK==true)
		{
			//Hura, wszystkie testy zaliczone, dodajemy gracza do bazy
			$query = $db->prepare("INSERT INTO users VALUES(NULL,:username,:password,:email)");
			$query->bindValue(':username', $nick, PDO::PARAM_STR);
			$query->bindValue(':password', $haslo_hash, PDO::PARAM_STR);
			$query->bindValue(':email', $email, PDO::PARAM_STR);
			$query->execute();
			
			$_SESSION['udanarejestracja']=true;
			header('Location: witamy.php');
					
		}
		
		}
		
		catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie !</span>';
			//echo '<br/>Informcja developerska: '.$e;
		}
		
	}
	
?>

<!DOCTYPE html>
<html lang="pl">
<head>

	<meta charset="utf-8">
	<title>Budżet Osobisty</title>
	<meta name="description" content="Aplikacja, która pomoże Ci ogarnąć Twoje finanse osobiste :) ">
	<meta name="keywords" content="budżet, osobisty, bilans, wydatki, przychody">
	<meta name="author" content="Tomasz Nosol">
	
	<meta http-equiv="X-Ua-Compatible" content="IE=edge,chrome=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
	
	<link rel="stylesheet" href="main.css">
	
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
	
	<!--[if lt IE 9]>
	<script src="//cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
	<![endif]-->
	
	<style>
		.error{color:red; margin-top: 10px; margin-bottom: 10px;}
	</style>
	
</head>

<body>
		<header>
				<img src="img/tlo.png" alt="tło" class="rounded mx-auto d-block img-fluid">
				<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #204ac8;">
				<div class="container-fluid">	
					<a class="navbar-brand" href="#">Mój Budżet</a>
						
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar" aria-controls="collapsibleNavbar" aria-expanded="false" aria-label="Toggle navigation">
							<span class="navbar-toggler-icon"></span>
					</button>	
						
					<div class="collapse navbar-collapse" id="collapsibleNavbar">	
						<ul class="navbar-nav me-auto">
							<li class="nav-item">
									<a class="nav-link" aria-current="page" href="rejestracja.php">Rejestracja</a>
							</li>
							<li class="nav-item">
									<a class="nav-link" aria-current="page" href="logowanie.php">Zaloguj się</a>
							</li>
						</ul>
					</div>
				</div>
				</nav>	
		</header>
		
		<main>
		
		<div class="row justify-content-center">
			<div class="col-xs-12 col-sm-7 col-lg-5 login-form-2">
			
                    <h3>Rejestracja</h3>
                    <form action="rejestracja.php" method="post">
                        <div class="form-group row">
									<label for="inputNick" class="col-sm-3  col-form-label">Nick: </label>
									<div class="col-sm-8">
										<input type="text" class="form-control" id="inputNick" name="nick">
									</div>
									
									<?php
										if(isset($_SESSION['e_nick']))
										{
											echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
											unset($_SESSION['e_nick']);
										}
									?>
									
									<label for="inputEmail" class="col-sm-3  col-form-label">E-mail: </label>	
									<div class="col-sm-8">
										<input type="email" class="form-control" id="inputEmail" name="email">
									</div>
									
									<?php
										if(isset($_SESSION['e_email']))
										{
											echo '<div class="error">'.$_SESSION['e_email'].'</div>';
											unset($_SESSION['e_email']);
										}
									?>
									
									<label for="inputPassword" class="col-sm-3  col-form-label">Hasło: </label>
									<div class="col-sm-8">
										<input type="password" class="form-control" id="inputPassword" name="haslo1">
									</div>
									
									<?php
										if(isset($_SESSION['e_haslo']))
										{
											echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
											unset($_SESSION['e_haslo']);
										}
									?>
									
									<label for="repeatPassword" class="col-sm-3  col-form-label">Powtórz hasło: </label>
									<div class="col-sm-8">
										<input type="password" class="form-control" id="repeatPassword" name="haslo2">
									</div>
									
									<?php
										if(isset($_SESSION['e_regulamin']))
										{
											echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
											unset($_SESSION['e_regulamin']);
										}
									?>
									
									<div class="col-sm-12 form-group">
									<input type="submit" class="btnSubmit" value="Zarejestruj się" />
									</div>
						</div>
                    </form>
            </div>	
		</div>
		
		</main>
		<footer>
			<div class="row">
			<div class="col-sm-12">	
				<div class="info">
					Wszelkie prawa zastrzeżone &copy; 2021 Dziękuję za wizytę !
				</div>
			</div>	
			</div>
		</footer>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
</body>
</html>