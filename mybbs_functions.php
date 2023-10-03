<?php

/* database */

function getPDO() {
	$dsn = 'mysql:dbname=mybbs;host=localhost;port=3306;charset=utf8mb4';
	$username = 'root';
	$password = 'rootpass';
	$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
	try {
		$pdo = new PDO($dsn, $username, $password, $options);
	}
	catch (PDOException $e) {
		$pdo = null;
	}
	return $pdo;
}

function createPost($username, $nickname, $message, $password) {
	$sql = 'INSERT INTO post (username, nickname, message, password) VALUES (:username, :nickname, :message, :password)';
	try {
		$pdo = getPDO();
		if (!$pdo) { return false; }
		$pdo->beginTransaction();
		$statement = $pdo->prepare($sql);
		$statement->bindValue(':username', $username);
		$statement->bindValue(':nickname', $nickname);
		$statement->bindValue(':message', $message);
		$statement->bindValue(':password', password_hash($password, PASSWORD_BCRYPT));
		$statement->execute();
		$pdo->commit();
		$pdo = null;
		return true;
	}
	catch (PDOException $e) {
		if (isset($pdo) && $pdo->inTransaction()) {
			$pdo->rollback();
		}
		$pdo = null;
		return false;
	}
}

function readPost($id) {
	$sql = 'SELECT * FROM post WHERE id = :id AND delete_at IS NULL';
	try {
		$pdo = getPDO();
		if (!$pdo) { return false; }
		$statement = $pdo->prepare($sql);
		$statement->bindValue(':id', $id, PDO::PARAM_INT);
		$statement->execute();
		$pdo = null;
		return $statement->fetch();
	}
	catch (PDOException $e) {
		$pdo = null;
		return false;
	}
}

function updatePost($id, $nickname, $message) {
	$sql = 'UPDATE post SET nickname = :nickname, message = :message WHERE id = :id AND delete_at IS NULL';
	try {
		$pdo = getPDO();
		if (!$pdo) { return false; }
		$pdo->beginTransaction();
		$statement = $pdo->prepare($sql);
		$statement->bindValue(':id', $id, PDO::PARAM_INT);
		$statement->bindValue(':nickname', $nickname);
		$statement->bindValue(':message', $message);
		$statement->execute();
		$pdo->commit();
		$pdo = null;
		return true;
	}
	catch (PDOException $e) {
		if (isset($pdo) && $pdo->inTransaction()) {
			$pdo->rollback();
		}
		$pdo = null;
		return false;
	}
}

function deletePost($id) {
	$sql = 'UPDATE post SET delete_at = NOW() WHERE id = :id AND delete_at IS NULL';
	try {
		$pdo = getPDO();
		if (!$pdo) { return false; }
		$pdo->beginTransaction();
		$statement = $pdo->prepare($sql);
		$statement->bindValue(':id', $id, PDO::PARAM_INT);
		$statement->execute();
		$pdo->commit();
		$pdo = null;
		return true;
	}
	catch (PDOException $e) {
		if (isset($pdo) && $pdo->inTransaction()) {
			$pdo->rollback();
		}
		$pdo = null;
		return false;
	}
}

function readPosts($limit, $offset) {
	$sql = 'SELECT * FROM post WHERE delete_at IS NULL ORDER BY id DESC LIMIT :limit OFFSET :offset';
	try {
		$pdo = getPDO();
		if (!$pdo) { return false; }
		$statement = $pdo->prepare($sql);
		$statement->bindValue(':limit', $limit, PDO::PARAM_INT);
		$statement->bindValue(':offset', $offset, PDO::PARAM_INT);
		$statement->execute();
		$pdo = null;
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}
	catch (PDOException $e) {
		$pdo = null;
		return false;
	}
}

function searchPosts($keyword, $limit, $offset) {
	$sql = 'SELECT * FROM post WHERE delete_at IS NULL AND CONCAT(nickname, message) REGEXP :regexp ORDER BY id DESC LIMIT :limit OFFSET :offset';
	$str = addcslashes($keyword, '\\?');
	$regexp = str_replace(' ', '|', $str);
	try {
		$pdo = getPDO();
		if (!$pdo) { return false; }
		$statement = $pdo->prepare($sql);
		$statement->bindValue(':regexp', $regexp);
		$statement->bindValue(':limit', $limit, PDO::PARAM_INT);
		$statement->bindValue(':offset', $offset, PDO::PARAM_INT);
		$statement->execute();
		$pdo = null;
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}
	catch (PDOException $e) {
		$pdo = null;
		return false;
	}
}

