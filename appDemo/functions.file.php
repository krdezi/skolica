<?php

	function validateUserForm($params) {
		if (!is_array ($params)) {
			throw new Exception('given param is not an array');
		}					
		if (!isset($params['email'])) {
			var_dump ('email');
			return false;
		}
		if (!isset($params['password'])) {
			var_dump ('password');
			return false;
		}
		if (!isset($params['password-2'])) {
			var_dump ('password-2');
			return false;
		}
		if (!isset($params['firstName'])) {
			var_dump ('firstName');
			return false;
		}
		if (!isset($params['lastName'])) {
			var_dump ('lastName');
			return false;
		}
		if (!isset($params['username'])) {
			var_dump ('username');
			return false;
		}	
		if (strlen($params['firstName']) <= 2 and strlen($params['firstName']) >= 32) {
			var_dump ('firstName');
			return false;
		}	
		if (strlen($params['lastName']) <= 2 and strlen($params['lastName']) >= 32) {
			var_dump ('lastName');
			return false;
		}
		if (strlen($params['username']) <= 2 and strlen($params['username']) >= 32) {
			var_dump ('username');
			return false;
		}
		if (strlen($params['email']) <= 2 and strlen($params['email']) >= 26 and strstr($params['email'], '@', true)) {
			var_dump ('email');
			return false;
		}
		if (strlen($params['password']) <= 2 and strlen($params['password']) >= 26 ) {
			var_dump ('password');
			return false;
		}	

		if ($params['password-2'] === $params['password']) {
		var_dump ('password');
			return true;
		}	
		else {	
			echo "nije ok";
			return false;
		}	
	}

	function createUser($params) {
		$userData = [
			'email' => $params['email'], 
			'password' => createPasswordHash($params['password']),
			'firstName' => $params['firstName'], 
			'lastName' => $params['lastName'], 
			'username' => $params['username'], 
			'status' => $params['status'],
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


	function createPasswordHash($password) {
		return md5($password);
	}

	function saveImage() {
		if (isset($_FILES['image']['name'])) {
			$fileName=APP_PATH.'/images/'.$_FILES['image']['name'];
			if (!move_uploaded_file($_FILES['image']['tmp_name'],$fileName)) {
				die('slika nije sachuvana');
			}
			return 'images/'.$_FILES['image']['name'];
		} else {
			return '';
		}

	}

	function validateArticleForm($article) {
		if (isset($article['title']) and isset($article['description']) and isset($article['body']) and 
			isset($article['category']) and isset($article['user'])) {
				return true;
			} 
			else {		
				return false;
			}
	}

	function saveArticle($article) {
		$previous_articles = getData('article.json');
		$article = [
			'title' => $article['title'], 
			'description' => $article['description'],
			'body' => $article['body'], 
			'category' => $article['category'], 
			'user' => $article['user'],
		];
		$previous_articles[] = $article;

    	if (file_put_contents('article.json', json_encode($previous_articles))) {
        	return true;
    	}

		return false;
	}

	function getArticles(){
		$articles = file_get_contents('article.json');
		return json_decode($articles);
	  }

	function getArticleByTitle($title){
		foreach (getArticles() as $article) {
			if ($title === $article->title) {
				return $article;
			}
		}
		return false;
   	}
	  
//	@param $new_article

	function updateArticle($new_article) {
    	$articles = [];
    		foreach (getData('article.json') as $article) {
        		if ($article->title === $new_article['title']) {
            	$articles[] = $new_article;
        	} else {
            	$articles[] = $article;
        	}
    	}
    	file_put_contents('article.json', json_encode($articles));
	}


	/**
 	* @return mixed
 	*/
	function getData($file_name) {
    	if (file_get_contents($file_name)) {
        	return json_decode(file_get_contents($file_name));
    	}
	}

	function validateCategoryForm($category_name){
		if (isset($category_name) && strlen($category_name) > 0) {
			return true;
		}
		return false;
	}

	function saveCategory($category_name) {
    	$previous_categories = getData('category.json');
    	$category = [
        	'category_name' => $category_name
    	];

    	$previous_categories[] = $category;

    	if (file_put_contents('category.json', json_encode($previous_categories))) {
        	return true;
    	}

    	return false;
	}

	function validateLoginForm($params) {
		if (!is_array ($params)) {
			throw new Exception('given param is not an array');
		}			
		if (isset($params['email']) and isset($params['password'])) {
			if ((strlen($params['email']) >= 2 and strlen($params['email']) <= 26 and strstr($params['email'], '@', true)) and 
			(strlen($params['password']) >= 2 and strlen($params['password']) <= 26)) {
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
	
	function registerUser($params) {
		$previous_users = getData('storage.json');
    	$user = [
        	'email' => $params['email'],
        	'password' => createPasswordHash($params['password'])
    	];
    	if (!getUserByEmail($params['email'])) {
        	$previous_users[] = $user;
        		if (file_put_contents('storage.json', json_encode($previous_users))) {
            	return true;
        	}
    	}
    	return false;
	}
	
	/* Get users from file storage
      * @return array
    */
	  
	function getUsers() {
		return json_decode(file_get_contents('storage.json'));
	}
	
	// Loop through each user and check the email

	function getUserByEmail($email) {
    	// Loop through each user and check the email
    	foreach (getUsers() as $single_user) {
       		if ($email === $single_user->email) {
           		return $single_user;
        	}

    	}
    	return false;
	}

	/**
 	* @param $email
 	* @param $password
 	*
 	* @return bool
 	*/
	
	function login($email, $password) {
		$user = getUserByEmail($email);
		if (createPasswordHash($password) === $user->password) {
			$_SESSION['isLoggedIn'] = true;
			return true;		
		}
		
		return false;
	}

	function isLoggedIn() {
		if (isset($_SESSION['isLoggedIn']) && ($_SESSION['isLoggedIn'] === true)) {
			return true;
		}
		return false;
	}
	
	function redirect ($baseUrl, $route = '', $statusCode = 302) {
		header ('Location:' . $baseUrl . $route, $statusCode);
		
	}
	function logOut() {
    	unset($_SESSION['logged_in']);
    	session_destroy();
	}
	
	function bootstrap() {
		session_start();
		error_reporting(E_ALL);
		ini_set('display_errors',1);
		define('APP_PATH', __DIR__);
	}
	
?>







