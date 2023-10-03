<?php

/* require functions */

require_once('mybbs_functions.php');

/* init variables */

$token = null;
$username = null;
$nickname = null;
$message = null;
$password = null;
$error = false;
$tokenError = false;
$nicknameError = false;
$messageError = false;
$passwordError = false;
$createError = false;
$success = false;

/* set variables */

if (isGetMethod()) {
	$token = createTokenValue();
}
else if (isPostMethod()) {
	$token = getTokenValue();
	$username = createUsernameValue();
	$nickname = getNicknameValue();
	$message = getMessageValue();
	$password = getPasswordValue();
	if (!isTokenValue($token)) {
		$error = true;
		$tokenError = true;
		$token = createTokenValue();
	}
	if (!isNicknameValue($nickname)) {
		$error = true;
		$nicknameError = true;
	}
	if (!isMessageValue($message)) {
		$error = true;
		$messageError = true;
	}
	if (!isPasswordValue($password)) {
		$error = true;
		$passwordError = true;
	}
	if (!$error) {
		$success = createPost($username, $nickname, $message, $password);
		$createError = !$success;
	}
}

/* require template */

require_once('mybbs_template.php');
