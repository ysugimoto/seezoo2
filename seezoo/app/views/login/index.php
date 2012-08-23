<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript" src="<?php echo base_link();?>flint.php?mode=view" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_link();?>js/flint.dev2.js" charset="UTF-8"></script>
<script type="text/javascript" src="<?php echo base_link();?>js/login.js" charset="UTF-8"></script>

<link rel="stylesheet" type="text/css" href="<?php echo base_link();?>css/login.css" />
<title>ログイン</title>
</head>
<body>
<?php if (isset($msg)):?>
	<p class="login_error"><?php echo $msg;?></p>
<?php endif;?>
<div id="login_box">
	<div class="login_radius_header">&nbsp;</div>
	<div class="login_content">
	<p class="login_caption">Seezooにログイン</p>
	<?php echo $Helper->form->open(SEEZOO_SYSTEM_LOGIN_URI . '/do_login');?>
	<table cellspacing="5">
		<tbody>
			<tr>
				<th>ID</th>
				<td>
					<div class="ipt"><?php echo $Helper->form->text(array('name' => 'user_name', 'id' => 'uid', 'value'=> $Validation->value('user_name')));?></div>
					<?php echo $Validation->error('user_name');?>
				</td>
			</tr>
			<tr>
				<th>パスワード</th>
				<td>
					<div class="ipt"><?php echo $Helper->form->password(array('name' => 'password', 'id' => 'password'));?></div>
					<?php echo $Validation->error('password');?>
				</td>
			</tr>
			<tr>
				<th>&nbsp;</th>
				<td><label><input type="checkbox" name="remember" value="1" />&nbsp;次回から自動的にログイン</label></td>
			</tr>
		</tbody>
	</table>
	<p id="login_btn">
		<?php echo $Helper->form->hidden($tokenName, $token);?>
		<input type="image" src="<?php echo base_link();?>images/login/login_btn.gif" alt="login" id="btn" />
	</p>
	<?php echo $Helper->form->close();?>
	<p><a href="javascript:void(0)" id="forgotten">パスワードを忘れた時はこちら</a></p>
	<div id="forgotten_block"<?php if(isset($open) && $open === TRUE) echo ' style="display:block"';?>>
		<?php echo $Helper->form->open(SEEZOO_SYSTEM_LOGIN_URI . '/forgotten_password');?>
		<p>登録時のメールアドレスを入力して下さい。<br />新規パスワードを再発行してメールを送信します。</p>
		<p>
			<label>
				<?php echo $Validation->error('forgotten_mail');?>
				<?php echo $Helper->form->text(array('name' => 'forgotten_mail', 'value' => '', 'class' => 'forgotten_email'));?>
			</label>
		</p>
		<?php echo $Helper->form->hidden($tokenName, $token);?>
		<p class="sbt"><?php echo $Helper->form->submit(array('value' => 'パスワードを再発行する'));?></p>
		<?php echo $Helper->form->close();?>
	</div>
	</div>
	<div class="login_radius_footer">&nbsp;</div>
</div>
</body></html>
