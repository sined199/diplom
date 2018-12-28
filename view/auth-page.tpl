<img src="/diplom/view/images/pages/bgauth.jpg" class="authbg">
<div class="container">
	<div class="col-xs-12">
		<h1 class="pagetitle">rost-d up</h1>
	</div>
	<div class="col-xs-12">
		<div class="auth-block">
			<div class="tabs">
				<div class="tab-item" id="login-tab-item">Вход</div><div class="tab-item notview" id="reg-tab-item">Регистрация</div>
			</div>
			<section id="login" class="show">
				<form name="form-login">
					<input type="text" name="login" placeholder="Логин..">
					<input type="password" name="password" placeholder="Пароль..">
					<input type="button" name="btn-login" value="Войти">
					<span class="reset-pass" onclick="location.href='/diplom/auth/resetpass'">Восстановить пароль</span>
				</form>
			</section>
			<section id="registration" class="hide">
				<form name="form-registration">
					<input type="text" name="login" placeholder="Логин..">
					<input type="email" name="email" placeholder="Адрес эл. почты..">
					<input type="password" name="password" placeholder="Пароль..">
					<input type="hidden" name="refurl" value="<?php echo $refurl; ?>">
					<input type="button" name="btn-registration" value="Зарегистрироваться">
				</form>
			</section>
		</div>
	</div>
</div>