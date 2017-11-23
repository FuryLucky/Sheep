<?php ob_start(); ?>

<img src="https://d30y9cdsu7xlg0.cloudfront.net/png/90442-200.png" class="image">

<?php if( hasFlashMessage() ): ?> <p><?php echo getFlashMessage(); ?></p> <?php endif ; ?>

<form action="/auth" method="post">
			
	<p><input class="input_log" type="email" name="email" placeholder="Email" value="<?php echo $_SESSION['email']?? ''; ?>"></p>
	<p><input class="input_log" type="password" name="password" placeholder="Password"></p>

	<p><input class="input_log ok" type="submit" name="Ok" value="Ok"></p>

	<p><input class="input_log" type="hidden" name="token" value="<?php echo $token = md5( date('Y-m-d h:i:00').SALT);?>"></p>

</form>

<?php $content = ob_get_clean(); ?>

<?php include __DIR__ . '/../layouts/master.php'; ?>

