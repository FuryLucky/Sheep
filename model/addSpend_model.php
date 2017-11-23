<?php

function addSpend() {

	$pdo = get_pdo();
    
    $prepare = $pdo->prepare("
    	INSERT INTO `spends` (`title`, `price`, `pay_date`) VALUES (?, ?, ?);
	");

	$prepare->bindValue(1, $_POST['title']);
	$prepare->bindValue(2, $_POST['price']);
	$prepare->bindValue(3, $_POST['pay_date']);

	$prepare->execute();

}

function getUserName() {

	$pdo = get_pdo();

	$prepare = $pdo->prepare("
		SELECT name 
		FROM users
	");

	$prepare->execute();

	return $prepare->fetchAll();

}