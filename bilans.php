<?php
		
	function calcSum($sqlArray){
			$sum = 0.0;
			foreach ($sqlArray as $values){
				$sum+= floatval($values['amount']);
			}
			return $sum;
		}
		
		session_start();
		try{
		
				require_once "database.php";
				$pieChartIncomes= "SELECT category, SUM(amount) FROM incomes WHERE user_id = :userId  GROUP BY category ORDER BY SUM(amount)  DESC ";
				$piechartQueryIncomes = $db->prepare($pieChartIncomes);
				$piechartQueryIncomes->bindValue(':userId', $_SESSION['id'], PDO::PARAM_STR);
				$piechartQueryIncomes->execute();
				
				$resultSetIncomes= "SELECT category, SUM(amount) FROM incomes WHERE user_id = :userId  GROUP BY category ORDER BY SUM(amount)  DESC ";
				$queryIncomes = $db->prepare($resultSetIncomes);
				$queryIncomes->bindValue(':userId', $_SESSION['id'], PDO::PARAM_STR);
				$queryIncomes->execute();
				
				$resultSetExpenses= "SELECT category, SUM(amount) FROM expenses WHERE user_id = :userId  GROUP BY category ORDER BY SUM(amount)  DESC ";
				$queryExpenses = $db->prepare($resultSetExpenses);
				$queryExpenses->bindValue(':userId', $_SESSION['id'], PDO::PARAM_STR);
				$queryExpenses->execute();
				
				$resultSumIncomes= "SELECT amount  FROM incomes WHERE user_id = :userId ";
				$queryIncomesSum = $db->prepare($resultSumIncomes);
				$queryIncomesSum->bindValue(':userId', $_SESSION['id'], PDO::PARAM_STR);
				$queryIncomesSum->execute();
				$resultIncomes = $queryIncomesSum->fetchAll();
				
				$resultSumExpenses= "SELECT amount FROM expenses WHERE user_id = :userId ";
				$queryExpensesSum = $db->prepare($resultSumExpenses);
				$queryExpensesSum->bindValue(':userId', $_SESSION['id'], PDO::PARAM_STR);
				$queryExpensesSum->execute();
				$resultExpenses = $queryExpensesSum->fetchAll();
				}

			catch(Exception $e)
			{
				echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności !</span>';
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
	
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
	<script type="text/javascript">
		google.charts.load('current', {'packages' : ['corechart']});
		google.charts.setOnLoadCallback(drawIncomesChart);
		function drawIncomesChart()
		{
				var data = google.visualization.arrayToDataTable([
						['category', 'amount'],
						<?php
								 while( $developer = $piechartQueryIncomes -> fetch(PDO::FETCH_ASSOC))
								 {
											echo "['".$developer["category"]."', ".$developer["SUM(amount)"]."],";
								 }   				   				  		   				   				  
						?>
				]);
				var options = {  
                      title: 'Kwoty poszczególnych kategorii przychodu',  
                      
                     };  
                var chart = new google.visualization.PieChart(document.getElementById('piechartIncomes'));  
                chart.draw(data, options);
		}
	
	</script>
	
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
									<a class="nav-link" aria-current="page" href="#">Ustawienia</a>
							</li>
							<li class="nav-item">
									<a class="nav-link" aria-current="page" href="#">Wyloguj</a>
							</li>
						</ul>
					</div>
			</div>
			</nav>
		</header>
		
		<main>
		<div class="container">		
		<div class="row">
				<div class="col-sm-1">
						<div class="dropdown">
								<button class="btn btn-primary btn-lg dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
											Wybierz okres
								</button>
								<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
											<li><a class="dropdown-item" href="#">bieżący miesiąc</a></li>
											<li><a class="dropdown-item" href="#">poprzedni miesiąc</a></li>
											<li><a class="dropdown-item" href="#">bieżący rok</a></li>
											<li><a class="dropdown-item" href="#">niestandardowy</a></li>
								</ul>
						</div>
				</div>	
		</div>
		
		<div class="d-flex">
		
		<table id="incomesTable" class="table p-2 caption-top" style="background-color: #204ac8; border: 1px solid white; color:white;">
			<caption style="color: white;"> 
				<h3 class="bd-title text-center">Tabela przychodów </h3>
			</caption>
			<thead>
				<tr>
					<th style ="width: 50%">kategoria</th>
					<th style ="width: 50%">Kwota</th>																				
				</tr>
			</thead>
			<tbody>
				<?php 
				while( $developer = $queryIncomes -> fetch(PDO::FETCH_ASSOC) ) { ?>
				   <tr>
						   <td><?php echo $developer ['category']; ?></td>
						   <td><?php echo $developer ['SUM(amount)']; ?></td>  				   				   				  		   				   				  
				   </tr>
				<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<td class ="text-end" ><b> Razem: </b></td>
					<td><?php echo number_format(calcSum($resultIncomes),2); ?> zł</td>
				</tr>
			</tfoot>
		</table>
		
		
		
		<table id="expensesTable" class="table p-2 caption-top" style="background-color: #204ac8; border: 1px solid white; color:white;">
		<caption style="color: white;"> 
				<h3 class="bd-title text-center">Tabela wydatków </h3>
			</caption>
			<thead>
				<tr>
					<th style ="width: 50%">kategoria</th>
					<th style ="width: 50%">Kwota</th>																								
				</tr>
			</thead>
			<tbody>
				<?php while( $developer = $queryExpenses -> fetch(PDO::FETCH_ASSOC) ) { ?>
				   <tr>
				   <td><?php echo $developer ['category']; ?></td>
				   <td><?php echo $developer ['SUM(amount)']; ?></td>  				   				   				  				   				   				  
				   </tr>
				<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<td class ="text-end"><b> Razem: </b></td>
					<td><?php echo number_format(calcSum($resultExpenses),2);?> zł</td>
				</tr>
			</tfoot>
		</table>
		</div>
		<?php 
					$e = calcSum($resultExpenses);
					$i = calcSum($resultIncomes);
					$balance = floatval( $i - $e );
					echo '<div style="text-align: right;">Twój balans wynosi: '.number_format($balance,2).'zł.</div>';		
		?>
		</br></br>
		<div class = "d-flex">
		<div id="piechartIncomes" class="p-2" style ="width: 50%;"></div>
		
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
		</div>
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js" integrity="sha384-SR1sx49pcuLnqZUnnPwx6FCym0wLsk5JZuNx2bPPENzswTNFaQU1RDvt3wT4gWFG" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.min.js" integrity="sha384-j0CNLUeiqtyaRmlzUHCPZ+Gy5fQu0dQ6eZ/xAww941Ai1SxSY+0EQqNXNE6DZiVc" crossorigin="anonymous"></script>
</body>
</html>