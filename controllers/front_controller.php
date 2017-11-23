<?php

function index(){
	include __DIR__ . '/../views/front/auth.php';
}

function auth(){

	$pdo = get_pdo();

	$flagToken = false;
    $token = $_POST['token'];

	if (isset($token)) {
		foreach(range(0,TOKEN_TIME) as $t){
            if($token == md5(date('Y-m-d h:i:00', time() - 60*$t ).SALT)) {
               $flagToken = true;
            }
        }
	}

    if ($flagToken == false) {
        header('Location: /');
        exit;
    }

    $rules = [
    	
        'email' => FILTER_VALIDATE_EMAIL,
        'password' => [
        	
            'filter' => FILTER_CALLBACK,
            'options' => function($pass){
            	
            	if (strlen($pass) < 4) {
            		return false;
            	}
            	return $pass;	       
            }
        
        ]
    
    ];

    $sanitize = filter_input_array(INPUT_POST, $rules); 

	$email = $_POST['email'];
    $password =  $_POST['password'];

    $prepare = $pdo->prepare('SELECT id, password FROM users WHERE email = ?');
    $prepare->bindValue(1, $email);
    $prepare->execute();

    $stmt =$prepare->fetch(); 

    $_SESSION['email'] = null;
    $_SESSION['email']  = $santize['email'];
    
    if ( $stmt  == false) {
		// redirection avec message d'erreur
		$_SESSION['message'] = "Une erreur dans le mot de passe ou email";
		header('Location: /');
		exit;
    } else {
		if( password_verify($sanitize['password'], $stmt['password']) )
		{
			session_regenerate_id(true); // crée un nouvel identifiant
			$_SESSION['auth'] = $stmt['id'];
			header('Location: admin');// redirection vers une page sécurisée
			exit;
		}else{
			setFlashMessage("Erreur dans le mot de passe ou email");
			
			header('Location: /');
			exit;
		}
	} 
}