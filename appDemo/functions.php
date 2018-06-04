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
// promeni svuda createUser u createUser
	function createUser($params) {
		global $pdo;
		$passwordHash = createPasswordHash($params['password']);
        $sql = "INSERT INTO `user` (`userId`, `firstName`, `lastName`, `email`, `password`, `username`, `status`, `age`) 
                VALUES (NULL, '{$params['firstName']}', '{$params['lastName']}', '{$params['email']}', '{$passwordHash}', '{$params['username']}', '{$params['status']}', '{$params['age']}')";

		if (!$pdo->exec($sql)) {
		var_dump ($pdo->errorInfo() [2]);
		die ();
		throw new \Exception($pdo->errorInfo [2]);
		}
	}

	// ovde JE STARO NE GLEDAJ
	
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
	  
	function getUsers($params=null) {
		global $pdo;
		$sql="SELECT * FROM `user` ";
			if (isset($params['offset'])){
			$sql .=" LIMIT {$params['offset']}, {$params['perPage']} ";
			}
			$statement=$pdo->query($sql);
			if (!$statement) {
				throw new Exception($pdo->errorInfo()[2]);
			}
		return $statement->fetchAll();
	}

	function getUserCount() {
		global $pdo;
		$sql="SELECT COUNT(*) AS count from `user`";
		$statement=$pdo->query($sql);
			if (!$statement) {
				throw new Exception($pdo->errorInfo()[2]);
			}
		return $statement->fetch()['count'];
	}
	
	// Loop through each user and check the email

	function getUserByEmail($email) {
		global $pdo;
		$sql = "SELECT * FROM `user` WHERE email = '{$email}'";
		$user = $pdo->query($sql)->fetch(PDO::FETCH_OBJ);

		return $user;
	}

	function getUserById($userId) {
		global $pdo;
		$sql = "SELECT * FROM `user` WHERE userId = {$userId}";
		$user = $pdo->query($sql)->fetch();
		return $user;
	}

	function createPasswordHash($password) {
		return md5($password);
	}

	function userUpdate($params){
		global $pdo;
		$user=getUserById($params['userId']);
			if ($params['password'] == '') {
			$params['password'] = $user['password'];
			} else {
				$params['password'] = createPasswordHash($params['password']);
			}
		$sql="UPDATE `user` SET `firstName`='{$params['firstName']}',
								`lastName`='{$params['lastName']}',
								`email`='{$params['email']}',
								`password`='{$params['password']}',
								`username`='{$params['username']}',
								`status`='{$params['status']}',
								`age`='{$params['age']}';
			WHERE `userId` = {$params['userId']}";
		$statement=$pdo->query($sql);
			if (!$statement) {
				throw new Exception($pdo->errorInfo()[2]);
			}
		return $statement->execute();
//		$fileName = saveImage();
   }

   function populateUsers($count) {
		for($i=0; $i<$count; $i++) {
			$user=generateUser();
			createUser($user);
		}
		echo "generisano ".$count." korisnika.";
   }

   function generateUser() {
		$firstNames=['Petar', 'Vendi', 'Zvoncica', 'Smeee', 'Kvrga', 'Popaj', 'Oliva', 'Kica', 'Draguljce', 
					'Dragoljupce'];
		$lastNames=['Pan', 'Sajn', 'Fejl', 'Brook', 'Badza', 'Mornar', 'Maslina', 'Slabinac', 'Porucnik', 'Kuce'];
		$emails=['petar@aa.com', 'vendi@aa.com', 'zvoncica@aa.com', 'smeee@aa.com', 'kvrga@aa.com', 'popaj@aa.com',
				'oliva@aa.com', 'kica@aa.com', 'draguljce@aa.com', 'dragoljupce@aa.com',];
		$usernames=['Pera', 'bebe', 'jakah', 'eeems', 'kakas', 'klokpar', 'lopart', 'werter', 'popert', 'putrat'];

		return [
			'firstName' => $firstNames[rand(0, count($firstNames)-1)],
			'lastName' => $lastNames[rand(0, count($lastNames)-1)],
			'email' => $emails[rand(0, count($emails)-1)],
			'password' => '',
			'username' => $usernames  [rand(0, count($usernames)-1)],
			'status' => rand(0,1),
			'age' => rand(7,77)
		];
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

	function validateArticleForm($params) {
		if (isset($params['title']) and isset($params['description']) and isset($params['body']) and 
			isset($params['categoryId']) and isset($params['userId'])) {
				return true;
			} 
			else {		
				return false;
			}
	}

	function saveArticle($params) {
		global $pdo;
        $sql = "INSERT INTO `article` (`articleId`, `title`, `description`, `body`, `userId`, `categoryId`) 
                VALUES (NULL, '{$params['title']}', '{$params['description']}', '{$params['body']}', 1, 1)";

		return $pdo->exec($sql);
	}

	function getArticles() {
		global $pdo;
        $sql = " SELECT * FROM `article` JOIN `user` USING (userId) JOIN `category` USING (categoryId) ";
		$stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

	function getArticleByTitle($title) {
		global $pdo;
        $sql = " SELECT * FROM `article` WHERE `title` = '{$title}' ";
        $stmt = $pdo->query($sql);
        if(!$stmt){
            throw new Exception($pdo->errorInfo()[2]);
        }
        $article = $stmt->fetch();

        return $article;
    }
	   
	function getArticleById($articleId){
		global $pdo;
		$sql = " SELECT * FROM `article` WHERE `articleId` = '{$articleId}' ";
		$stmt = $pdo->query($sql);
		if(!$stmt){
			throw new Exception($pdo->errorInfo());
		}
		$article = $stmt->fetch();

		return $article;
	}

	function updateArticle($params) {
		global $pdo;
		$article = getArticleById($params['articleId']);
		$sql="UPDATE `article` SET `title`='{$params['title']}',
									`description`='{$params['description']}',
									`body`='{$params['body']}',
									`image`='{$params['image']}',
									`userId`='{$params['userId']}',
									`categoryId`='{$params['categoryId']}',
			WHERE `articleId` = {$params['articleId']}";
		return $pdo->exec($sql);
	}

	/**
 	* @return mixed
 	*/
	function getData($file_name) {
    	if (file_get_contents($file_name)) {
        	return json_decode(file_get_contents($file_name));
    	}
	}

	function validateCategoryForm($params) {
		if (isset($params['categoryName']) && strlen($params['categoryName']) > 0) {
			return true;
		}
		return false;
	}

	function saveCategory($params) {
    	global $pdo;
        $sql = "INSERT INTO `category` (`categoryId`, `name`) VALUES (NULL, '{$params['categoryName']}' )";

        return $pdo->exec($sql);
	}
	 
	function categoryUpdate($params) {
		global $pdo;
		$category = getCategoryById($params['categoryId']);
		$sql = " UPDATE `category` SET `name` = '{$params['categoryName']}' ,
				 WHERE `categoryId` = {$params['categoryId']} ";

		return $pdo->exec(sql);
	}

	function getCategory() {
		global $pdo;
		$sql = " SELECT * FROM `category` ";

		return $pdo->query($sql)->fetchAll();
	}

	function getCategoryByName($name){
		global $pdo;
		$sql = " SELECT * FROM `category` WHERE `name` = '{$name}' ";
		$category = $pdo->query($sql)->fetchAll();

		return $category;
	}

	function getCategoryById($categoryId){
		global $pdo;
		$sql = " SELECT * FROM `category` WHERE `categoryId` = '{$categoryId}' ";
		$stmt = $pdo->query($sql);
		if(!$stmt){
			throw new Exception($pdo->errorInfo());
		}
		$category = $stmt->fetch();

		return $category;
	}

	function validateLoginForm($params) {
		if (!is_array ($params)) {
			throw new Exception('given param is not an array');
		}			
		if (isset($params['email']) and isset($params['password'])) {
			if ((strlen($params['email']) >= 2 and strlen($params['email']) <= 36 and strstr($params['email'], '@', true)) and 
			(strlen($params['password']) >= 2 and strlen($params['password']) <= 36)) {
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

	function connectToMysql() {
		$dsn = 'mysql:host=localhost;dbname=skolica';
		$username='root';
		$password='';
		$pdo = new PDO($dsn,$username,$password);
		return $pdo;
	}
	
	function bootstrap() {
		session_start();
		error_reporting(E_ALL);
		ini_set('display_errors',1);
		define('APP_PATH', __DIR__);
		$pdo=connectToMysql();
		return $pdo;
	}
	
?>







