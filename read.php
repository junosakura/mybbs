<?php

/* require functions */

require_once('mybbs_functions.php');

/* init variables */

$id = null;
$post = null;
$username = null;
$nickname = null;
$message = null;
$create_at = null;
$update_at = null;
$error = false;
$readError = false;

/* set variables */

$id = getIdParam();

if ($id) {
	$post = readPost($id);
}

if ($post) {
	$username = $post['username'];
	$nickname = $post['nickname'];
	$message = $post['message'];
	$create_at = $post['create_at'];
	$update_at = $post['update_at'];
}
else {
	$error = true;
	$readError = true;
}

/* require template */

require_once('mybbs_template.php');
