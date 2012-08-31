<div class="sz_form_view<?php echo ( !empty($controller->form_class_name) ) ? ' ' . prep_str($controller->form_class_name) : '';?>">
	<p>以下の内容でよろしければ送信ボタンを押してください。</p>
	<table class="sz_form_table">
		<tbody>
			<?php foreach ($controller->forms as $value):?>
			<tr>
				<th><?php echo $value->question_name;?></th>
				<td><?php echo $controller->build_form_parts_confirm($value);?></td>
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<div class="sz_form_submit">
		<?php echo form_open($controller->get_self_path(), array('style' => 'display:inline;'));?>
		<?php foreach ($controller->validation_values() as $key => $value):?>
		<?php echo form_hidden('question_' . $key, $value);?>
		<?php endforeach;?>
		<input type="hidden" name="<?php echo $controller->ticket_name;?>" value="<?php echo $controller->generate_token();?>" />
		<input type="hidden" name="action<?php echo $controller->block_id;?>" value="init" />
		<input type="submit" value="入力画面に戻る" />
		<?php echo form_close();?>&nbsp;&nbsp;&nbsp;&nbsp;
		<?php echo form_open($controller->get_self_path(), array('style' => 'display:inline;'));?>
		<?php foreach ($controller->validation_values() as $key => $value):?>
		<?php echo form_hidden('question_' . $key, $value);?>
		<?php endforeach;?>
		<input type="hidden" name="<?php echo $controller->ticket_name;?>" value="<?php echo $controller->generate_token();?>" />
		<input type="hidden" name="action<?php echo $controller->block_id;?>" value="send" />
		<input type="submit" value="送信する" />
		<?php echo form_close();?>
	</div>
</div>
