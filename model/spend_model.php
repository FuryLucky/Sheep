<?php

function getSpendByDate($limit) {

	$pdo = get_pdo();
    
    $prepare = $pdo->prepare('
    	SELECT us.user_id, GROUP_CONCAT(u.name), s.price, s.pay_date, us.price as part
		FROM users as u
		JOIN user_spend as us
		ON us.user_id = u.id
		JOIN spends as s 
		ON s.id = us.spend_id 
		GROUP BY s.pay_date 
		ORDER BY s.pay_date 
		LIMIT ?, ?;'
	);

	$prepare->bindValue(1, $limit, PDO::PARAM_INT);
	$prepare->bindValue(2, PAGINATE, PDO::PARAM_INT);

	$prepare->execute();

    return $prepare->fetchAll();

}

function spend_model($select = "*", $limit = 10){

	$pdo = get_pdo();
    
    $prepare = $pdo->prepare('SELECT * FROM spends LIMIT ?');
   
    $prepare->bindValue(1, $limit, PDO::PARAM_INT);
    $prepare->execute();
    
    return $prepare->fetchAll();

}

function getAllSpends() {

	$pdo = get_pdo();

	$prepare = $pdo->prepare('SELECT SUM(price) FROM user_spend');
	$prepare->execute();

	return $prepare->fetchAll();

}

function getSpendByUser() {

	$pdo = get_pdo();

	$prepare = $pdo->prepare('SELECT SUM(price), u.name  FROM user_spend as us JOIN users as u ON u.id = us.user_id GROUP BY u.id');
	$prepare->execute();

	return $prepare->fetchAll();

}

function getIDPage(){

	$pdo = get_pdo();
    
    $prepare = $pdo->prepare('
    	SELECT COUNT(id) as nbSpends 
    	FROM spends'
	);

	$prepare->execute();

    return $prepare->fetch();

}