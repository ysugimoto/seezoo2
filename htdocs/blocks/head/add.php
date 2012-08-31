<p>見出しを挿入します。見出しのレベルと内容を挿入して下さい。</p>
<dl>
	<dt>見出しレベル：</dt>
	<dd><?php echo form_dropdown('head_level', $controller->get_head_list_display());?></dd>
	<dt>class属性：（テンプレートと連動させている場合は入力して下さい）</dt>
	<dd><?php echo form_input(array('name' => 'class_name', 'value' => '', 'class' => 'imedis middle-text'));?></dd>
	<dt>リンク先：</dt>
	<dd><?php echo select_page('link_page_id', 0);?></dd>
	<dt>内容：</dt>
	<dd>
		<label><input type="checkbox" name="content_type" id="content_type" value="1" />&nbsp;画像を使用する</label>
		<p style="margin:0;padding:0">テキスト：（HTMLが有効です）<br /><?php echo form_input(array('name' => 'text', 'value' => '', 'class' => 'long-text'));?></p>
		<div style="display:none">
			<?php echo select_file('content_file_id', 0, 'gif|jpg|jpeg|png');?>
			alt属性：<br />
			<?php echo form_input(array('name' => 'alt_text', 'value' => '', 'class' => 'long-text'));?>
		</div>
	</dd>
</dl>