function countAllPosts() {
	$sql = 'SELECT * FROM post WHERE delete_at IS NULL';
	try {
		$pdo = getPDO();
		if (!$pdo) { return false; }
		$statement = $pdo->prepare($sql);
		$statement->execute();
		$pdo = null;
		return $statement->rowCount();
	}
	catch (PDOException $e) {
		$pdo = null;
		return false;
	}
}

function countSearchPosts($keyword) {
	$sql = 'SELECT * FROM post WHERE delete_at IS NULL AND CONCAT(nickname, message) REGEXP :regexp';
	$str = addcslashes($keyword, '\\?.');
	$regexp = str_replace(' ', '|', $str);
	try {
		$pdo = getPDO();
		if (!$pdo) { return false; }
		$statement = $pdo->prepare($sql);
		$statement->bindValue(':regexp', $regexp);
		$statement->execute();
		$pdo = null;
		return $statement->rowCount();
	}
	catch (PDOException $e) {
		$pdo = null;
		return false;
	}
}

/* create value */

function createTokenValue() {
	session_start();
	session_regenerate_id();
	$token = uniqid('', true);
	$_SESSION['token'] = $token;
	return $token;
}

function createUsernameValue() {
	$data = $_SERVER['REMOTE_ADDR'];
	$key = 'foobar_helloworld_256';
	$hash = hash_hmac('sha256', $data, $key);
	$base64 = base64_encode($hash);
	$username = substr($base64, 0, 8);
	return $username;
}

/* validate value */

function isTokenValue($value) {
	session_start();
	session_regenerate_id();
	return isset($_SESSION['token']) && $_SESSION['token'] == $value;
}

function isNicknameValue($value) {
	$pattern = '/^.{1,100}$/';
	return preg_match($pattern, $value);
}

function isMessageValue($value) {
	$pattern = '/^[\s\S]{1,500}$/';
	return preg_match($pattern, $value);
}

function isPasswordValue($value) {
	$pattern = '/^[0-9A-Za-z]{4,128}$/';
	return preg_match($pattern, $value);
}

function isPasswordMatch($value, $hash) {
	return password_verify($value, $hash);
}

/* get value */

function getTokenValue() {
	return isset($_POST['token']) && $_POST['token'] != '' ? $_POST['token'] : null;
}

function getIdValue() {
	return isset($_POST['id']) && $_POST['id'] != '' ? $_POST['id'] : null;
}

function getNicknameValue() {
	return isset($_POST['nickname']) && $_POST['nickname'] != '' ? $_POST['nickname'] : null;
}

function getMessageValue() {
	return isset($_POST['message']) && $_POST['message'] != '' ? $_POST['message'] : null;
}

function getPasswordValue() {
	return isset($_POST['password']) && $_POST['password'] != '' ? $_POST['password'] : null;
}

/* get param */

function getIdParam() {
	return isset($_GET['id']) && $_GET['id'] != '' ? $_GET['id'] : null;
}

function getPageParam() {
	return isset($_GET['page']) && $_GET['page'] != '' ? $_GET['page'] : null;
}

function getKeywordParam() {
	return isset($_GET['keyword']) && $_GET['keyword'] != '' ? $_GET['keyword'] : null;
}

/* check method */

function isGetMethod() {
	return $_SERVER['REQUEST_METHOD'] == 'GET';
}

function isPostMethod() {
	return $_SERVER['REQUEST_METHOD'] == 'POST';
}

/* check page */

function isIndexPage() {
	return basename($_SERVER['SCRIPT_NAME']) == 'index.php';
}

function isCreatePage() {
	return basename($_SERVER['SCRIPT_NAME']) == 'create.php';
}

function isReadPage() {
	return basename($_SERVER['SCRIPT_NAME']) == 'read.php';
}

function isUpdatePage() {
	return basename($_SERVER['SCRIPT_NAME']) == 'update.php';
}

function isDeletePage() {
	return basename($_SERVER['SCRIPT_NAME']) == 'delete.php';
}

function isSearchPage() {
	return basename($_SERVER['SCRIPT_NAME']) == 'search.php';
}

/* pager value */

function getPostsLimit() {
	return 10;
}

function getPostsOffset($page, $limit) {
	return ($page - 1) * $limit;
}

function getFirstPage() {
	return 1;
}

function getLastPage($count, $limit) {
	return ceil($count / $limit);
}

function getPrevPage($page, $min) {
	return max($page - 1, $min);
}

function getNextPage($page, $max) {
	return min($page + 1, $max);
}

function getPagerStart($page, $min) {
	return max($page - 3, $min);
}

function getPagerEnd($page, $max) {
	return min($page + 3, $max);
}

/* escape value */

function escapeHtml($value) {
	return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
