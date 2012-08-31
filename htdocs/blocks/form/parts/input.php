<div class="sz_form_view<?php echo ( !empty($controller->form_class_name) ) ? ' ' . prep_str($controller->form_class_name) : '';?>">
	<?php if ($controller->ticket_miss === FALSE):?>
	<?php echo form_open($controller->get_self_path());?>
	<table class="sz_form_table">
		<tbody>
			<?php foreach ($controller->forms as $value):?>
			<tr>
				<th><?php echo $value->question_name;?><?php if ($controller->is_required($value) === TRUE) { echo '&nbsp;<span style="color:#c00">※</span>';}?></th>
				<td>
					<?php echo $controller->build_form_parts($value);?>
					<?php echo $controller->validation_error($value->question_id);?>
				</td>
			</tr>
			<?php endforeach;?>
			<?php if ($controller->use_captcha > 0):?>
			<tr>
				<th>画像認証</th>
				<td>
					<?php echo $controller->generate_captcha();?><br />
					<input type="text" name="captcha_value" value="" />
					<?php echo $controller->validation_error('captcha_value');?>
				</td>
			</tr>
			<?php endif;?>
		</tbody>
	</table>
	<div class="sz_form_submit">
		<input type="hidden" name="<?php echo $controller->ticket_name;?>" value="<?php echo $controller->generate_token();?>" />
		<input type="hidden" name="action<?php echo $controller->block_id;?>" value="confirm" />
		<input type="submit" value="確認画面へ" />
	</div>
	<?php echo form_close();?>
	<?php else:?>
	<p>クッキーを有効にしてください。また、リロードは禁止しています。</p>
	<?php endif;?>
</div>
