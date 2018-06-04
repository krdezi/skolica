<?php
	function resolveRoutes($config) {
		if (!isset ($_GET['route'])) {
			$route = 'home';			
		} else {	
			$route = $_GET['route'];
		}			
		switch ($route) {
			case 'loginForm':
				// redirect($config['base_url'], 'loginform');
				include('loginform.phtml');
			break;

			case 'createUserForm':
				include('userForm.phtml');
			break;
					
			case 'createUser';
				$valid=validateUserForm($_POST);
					if (!$valid) {
						global $message;
						$message=$valid;			
					}else {
						if (!createUser($_POST)) {
							echo 'Doslo je do greske prilikom snimanja';
						}else {
							echo 'Korisnik je uspesno sacuvan';
						}
					}		
			break;

			case 'gerateUsersForm';
				include(generateUsersForm.phtml);
			break;

			case 'populateUsers';
				if (!populateUsers($_POST['count'])) {
					echo "doslo je do greske";
				}
				else {
					
					echo "Korisnici su uspesno sacuvani";
				}

			break;

			case 'userUpdateForm':
				$user = getUserByEmail($_GET['email']);
				if(isset($_GET['email'])){
					include('userForm.phtml');
			   }
			break;

			case 'updateUser':
				$valid = validateUserForm($_POST);
				if(!$valid){
					global $message;
					$message = $valid;
				}
				else {
					if (!userUpdate($_POST)) {
						$message = 'greska - Update user';
					}
					else {
						echo "User updated! ";
					}
				}
			break;
					
			case 'login':
				if (validateLoginForm($_POST) and login($_POST['email'], $_POST['password'])){
					redirect ($config['baseUrl'], 'userList&message=loggedIn');				
				} else {
					include('loginform.phtml');
				}
			break;
				
			case 'register':
				if (validateUserForm($_POST)) {
                if (registerUser($_POST)) {
                    echo 'Registrovan si';
				} 
				else {
                    $message = 'Ne valjaju ti parametri2';
                    include ('registerForm.phtml');
                }
            	} else {
                	$message = 'Ne valjaju ti parametri1';
                	include ('registerForm.phtml');
            	}
            	echo 'register';
				break;				
				
			case 'userList':
				if (!isset ($_GET['page'])) {
					$page = 1;
				} else {
					$page = $_GET['page'];
				}
				$perPage = 20;
				$offset = ($page-1) * $perPage;
				$lastPage = ceil(getUserCount()/$perPage);
				$users = array();

				if (isset($_GET['emailFilter']) and !empty($_GET['emailFilter'])) {
					array_push($users, getUserByEmail($_GET['emailFilter']));
				}else {
					$users = getUsers(['offset' => $offset, 'perPage' => $perPage]); 
					foreach ($users as $user) {
					}
				}
				if (isset($GET['message'])) { 
					if ($GET['message'] === 'loggedIn') {
						$message  = 'Uspesno ste se ulogovali';
					} elseif ($GET['message'] === 'saved') {
						$message  = 'Korisnik uspesno snimljen';
					}
				}
				include('userList.phtml');
			break;	

			case 'createArticleForm':
				$categories = getCategory();
				include ('articleForm.phtml');
			break;

			case 'saveArticle':
				$valid = validateArticleForm($_POST);
				if (!$valid) {
					global $message;
					$message = $valid;
				}
				else {
					if (!saveArticle($_POST)) {
						echo "doslo je do greske";
					}
					else {
						
						echo "Article je uspesno sacuvan";
					}
				}
			break;
			
				case 'articleList':
					$articles = getArticles();
					include('articleList.phtml');
				break;	

				case 'updateArticleForm':
					$article = getArticleByTitle($_GET['title']);
					$categories = getCategory();
					if(isset($_GET['title'])){
						include('articleUpdateForm.phtml');
					}
				break;

				case 'updateArticle' :
					$valid = validateArticleForm($_POST);
					if (!$valid) {
						global $message;
						$message = $valid;
					} else {
						if (!updateArticle($_POST)) {
							echo "greska - Artikal nije updateovan";
						} else {
							echo "Artikal je updateovan";
						}
					}
				break;
				
			case 'articleView':
				if (isset($_GET['title'])){
					$article = getArticleByTitle(trim($_GET['title']));
				} else {
					throw new InvalidArgumentException('Fali ti parametar');

				}
					include "articleView.phtml";
			break;

			case 'createCategoryForm':
				$categories = getCategory();
				include('categoryForm.phtml');
			break;

			case 'createCategory':
				$valid = validateCategoryForm($_POST);
				if(!$valid){
					echo "Ne valja validacija kategorije";
				}
				else {
					if(!saveCategory($_POST)){
						echo "Doslo je do greske";
					}
					else {
						echo "Kategorija je uspesno sacuvana";
					}
				}
			break;

			case 'categoryList':
                $categoryes = getCategory();
                include('categoryList.phtml');
            break;

            case 'categoryUpdateForm':
                $category = getCategoryByName($_GET['category']);
                if(isset($_GET['category'])){
                    include('categoryForm.phtml');
                }
                break;

            case 'categoryUpdate':
                $valid = validateCategoryForm($_POST);
                if (!$valid) {
                    global $message;
                    $message = $valid;
                } else {
                    if (!categoryUpdate($_POST)) {
                        echo "Update error";
                    }else {
                        echo "Category updated!";
                    }
                }
            break;
					
			case 'logOut':
				session_destroy();
				// log_out();
            	// redirect($config['base_url']);
            break;

            default:
                echo "default";
            break;
        }
    }

?>