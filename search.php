<?php

/* require functions */

require_once('mybbs_functions.php');

/* init variables */

$page = null;
$keyword = null;
$limit = null;
$offset = null;
$posts = null;
$count = null;
$firstPage = null;
$lastPage = null;
$prevPage = null;
$nextPage = null;
$pagerStart = null;
$pagerEnd = null;
$error = false;
$readError = false;

/* set variables */

$page = getPageParam();

if (!$page) {
	$page = 1;
}

$keyword = getKeywordParam();
$limit = getPostsLimit();
$offset = getPostsOffset($page, $limit);
$posts = searchPosts($keyword, $limit, $offset);
$count = countSearchPosts($keyword);

if (!$posts || !$count) {
	$error = true;
	$readError = true;
}
else {
    $firstPage = getFirstPage();
    $lastPage = getLastPage($count, $limit);
    $prevPage = getPrevPage($page, $firstPage);
    $nextPage = getNextPage($page, $lastPage);
    $pagerStart = getPagerStart($page, $firstPage);
    $pagerEnd = getPagerEnd($page, $lastPage);
}

/* require template */

require_once('mybbs_template.php');
