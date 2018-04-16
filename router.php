<?php

	function resolveRoutes($config){
		if (!isset ($_GET['route'])){
			$route = 'home';			
		} else {	
			$route = $_GET['route'];
		}			
		switch ($route){
			case 'loginForm':
                    include('loginForm.phtml');
					break;
					
			case 'createUser';
				$valid=validateUserForm($_POST);
					if (!$valid) {
						// global $message;
						// $message=$valid;
						
					}else {
						if (!saveUser($_POST)) {
							echo 'Doslo je do greske prilikom snimanja';
						}else {
							echo 'Korisnik je uspesno sacuvan';
						}
					}		
				break;
					
			case 'login':
				if (validateLoginForm($_POST)and login($_POST['email'], $_POST['password'])){
					redirect ($config['baseUrl'], 'userList&message=loggedIn');				
				}else{
					echo 'ne valjaju parametri';
					include('loginForm.phtml');
				}
				break;
			
			case 'createUserForm':
				include('registerForm.phtml');
				break;
				
			case 'register':
				break;				
				
			case 'userList':
				$users = array();
				if(isset($_GET['emailFilter']) and !empty($_GET['emailFilter'])){
					array_push($users, getUserByEmail($_GET['emailFilter']));
				}else {
					$users = getUsers();
				}
				if (isset ($GET_message) and ($GET_message = loggedIn)){
					$message  = 'Uspesno ste se ulogovali';
				}
				include('userList.phtml');
				break;	
		
			case 'userCreate':
                    	break;

			case 'userCreateForm':
				break;

			case 'userUpdateForm':
			$user = getUserByEmail($_GET['email']);
				include('userUpdateForm.phtml');
				break;

			case 'userUpdate':                  
				break;
					
			case 'logOut':
                    	break;

            		default:
                    	echo "default";
                    	break;
        }
    }

?>