<?php
	
	session_start();
	
	//Sprawdzenie czy osoba wchodząca na stronę jest zalogowana
	if(!isset($_SESSION['zalogowany']) && ($_SESSION['zalogowany']!=true)){
		
		header('Location: logowanie.php');
		exit();
	}
	
	if(isset($_POST['amount']))
	{
		//Udana walidacja ? Załóżmy, że tak!
		$udanyWpis = true;
		
		//Sprawdzenie czy wprowadzona kwota jest większa bądź równa 0
		$amount = $_POST['amount'];
		if($amount == null )
		{
			$udanyWpis = false;
			$_SESSION['e_amount'] = "Proszę podać wartość wydatku!";
		}
		
		//Sprawdzenie czy data wydatku została wybrana (jeśli nie wybrana to w bazie będzie 1970-01-01)
		$date = $_POST['expenseDate'];
		//Tu jeszcze konwertujemy zmienną $date do odpowiedniego formatu
		$date = strtotime($_POST['expenseDate']);
		$convertedDate = date('Y-m-d', $date);
		if($convertedDate == "1970-01-01")
		{
			$udanyWpis = false;
			$_SESSION['e_date'] = "Proszę wybrać datę wydatku!";
		}
		
		//Sprawdzenie, czy sposób płatności został wybrany
		$payment = $_POST['paymentCategory'];
		if($payment == "Wybierz...")
		{
			$udanyWpis = false;
			$_SESSION['e_payment'] = "Proszę wybrać sposób płatności!";
		}
		
		//Sprawdzenie czy kategoria wydatku została wybrana
		$category = $_POST['expenseCategory'];
		if($category == "Wybierz...")
		{
			$udanyWpis = false;
			$_SESSION['e_category'] = "Proszę wybrać kategorię wydatku!";
		}
		
		//Sprawdzenie czy komentarz nie przekracza 70 znaków
		$comment = $_POST['expenseComment'];
		if(strlen($comment)>70)
		{
			$udanyWpis = false;
			$_SESSION['e_comment'] = "Komentarz nie powinien przekraczać 70 znaków!";
		}
		
		if($udanyWpis == true)//Hurra, możemy dodać nowy wydatek do bazy!
		{
			//Dajemy polecenie insert
			try{
		
				require_once "database.php";

				$sql='INSERT INTO expenses VALUES(NULL,:userId,:payment,:category,:amount,:date,:comment)';
				$query = $db->prepare($sql);
				$query->bindValue(':userId', $_SESSION['id'], PDO::PARAM_STR);
				$query->bindValue(':payment', $payment, PDO::PARAM_STR);
				$query->bindValue(':category', $category, PDO::PARAM_STR);
				$query->bindValue(':amount', $amount, PDO::PARAM_INT);
				$query->bindValue(':date', $convertedDate, PDO::PARAM_STR);
				$query->bindValue(':comment', $comment, PDO::PARAM_STR);
				$query->execute();
				}

			catch(Exception $e)
			{
				echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności !</span>';
			}	
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
		.error
		{
			color:red;
			margin-top: 10px;
			margin-bottom: 10px;
		}
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
										<a class="nav-link" aria-current="page" href="menu_glowne.php">Menu Główne</a>
								</li>
								<li class="nav-item">
										<a class="nav-link" aria-current="page" href="#">Ustawienia</a>
								</li>
								<li class="nav-item">
										<a class="nav-link" aria-current="page" href="wyloguj.php">Wyloguj się</a>
								</li>
							</ul>
						</div>
				</div>
				</nav>	
		</header>
		
		<main>
		
			<div class="row justify-content-center">
			<div class="col-xs-12 col-sm-7 col-lg-5 login-form-2">
                    <h3>Dodaj Wydatek</h3>
                    <form method="post">
                        <div class="form-group row">
									<label for="inputAmount" class="col-sm-6  col-form-label">Kwota: </label>
									<div class="col-sm-6">
										<input type="number" class="form-control" id="inputAmount" name="amount" step="0.01">
									</div>
									<?php
											if(isset($_SESSION['e_amount']))
											{
												echo '<div class="error">'.$_SESSION['e_amount'].'</div>';
												unset ($_SESSION['e_amount']);
											}
									?>
									
									<label for="inputDate" class="col-sm-6  col-form-label" >Data wydatku: </label>
									<div class="col-sm-6">
										<input type="date" class="form-control" id="inputDate" name="expenseDate">
									</div>
									<?php
											if(isset($_SESSION['e_date']))
											{
												echo '<div class="error">'.$_SESSION['e_date'].'</div>';
												unset ($_SESSION['e_date']);
											}
									?>
									
									<label for="paymentSelect" class="col-sm-6 col-form-label">Sposób płatności:</label>
									<select class="col-sm-6" id="paymentSelect" name="paymentCategory">
											<option selected>Wybierz...</option>
											<option value="gotowka">Gotówka</option>
											<option value="debet">Karta Debetowa</option>
											<option value="kredyt">Karta Kredytowa</option>
									</select>
									<?php
											if(isset($_SESSION['e_payment']))
											{
												echo '<div class="error">'.$_SESSION['e_payment'].'</div>';
												unset ($_SESSION['e_payment']);
											}
									?>
									
									<label for="categorySelect" class="col-sm-6 col-form-label">Kategoria:</label>
									<select class="col-sm-6" id="categorySelect" name="expenseCategory">
											<option selected>Wybierz...</option>
											<option value="jedzenie">Jedzenie</option>
											<option value="mieszkanie">Mieszkanie</option>
											<option value="transport">Transport</option>
											<option value="telekomunikacja">Telekomunikacja</option>
											<option value="zdrowie">Opieka Zdrowotna</option>
											<option value="ubranie">Ubranie</option>
											<option value="higiena">Higiena</option>
											<option value="dzieci">Dzieci</option>
											<option value="rozrywka">Rozrywka</option>
											<option value="wycieczka">Wycieczka</option>
											<option value="szkolenia">Szkolenia</option>
											<option value="ksiazki">Książki</option>
											<option value="oszczednosci">Oszczędności</option>
											<option value="emerytura">Na złotą jesień, czyli emeryturę</option>
											<option value="dlugi">Spłata długów</option>
											<option value="darowizna">Darowizna</option>
											<option value="inne">Inne Wydatki</option>
									</select>
									<?php
											if(isset($_SESSION['e_category']))
											{
												echo '<div class="error">'.$_SESSION['e_category'].'</div>';
												unset ($_SESSION['e_category']);
											}
									?>
									
									<label for="inputComment" class="col-sm-6 col-md-6  col-form-label">Komentarz (opcjonalnie): </label>
									<div class="col-sm-6">
										<input class="form-control " id="inputComment" type="text" name="expenseComment">
									</div>
									<?php
											if(isset($_SESSION['e_comment']))
											{
												echo '<div class="error">'.$_SESSION['e_comment'].'</div>';
												unset ($_SESSION['e_comment']);
											}
									?>
								
									<div class="col-sm-12 form-group">
									<input type="submit" class="btnSubmit" value="Dodaj wydatek" />
									</div>
									<div class="col-sm-12 form-group">
										<a href="menu_glowne.php">
											<input type="button" class="btnAbort"  value="Anuluj" />
										</a>
									</div>
						</div>
                    </form>
					<?php if(isset($udanyWpis) &&  ($udanyWpis==true))
					{
						echo '<span style="color:#20c997;">Nowy wpis został dodany !</span>'; 
					}
					
					?> 
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