<?php


	include('router.php');
	include('functions.php');
	$config = include('config.php');
	$pdo=bootstrap();
	$message = '';
	include('header.phtml');

	try {
		resolveRoutes($config);
	} catch (\Exception $e) {
		
	}

	
/*	try{
		if (validateParams('123')){		
			registerUser($_POST);	
				echo 'OK';
		} else {	
		include('registerForm.phtml');	
		}
	} catch(Exception $e) {
		echo $e->getMessage();
		die();
		
		
	}


	try {	
		resolveRoutes();
	} catch (Exception $e){
		echo $e->getMessage();
		
	}
*/






?>