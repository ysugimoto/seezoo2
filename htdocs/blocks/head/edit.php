<p>見出しを挿入します。見出しのレベルと内容を挿入して下さい。</p>
<dl>
	<dt>見出しレベル：</dt>
	<dd><?php echo form_dropdown('head_level', $controller->get_head_list_display(), $controller->head_level);?></dd>
	<dt>class属性：（テンプレートと連動させている場合は入力して下さい）</dt>
	<dd><?php echo form_input(array('name' => 'class_name', 'value' => ($controller->class_name) ? $controller->class_name : '', 'class' => 'imedis middle-text'));?></dd>
	<dt>リンク先：</dt>
	<dd><?php echo select_page('link_page_id', $controller->link_page_id);?></dd>
	<dt>内容：</dt>
	<dd>
		<label><input type="checkbox" name="content_type" id="content_type" value="1"<?php echo ($controller->content_type > 0) ? ' checked="checked"' : ''?> />&nbsp;画像を使用する</label>
		<p style="margin:0;padding:0;<?php echo ( $controller->content_type > 0 ) ? 'display:none;' : '';?>">テキスト：（HTMLが有効です）<br /><?php echo form_input(array('name' => 'text', 'value' => $controller->text, 'class' => 'long-text'));?></p>
		<div<?php echo ( $controller->content_type < 1 ) ? ' style="display:none"' : '';?>>
			<?php echo select_file('content_file_id', (int)$controller->content_file_id, 'gif|jpg|jpeg|png');?>
			alt属性：<br />
			<?php echo form_input(array('name' => 'alt_text', 'value' => ( ! empty($controller->alt_text) ) ? $controller->alt_text : '', 'class' => 'long-text'));?>
		</div>
	</dd>
</dl>
