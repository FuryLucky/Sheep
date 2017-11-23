<?php

function dashboard(){

    $i = 0;

    $color = [
    	'1' => 'ff4d4d',
    	'2' => 'ffff4d',
    	'3' => '5cd65c',
    	'4' => '33ffff',
    	'5' => '9966ff'
    ];

    $width = 25;
    $total = getAllSpends();
    $depenses = getSpendByUser();

    $userName = getUserName();

    $cPage = 1;
    $data = getIDPage();
    $pageID = $data['nbSpends'];
    $nbPage = ceil($pageID/20);

    if( isset($_GET['page']) && $nbPage == 0 ) {

    	header('Location: /404');
    	exit;
    }

    if (isset($_GET['page'])) {
    	$cPage = $_GET['page'];
    }else{
    	$cPage = 1;
    }

    $datas = getSpendByDate(($cPage-1)*20);
    
    include __DIR__ . '/../views/back/dashboard.php';
    
}