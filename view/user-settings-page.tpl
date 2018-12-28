<title><?php echo $pagetitle; ?></title>
<div class="main">

	<div class="main-panel">
		<div class="container">
			<div class="content">
				<div class="col-xs-12 page-title"><h3>Настройки</h3></div>
				<div class="col-xs-12 settings-block">
					<div class="content">
						<form>
							<div class="block">
								<div class="setting-item"><input type="checkbox" name="search_user" <?php if($settings['search_user']){ ?> checked=checked <?php } ?>>Находить данный профиль в поиске</div>
								<div class="setting-item"><input type="checkbox" name="view_statistics" <?php if($settings['view_statistics']){ ?> checked=checked <?php } ?>>Статистика в публичном доступе</div>
								<div class="setting-item"><input type="checkbox" name="send_invite" <?php if($settings['send_invite']){ ?> checked=checked <?php } ?>>Получать приглашения в проекты</div>
								<div class="setting-item"><input type="checkbox" name="hidden_profile" <?php if($settings['hidden_profile']){ ?> checked=checked <?php } ?>>Режим "Невидимки"</div>
								<!--<input type="checkbox" name="send_messages">Писать мне сообщения<br>-->
								<div class="setting-item"><input type="checkbox" name="mail_invite" <?php if($settings['mail_invite']){ ?> checked=checked <?php } ?>>Получать уведомления на почту о приглашени в проект или задачу.</div>
								<div class="setting-item"><input type="checkbox" name="mail_new_ads" <?php if($settings['mail_new_ads']){ ?> checked=checked <?php } ?>>Получать уведомление на почту о новых объявлениях.</div>
							</div>
							
							<input type="button" class="action-btn" name="save_settings" value="Сохранить настройки">					
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>