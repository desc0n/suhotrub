<div class="row">
	<form class="form-horizontal row" style="display:inline-block;margin-left:40px;" method="post">
		<div class="row">
			<h3>Редактируем контакты</h3>
		</div>
		<div class="form-group span12">
			<label for="redact_phone">Телефон</label>
			<input type="text" id="redact_phone" name="phone" class="form-control" value="<?=Arr::get($contacts, 'phone', '');?>">
		</div>
		<div class="form-group span12">
			<label for="redact_email">Почта</label>
			<input type="text" id="redact_email" name="email" class="form-control" value="<?=Arr::get($contacts, 'email', '');?>">
		</div>
		<div class="form-group span12">
			<label for="redact_address">Адрес</label>
			<input type="text" id="redact_address" name="address" class="form-control" value="<?=Arr::get($contacts, 'address', '');?>">
		</div>
		<div class="form-group span12">
			<label for="redact_about">О компании</label>
			<textarea id="redact_about" name="about" class="ckeditor"><?=Arr::get($contacts, 'about', '');?></textarea>
		</div>
		<div class="row">
			<button type="submit" class="btn btn-primary" name="redactcontacts" value="1">Сохранить</button>
		</div>
	</form>
</div>

