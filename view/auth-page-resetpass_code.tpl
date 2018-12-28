<img src="/diplom/view/images/pages/bgauth.jpg" class="authbg">
<div class="container">
	<div class="col-xs-12">
		<h1 class="pagetitle">rost-d up</h1>
	</div>
	<div class="col-xs-12">
		<div class="auth-block">
			<div class="tabs">
				<div class="tab-item" id="login-tab-item">Восстановление пароля</div>
			</div>
			<section id="login" class="show">
				<form name="form-login">
					<?php if($result){ ?>
						<input type="hidden" name="code" value="<?php echo $code; ?>">
						<input type="password" name="password" placeholder="Ваш новый пароль">
						<input type="button" name="btn-resetpass_new_pass" value="Восстановить">
					<?php } else { ?>
						<span>Код не действителен</span>
					<?php } ?>
				</form>
			</section>
		</div>
	</div>
</div>