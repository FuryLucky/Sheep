<?php 

require_once __DIR__.'/../app.php';
if ('/' === $uri) {
	index();
} elseif ('/auth' == $uri and $method == 'POST') {
	auth();
} elseif( $uri == '/admin' ){
	if( !isset($_SESSION['auth']) ){
		$_SESSION['message'] = "Vous n'avez pas l'autorisation";
		
		header('Location: /');
		exit;
	}
	dashboard();
} elseif( $uri == '/logout' ){
	if( !isset($_SESSION['auth']) ){
		$_SESSION['message'] = "Vous n'avez pas l'autorisation";

		header('Location: /');
		exit;
	}
	session_destroy(); // controller 
	header('Location: /');
	exit;
} elseif( $uri == '/form' ){
	if( !isset($_SESSION['auth']) ){
		$_SESSION['message'] = "Vous n'avez pas l'autorisation";

		header('Location: /');
		exit;
	}
	addSpend();
	header('Location: /admin');
	exit;
}
else {
    header('HTTP/1.1 404 Not Found');
    echo '<html><body><h1>Page Not Found</h1></body></html>';
}

