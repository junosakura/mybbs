<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php if (isIndexPage()) { ?>
			<title>MyBBS</title>
		<?php } else if (isSearchPage()) { ?>
			<title><?= escapeHtml($keyword) ?> 検索結果 - MyBBS</title>
		<?php } else if (isCreatePage()) { ?>
			<title>投稿フォーム - MyBBS</title>
		<?php } else if (isReadPage()) { ?>
			<title>No.<?= $id ?> 閲覧フォーム - MyBBS</title>
		<?php } else if (isUpdatePage()) { ?>
			<title>No.<?= $id ?> 編集フォーム - MyBBS</title>
		<?php } else if (isDeletePage()) { ?>
			<title>No.<?= $id ?> 削除フォーム - MyBBS</title>
		<?php } ?>
		<link rel="stylesheet" href="mybbs.css">
	</head>
	<body>
		<div class="app">
			<header class="header">
				<div class="header-container">
					<h1 class="title">
						<a class="title-link" href="index.php">MyBBS</a>
					</h1>
					<form class="search" method="get" action="search.php">
						<input class="search-textbox" type="text" name="keyword">
						<input class="search-button" type="submit" value="検索">
					</form>
					<ul class="menu">
						<li class="menu-item">
							<a class="menu-link" href="create.php">新規投稿</a>
						</li>
					</ul>
				</div>
			</header>
			<main class="main">
				<div class="main-container">
					<?php if (isIndexPage()) { ?>
						<h2 class="heading">投稿一覧</h2>
						<?php if ($readError) { ?>
							<p class="form-error">データが見つかりませんでした。</p>
						<?php } else { ?>
							<p><?= $count ?>件中 <?= $offset + 1 ?>～<?= $offset + count($posts) ?>件目</p>
							<?php foreach ($posts as $post) { ?>
								<article class="post">
									<h3 class="post-title">
										<a class="post-title-link" href="read.php?id=<?= $post['id'] ?>">
											<span class="post-id"><?= $post['id'] ?>.</span>
											<span class="post-nickname"><?= escapeHtml($post['nickname']) ?></span>
											<span class="post-username">◆<?= $post['username'] ?></span>
										</a>
									</h3>
									<pre class="post-main"><?= escapeHtml($post['message']) ?></pre>
									<div class="post-info">
										<div class="post-times">
											<span class="post-time">投稿: <?= $post['create_at'] ?></span>
											<?php if ($post['create_at'] != $post['update_at']) { ?>
												<span class="post-time">更新: <?= $post['update_at'] ?></span>
											<?php } ?>
										</div>
										<div class="post-links">
											<a class="post-link" href="update.php?id=<?= $post['id'] ?>">編集</a>
											<a class="post-link" href="delete.php?id=<?= $post['id'] ?>">削除</a>
										</div>
									</div>
								</article>
							<?php } ?>
							<div class="pager">
								<?php if ($page == $firstPage) { ?>
									<span class="pager-now">&Lt;</span>
									<span class="pager-now">&lt;</span>
								<?php } else { ?>
									<a class="pager-link" href="?page=<?= $firstPage ?>">&Lt;</a>
									<a class="pager-link" href="?page=<?= $prevPage ?>">&lt;</a>
								<?php } for ($i = $pagerStart; $i <= $pagerEnd; $i++) { ?>
									<?php if ($page == $i) { ?>
										<span class="pager-now"><?= $i ?></span>
									<?php } else { ?>
										<a class="pager-link" href="?page=<?= $i ?>"><?= $i ?></a>
									<?php } ?>
								<?php } if ($page == $lastPage) { ?>
									<span class="pager-now">&gt;</span>
									<span class="pager-now">&Gt;</span>
								<?php } else { ?>
									<a class="pager-link" href="?page=<?= $nextPage ?>">&gt;</a>
									<a class="pager-link" href="?page=<?= $lastPage ?>">&Gt;</a>
								<?php } ?>
							</div>
						<?php } ?>
					<?php } if (isSearchPage()) { ?>
						<h2 class="heading"><?= escapeHtml($keyword) ?> 検索結果</h2>
						<?php if ($readError) { ?>
							<p class="form-error">データが見つかりませんでした。</p>
						<?php } else { ?>
							<p><?= $count ?>件中 <?= $offset + 1 ?>～<?= $offset + count($posts) ?>件目</p>
							<?php foreach ($posts as $post) { ?>
								<article class="post">
									<h3 class="post-title">
										<a class="post-title-link" href="read.php?id=<?= $post['id'] ?>">
											<span class="post-id"><?= $post['id'] ?>.</span>
											<span class="post-nickname"><?= escapeHtml($post['nickname']) ?></span>
											<span class="post-username">◆<?= $post['username'] ?></span>
										</a>
									</h3>
									<pre class="post-main"><?= escapeHtml($post['message']) ?></pre>
									<div class="post-info">
										<div class="post-times">
											<span class="post-time">投稿: <?= $post['create_at'] ?></span>
											<?php if ($post['create_at'] != $post['update_at']) { ?>
												<span class="post-time">更新: <?= $post['update_at'] ?></span>
											<?php } ?>
										</div>
										<div class="post-links">
											<a class="post-link" href="update.php?id=<?= $post['id'] ?>">編集</a>
											<a class="post-link" href="delete.php?id=<?= $post['id'] ?>">削除</a>
										</div>
									</div>
								</article>
							<?php } ?>
							<div class="pager">
								<?php if ($page == $firstPage) { ?>
									<span class="pager-now">&Lt;</span>
									<span class="pager-now">&lt;</span>
								<?php } else { ?>
									<a class="pager-link" href="?keyword=<?= $keyword ?>&page=<?= $firstPage ?>">&Lt;</a>
									<a class="pager-link" href="?keyword=<?= $keyword ?>&page=<?= $prevPage ?>">&lt;</a>
								<?php } for ($i = $pagerStart; $i <= $pagerEnd; $i++) { ?>
									<?php if ($page == $i) { ?>
										<span class="pager-now"><?= $i ?></span>
									<?php } else { ?>
										<a class="pager-link" href="?keyword=<?= $keyword ?>&page=<?= $i ?>"><?= $i ?></a>
									<?php } ?>
								<?php } if ($page == $lastPage) { ?>
									<span class="pager-now">&gt;</span>
									<span class="pager-now">&Gt;</span>
								<?php } else { ?>
									<a class="pager-link" href="?keyword=<?= $keyword ?>&page=<?= $nextPage ?>">&gt;</a>
									<a class="pager-link" href="?keyword=<?= $keyword ?>&page=<?= $lastPage ?>">&Gt;</a>
								<?php } ?>
							</div>
						<?php } ?>
					<?php } else if (isCreatePage()) { ?>
						<h2 class="heading">投稿フォーム</h2>
						<?php if ($tokenError) { ?>
							<p class="form-error">トークンエラーが発生しました。もう一度ご送信ください。</p>
						<?php } if ($nicknameError) { ?>
							<p class="form-error">ニックネームは1-20文字で入力してください。</p>
						<?php } if ($messageError) { ?>
							<p class="form-error">メッセージは1-500文字で入力してください。</p>
						<?php } if ($passwordError) { ?>
							<p class="form-error">パスワードは4-128文字の半角英数字で入力してください。</p>
						<?php } if ($createError) { ?>
							<p class="form-error">投稿エラーが発生しました。もう一度ご送信ください。</p>
						<?php } if ($success) { ?>
							<p class="form-success">投稿が完了しました。</p>
							<div class="form-item form-buttons">
								<a class="form-button" href="index.php">ホームへ戻る</a>
							</div>
						<?php } if (!$success) { ?>
							<form class="form" method="post">
								<input type="hidden" name="token" value="<?= $token ?>">
								<div class="form-item">
									<span class="form-label">ニックネーム</span>
									<input class="form-textbox" type="text" name="nickname" value="<?= escapeHtml($nickname) ?>">
								</div>
								<div class="form-item">
									<span class="form-label">メッセージ</span>
									<textarea class="form-textarea" name="message"><?= escapeHtml($message) ?></textarea>
								</div>
								<div class="form-item">
									<span class="form-label">パスワード</span>
									<input class="form-textbox" type="password" name="password">
								</div>
								<div class="form-item form-buttons">
									<input class="form-button" type="submit" value="送信">
								</div>
							</form>
						<?php } ?>
					<?php } else if (isReadPage()) { ?>
						<h2 class="heading">閲覧フォーム</h2>
						<?php if ($readError) { ?>
							<p class="form-error">該当データが見つかりませんでした。</p>
						<?php } else { ?>
							<article class="post">
								<h3 class="post-title">
									<span class="post-id"><?= $post['id'] ?>.</span>
									<span class="post-nickname"><?= escapeHtml($post['nickname']) ?></span>
									<span class="post-username">◆<?= $post['username'] ?></span>
								</h3>
								<pre class="post-main"><?= escapeHtml($post['message']) ?></pre>
								<div class="post-info">
									<div class="post-times">
										<span class="post-time">投稿: <?= $post['create_at'] ?></span>
										<?php if ($post['create_at'] != $post['update_at']) { ?>
											<span class="post-time">更新: <?= $post['update_at'] ?></span>
										<?php } ?>
									</div>
									<div class="post-links">
										<a class="post-link" href="update.php?id=<?= $post['id'] ?>">編集</a>
										<a class="post-link" href="delete.php?id=<?= $post['id'] ?>">削除</a>
									</div>
								</div>
							</article>
							<div class="form-item form-buttons">
								<a class="form-button" href="index.php">ホームへ戻る</a>
							</div>
						<?php } ?>
					<?php } else if (isUpdatePage()) { ?>
						<h2 class="heading">編集フォーム</h2>
						<?php if ($readError) { ?>
							<p class="form-error">該当データが見つかりませんでした。</p>
						<?php } if ($tokenError) { ?>
							<p class="form-error">トークンエラーが発生しました。もう一度ご送信ください。</p>
						<?php } if ($nicknameError) { ?>
							<p class="form-error">ニックネームは1-20文字で入力してください。</p>
						<?php } if ($messageError) { ?>
							<p class="form-error">メッセージは1-500文字で入力してください。</p>
						<?php } if ($passwordError) { ?>
							<p class="form-error">パスワードは4-128文字の半角英数字で入力してください。</p>
						<?php } if ($passwordMatchError) { ?>
							<p class="form-error">パスワードが一致しませんでした。</p>
						<?php } if ($updateError) { ?>
							<p class="form-error">編集エラーが発生しました。もう一度ご送信ください。</p>
						<?php } if ($success) { ?>
							<p class="form-success">編集が完了しました。</p>
							<div class="form-item form-buttons">
								<a class="form-button" href="index.php">ホームへ戻る</a>
							</div>
						<?php } if (!$success && !$readError) { ?>
							<form class="form" method="post">
								<input type="hidden" name="token" value="<?= $token ?>">
								<input type="hidden" name="id" value="<?= $id ?>">
								<div class="form-item">
									<span class="form-label">ニックネーム</span>
									<input class="form-textbox" type="text" name="nickname" value="<?= escapeHtml($nickname) ?>">
								</div>
								<div class="form-item">
									<span class="form-label">メッセージ</span>
									<textarea class="form-textarea" name="message"><?= escapeHtml($message) ?></textarea>
								</div>
								<div class="form-item">
									<span class="form-label">パスワード</span>
									<input class="form-textbox" type="password" name="password">
								</div>
								<div class="form-item form-buttons">
									<input class="form-button" type="submit" value="送信">
								</div>
							</form>
						<?php } ?>
					<?php } else if (isDeletePage()) { ?>
						<h2 class="heading">削除フォーム</h2>
						<?php if ($readError) { ?>
							<p class="form-error">該当データが見つかりませんでした。</p>
						<?php } if ($tokenError) { ?>
							<p class="form-error">トークンエラーが発生しました。もう一度ご送信ください。</p>
						<?php } if ($passwordMatchError) { ?>
							<p class="form-error">パスワードが一致しませんでした。</p>
						<?php } if ($deleteError) { ?>
							<p class="form-error">削除エラーが発生しました。もう一度ご送信ください。</p>
						<?php } if ($success) { ?>
							<p class="form-success">削除が完了しました。</p>
							<div class="form-item form-buttons">
								<a class="form-button" href="index.php">ホームへ戻る</a>
							</div>
						<?php } if (!$success && !$readError) { ?>
							<form class="form" method="post">
								<input type="hidden" name="token" value="<?= $token ?>">
								<input type="hidden" name="id" value="<?= $id ?>">
								<div class="form-item">
									<span class="form-label">ニックネーム</span>
									<input class="form-textbox" type="text" name="nickname" value="<?= escapeHtml($nickname) ?>" readonly>
								</div>
								<div class="form-item">
									<span class="form-label">メッセージ</span>
									<textarea class="form-textarea" name="message" readonly><?= escapeHtml($message) ?></textarea>
								</div>
								<div class="form-item">
									<span class="form-label">パスワード</span>
									<input class="form-textbox" type="password" name="password">
								</div>
								<div class="form-item form-buttons">
									<input class="form-button" type="submit" value="送信">
								</div>
							</form>
						<?php } ?>
					<?php } ?>
				</div>
			</main>
			<footer class="footer">
				<div class="footer-container">
					<p class="copyright">
						<a class="copyright-link" href="index.php">&copy; MyBBS 2023</a>
					</p>
				</div>
			</footer>
		</div>
	</body>
</html>