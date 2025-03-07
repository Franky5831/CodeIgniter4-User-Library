<?php

/**
 * This is just an example.
 * To use your view create Views/userlib/login-view.php
 */
helper(['url', 'form_validation', 'form']);
$loginUrl = url_to('loginurl');
$cloudflareSiteKey = "";
?>
<?= \Config\Services::validation()->listErrors() ?>
<form action="<?= $loginUrl ?>" method="post" id="userForm">
	<input type="email" name="email" value="<?= set_value('email') ?>" placeholder="Email">
	<input type="password" name="password" placeholder="Password">
	<?=
	// If you happen to override the login view you should still include the captcha view
	view('../../vendor/franky5831/codeigniter4-user-library/src/Views/captcha', array("label" => "Login")) ?>
</form>