<?php

/* require functions */

require_once('mybbs_functions.php');

/* init variables */

$token = null;
$id = null;
$post = null;
$nickname = null;
$message = null;
$password = null;
$password_hash = null;
$error = false;
$readError = false;
$tokenError = false;
$nicknameError = false;
$messageError = false;
$passwordError = false;
$passwordMatchError = false;
$updateError = false;
$success = false;

/* set variables */

if (isGetMethod()) {
	$token = createTokenValue();
	$id = getIdParam();
	if ($id) {
		$post = readPost($id);
	}
	if ($post) {
		$nickname = $post['nickname'];
		$message = $post['message'];
	}
	else {
		$error = true;
		$readError = true;
	}
}
else if (isPostMethod()) {
	$token = getTokenValue();
	$id = getIdValue();
	$nickname = getNicknameValue();
	$message = getMessageValue();
	$password = getPasswordValue();
	$password_hash = readPost($id)['password'];
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
	if (!$password_hash) {
		$error = true;
		$readError = true;
	}
	if (!isPasswordMatch($password, $password_hash)) {
		$error = true;
		$passwordMatchError = true;
	}
	if (!$error) {
		$success = updatePost($id, $nickname, $message);
		$updateError = !$success;
	}
}

/* require template */

require_once('mybbs_template.php');
