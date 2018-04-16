<?php

	function validateUserForm($params) {
		if (!is_array ($params)) {
			throw new Exception('given param is not an array');
		}			
		if (isset($params['email']) and isset($params['password']) and isset($params['password-2']) and isset($params['firstName']) and 
			isset($params['lastName']) and isset($params['username'])) {
//*			if	((strlen($_params['firstName']) >= 2 and strlen($_params['firstName']) <= 32 and preg_match("/[^a-zA-Z\_-]/i", $params)) and
			if	((strlen($params['lastName']) >= 2 and strlen($params['lastName']) <= 32) and
				(strlen($params['username']) >= 2 and strlen($params['username']) <= 32) and
				(strlen($params['email']) >= 6 and strlen($params['email']) <= 26 and strstr($params['email'], '@', true)) and 
				(strlen($params['password']) >= 4 and strlen($params['password']) <= 14 and ($params['password-2'] === $params['password']))) {
					return true;
				} 
				else {	
					echo "nije ok";
					return false;
				}	
			}
			else { 
				return false;
		}
	}

	function saveUser($params) {
		$fileName = saveImage();
		$userData = [
			'email' => $params['email'], 
			'password' => $params['password'], 
			'firstName' => $params['firstName'], 
			'lastName' => $params['lastName'], 
			'username' => $params['username'], 
			'image' => $fileName
		];
		$tmp = file_get_contents('storage.json');
		if (strlen($tmp) === 0) {
			$data=[$userData];
		} else {
			$data = json_decode($tmp);
			$data[] = $userData;
		} 
		return file_put_contents('storage.json', json_encode($data));
	}

	function saveImage(){
		$fileName=APP_PATH.'/images/'.$_FILES['image']['name'];
			if (!move_uploaded_file($_FILES['image']['tmp_name'],$fileName)){

			}
			return 'images/'.$_FILES['image']['name'];

	}

	function validateLoginForm($params) {
		if (!is_array ($params)) {
			throw new Exception('given param is not an array');
		}			
		if (isset($params['email']) || isset($params['password'])) {
			if ((strlen($params['email']) >= 6 and strlen($params['email']) <= 26 and strstr($params['email'], '@', true))and 
			(strlen($params['password'])>= 4 and strlen($params['password'])<= 14)) {
				return true;
			} 
			else {		
				return false;
			}
		}
		else {
               return false;
          }
	}

	
	// ovde dole biramo sta hocemo da upisemo u bazu, koje podatke iz $params
	
	function registerUser ($params) {
		$data = file_get_contents('storage.json');
		$data = json_encode(['email' => $params['email'], 'password' => $params['password']]) . PHP_EOL;
		file_put_contents('storage.json', $data);
	}
	
	/* Get users from file storage
      * @return array
    */
	  
	function getUsers(){
          $users = file_get_contents('storage.json');
          return json_decode($users);
    }

	function getUserByEmail ($email) {
		foreach (getUsers() as $user) {
               if($email === $user -> email){
                    return $user;
               }
        }
          return false;
    }
	
	function login ($email, $password) {
		$user = getUserByEmail($email);
		if (!$user){
			return false;
		}
		if ($password === $user -> password){
			$_SESSION['isLoggedIn'] = true;
			return true;		
		}
		
		return false;
	}
	function isLoggedIn (){
		if (isset($_SESSION['isLoggedIn']) && ($_SESSION['isLoggedIn'] === true)) {
			return true;
		}
		return false;
	}
	
	function redirect ($baseUrl, $route='', $statusCode = 302) {
		header ('Location:' . $baseUrl . $route, $statusCode);
		
	}
	function logOut (){
		unset($_session);
		session_destroy();
	}
	
	function bootstrap() {
		session_start();
		error_reporting(E_ALL);
		ini_set('display_errors',1);
		define('APP_PATH', __DIR__);
	}
	
?>







