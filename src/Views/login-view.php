<?php

/**
 * This is just an example.
 * To use your view create Views/userlib/login-view.php
 */
helper('url');
helper('form_validation');
$loginUrl = url_to('loginurl');
$cloudflareSiteKey = "";
?>
<?= \Config\Services::validation()->listErrors() ?>
<form action="<?= $loginUrl ?>" method="post" id="userForm">
	<input type="email" name="email" placeholder="Email">
	<input type="password" name="password" placeholder="Password">
	<?=
	// If you happen to override the login view you should still include the captcha view
	view('../../vendor/franky5831/ci4-pckg-userlib/src/Views/captcha', array("label" => "Login")) ?>
</form>