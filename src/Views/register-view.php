<?php

/**
 * This is just an example.
 * To use your view create Views/userlib/register-view.php
 */
helper('url');
helper('form_validation');
$registerUrl = url_to('registerurl');
$cloudflareSiteKey = "";
$config = config(\Franky5831\CodeIgniter4UserLibrary\Config\App::class);
$userExtraAttributes = $config->userExtraAttributes;
?>
<?= \Config\Services::validation()->listErrors() ?>
<form action="<?= $registerUrl ?>" method="post" id="userForm">
	<input type="email" name="email" placeholder="Email">
	<input type="password" name="password" placeholder="Password">
	<input type="password" name="password_confirm" placeholder="Confirm Password">
	<?php foreach ($userExtraAttributes as $attribute => $data): ?>
		<input type="<?= $data["type"] ?>" name="<?= $attribute ?>" placeholder="<?= $data["label"] ?>">
	<?php endforeach; ?>
	<?=
	// If you happen to override the register view you should still include the captcha view
	view('../../vendor/franky5831/codeigniter4-user-library/src/Views/captcha', array("label" => "Register")) ?>
</form>