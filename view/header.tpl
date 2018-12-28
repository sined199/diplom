<!DOCTYPE html>
<html>
<head>
	<script type="text/javascript" src="/diplom/view/js/face.js"></script>
	<script type="text/javascript" src="/diplom/view/js/jquery-1.9.1.min.js"></script>
	<script type="text/javascript" src="/diplom/view/js/functions.js"></script>
	<script type="text/javascript" src="/diplom/view/js/jquery-ui.js"></script>
	<script type="text/javascript" src="/diplom/view/js/wow.min.js"></script>
	<link rel="stylesheet" type="text/css" href="/diplom/view/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="/diplom/view/css/style.css">
	<link rel="stylesheet" type="text/css" href="/diplom/view/css/jquery-ui.css">
	<link rel="stylesheet" type="text/css" href="/diplom/view/css/animate.css">
  	<link rel="stylesheet" href="/diplom/view/css/gantti.css" />
	<link rel="shortcut icon" href="/diplom/view/images/icons/logo-icon.png" type="image/png">
</head>
<body>
<?php 
	if($is_login){
	?>
		<header>
			<div class="container" style="height:50px">
				<div class="logo"><a href="/diplom/">Rost-d <span class="logo-child">UP</span></a></div>
				<div class="menu-panel">
					<ul class="top-panel-menu col-xs-12">
							<li><a href="/diplom/board">Доска событий</a></li>
							<li><a href="/diplom/projects">Проекты</a></li>
							<li><a href="/diplom/tasks">Задачи</a></li>
						</ul>
				</div>
				<div class="search-panel">
					<form>
						<input type="text" name="search" <?php if(!empty($request)) echo "value='".$request."'"; ?> placeholder="Поиск...">
						<div id="prefilter">
							<div class="prefilter-item"><input type="radio" <?php if($search_type=="users" || empty($search_type)) echo "checked='checked'"; ?> name="search_type" value="users">по пользователям</div>
							<div class="prefilter-item"><input type="radio" <?php if($search_type=="projects") echo "checked='checked'"; ?> name="search_type" value="projects">по проектам</div>
						</div>
					</form>
				</div>
				<div class="user-panel">
					<div class="icons icon-notification">
						<?php if($countNotifications>0){ ?>
							<div class="count"><?php echo $countNotifications; ?></div>
						<?php } ?>
						<div class="sub-win">
							<div class="arrow"></div>
							<div id="content-sub-win"></div>
						</div>
					</div>
					<div class="online icons icon-user">
						<div class="sub-win">
							<div class="arrow"></div>
							<span><a href="/diplom/user/contacts">Контакты</a></span>
							<span><a href="/diplom/user">Мой профиль</a></span>
							<span><a href="/diplom/user/edit">Изменить профиль</a></span>
							<span><a href="/diplom/user/settings">Настройки</a></span>
							<span id="btn-exit">Выход</span>
						</div>
					</div>
					<span class="login-name"><?php echo $login; ?></span>
				</div>
			</div>
		</header>
		<div id="content-page">

	<?php
	}
?>
