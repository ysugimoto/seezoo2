<p>見出し+開閉可能な概要エリアを挿入します。</p>
<p><span style="color:#c00">※エリア名は同一ページ内で重複しないようにしてください</span></p>
<dl>
	<dt>見出しレベル：</dt>
	<dd><?php echo form_dropdown('head_level', $controller->get_head_list_display());?></dd>
	<dt>テキスト：（HTMLが有効です）</dt>
	<dd><?php echo form_input(array('name' => 'head_text', 'value' => '', 'class' => 'long-text'));?></dd>
	<dt>概要エリア名：</dt>
	<dd><?php echo form_input(array('name' => 'description_area_name', 'value' => '', 'class' => 'long-text'));?></dd>
	<dd><label><?php echo form_checkbox('open_animate', 1, TRUE);?>&nbsp;開閉時にアニメーションを行う</label></dd>
</dl>
