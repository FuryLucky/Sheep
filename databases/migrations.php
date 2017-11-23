<?php

require_once __DIR__.'/../vendor/fzaninotto/faker/src/autoload.php';
$faker = Faker\Factory::create(); // faker la dépendance sous forme d'un objet PHP

# Constantes

define('DB_SEED', true);
define('NUMBER_USER', 5);

# codes utiles

function aleaUserIds($nbIds, $maxUser) {

    $ids = [];

    while (count($ids) < $nbIds) {
        
        $choiceId = rand(1, $maxUser);

        while (in_array($choiceId, $ids) == true) $choiceId = rand(1, $maxUser); {
            $ids[] = $choiceId;
        }
    }

    return $ids;
}

$defaults = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
];

// trois arguments pour se connecter à la base données : 
// le premier c'est la chaîne de connexion
// le deuxième c'est le user
// le dernier pass
$pdo = new PDO('mysql:host=localhost;dbname=sheep','root','', $defaults);

print_r($pdo);

$users = "
	CREATE TABLE `users` (
		`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(100) NOT NULL,
        `password` VARCHAR(100) NOT NULL,
        `email` VARCHAR(100) NOT NULL,
        `avatar`VARCHAR(100) NULL DEFAULT NULL,
        UNIQUE KEY `users_email_unique` (`email`),
        PRIMARY KEY (`id`)
	) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
";

$spends = "
    CREATE TABLE `spends` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `title` VARCHAR(100) NOT NULL,
        `description` TEXT NOT NULL,
        `price` DECIMAL (7,2) NOT NULL,
        `pay_date` DATETIME NULL DEFAULT NULL,
        `status` ENUM ('in progress', 'paid', 'canceled') DEFAULT 'in progress',
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
";

$user_spend = "
    CREATE TABLE `user_spend` (
        `user_id` INT UNSIGNED NULL DEFAULT NULL,
        `spend_id` INT UNSIGNED NOT NULL,
        `price` DECIMAL (7,2) NOT NULL,
        CONSTRAINT `user_spend_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
        CONSTRAINT `user_spend_spend_id_foreign` FOREIGN KEY (`spend_id`) REFERENCES `spends` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
";

$parts = "
	CREATE TABLE `parts` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id` INT UNSIGNED NULL DEFAULT NULL,
    `day` SMALLINT NOT NULL,
    `started` DATETIME NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `parts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
";

$balances = "
    CREATE TABLE `balances` (
        `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
        `user_id` INT UNSIGNED NULL DEFAULT NULL,
        `pricePart` DECIMAL (7,2) NOT NULL,
        `priceStay` DECIMAL (7,2) NOT NULL,
        `priceDebit` DECIMAL (7,2) NOT NULL,
        `priceCredit` DECIMAL (7,2) NOT NULL,
        PRIMARY KEY (`id`),
        CONSTRAINT `balances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
";

$pdo->exec("DROP TABLE IF EXISTS balances");
$pdo->exec("DROP TABLE IF EXISTS parts");
$pdo->exec("DROP TABLE IF EXISTS user_spend");
$pdo->exec("DROP TABLE IF EXISTS spends");
$pdo->exec("DROP TABLE IF EXISTS users");

$pdo->exec($users);
$pdo->exec($spends);
$pdo->exec($user_spend);
$pdo->exec($parts);
$pdo->exec($balances);

if(DB_SEED == true) {
    // $pdo->query("INSERT INTO `users` (`name`, `email`, `password`) 
    //                 VALUES ('{$faker->name}','{$faker->unique()->email}','admin');");

    // OU : 
    // $pdo->query(sprintf(
    //         "INSERT INTO `users` (`name`, `email`, `password`) VALUES ('%s', '%s', '%s');",
    //         $faker->name,
    //         $faker->unique()->email,
    //         'admin'
    //     )
    // );
    
    // Requête préparée => PDO compile cette partie de la requête
    $prepare = $pdo->prepare("INSERT INTO `users` (`name`, `email`, `password`) VALUES (?, ?, ?);");

    for($i = 0; $i < 5 ; $i++) {
        $prepare->bindValue(1, $faker->name);
        $prepare->bindValue(2, $faker->unique()->email);
        $prepare->bindValue(3, 'admin');

        $prepare->execute(); // insert les données dans la table users
    }

    $prepare = null;

    $prepareSpend = $pdo->prepare("INSERT INTO `spends` (`title`, `description`, `price`, `pay_date`, `status`) VALUES (?, ?, ?, ?, ?);");

    for($i = 0; $i < 60 ; $i++) {
        $prepareSpend->bindValue(1, $faker->randomElement(['shopping', 'transport', 'location', 'energy', 'billet', 'visit', 'various']));
        $prepareSpend->bindValue(2, $faker->sentence);
        $nbDec = rand(1,5) == 5 ? 4 : 2;
        $prepareSpend->bindValue(3, $faker->randomFloat($nbDec));
        $t = 60*24*3600;
        $d = rand(0,$t);
        $prepareSpend->bindValue(4, date('Y-m-d h:i:s', time() - $d ));
        $status = rand(0,1) ? 'in progress' : 'paid';
        $prepareSpend->bindValue(5, $status);

        $prepareSpend->execute(); // insert les données dans la table users
    }

    $prepareSpend = null;

    # Partie

    $prepareUser_spend = $pdo->prepare('INSERT INTO `user_spend` (`user_id`, `spend_id`, `price`) VALUES (?, ?, ?) ');

    $queryDepend = $pdo->query('SELECT id, price FROM spends');

    $depends = $queryDepend->fetchAll();

    $queryCountUser = $pdo->query('SELECT COUNT(id) as total FROM users');
    $totalUser = ($queryCountUser->fetch())['total'];

    foreach ($depends as $depend) {
        
        if($depend['price'] > 1000){

            $nbUser = rand(2, NUMBER_USER);
            $priceUser = round($depend['price'] / $nbUser,2);

            $ids = aleaUserIds($nbUser, NUMBER_USER);

            for($i = 0; $i < $nbUser; $i++) {

                $prepareUser_spend->bindValue(1,$ids[$i]);
                $prepareUser_spend->bindValue(2,$depend['id']);
                $prepareUser_spend->bindValue(3,$priceUser);

                $prepareUser_spend->execute();
            }

        }else{

            $prepareUser_spend->bindValue(1,rand(1, NUMBER_USER));
            $prepareUser_spend->bindValue(2,$depend['id']);
            $prepareUser_spend->bindValue(3,$depend['price']);

            $prepareUser_spend->execute();
        }
    }

    $prepareUser_spend = null;
    $queryDepend = null;
}