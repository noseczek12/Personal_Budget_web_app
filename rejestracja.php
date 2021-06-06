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
									<a class="nav-link" aria-current="page" href="#">Menu Główne</a>
							</li>
							<li class="nav-item">
									<a class="nav-link" aria-current="page" href="#">Rejestracja</a>
							</li>
							<li class="nav-item">
									<a class="nav-link" aria-current="page" href="#">Zaloguj się</a>
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
									<label for="inputName" class="col-sm-4  col-form-label">Imię: </label>
									<div class="col-sm-6">
										<input type="text" class="form-control" id="inputName">
									</div>
									<label for="inputEmail" class="col-sm-4  col-form-label">E-mail: </label>	
									<div class="col-sm-6">
										<input type="password" class="form-control" id="inputEmail">
									</div>
									<label for="inputPassword" class="col-sm-4  col-form-label">Hasło: </label>
									<div class="col-sm-6">
										<input type="password" class="form-control" id="inputPassword">
									</div>
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