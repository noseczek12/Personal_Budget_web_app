<?php

session_start();
unset($_SESSION['zalogowany']);

header('Location: logowanie.php');

?>