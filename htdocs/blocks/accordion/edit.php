<p>見出し+開閉可能な概要ブロックを挿入します。概要のエリア名はページ内で重複しないようにしてください。</p>
<p><span style="color:#c00">※エリア名は同一ページ内で重複しないようにしてください</span></p>
<dl>
	<dt>見出しレベル：</dt>
	<dd><?php echo form_dropdown('head_level', $controller->get_head_list_display());?></dd>
	<dt>テキスト：（HTMLが有効です）</dt>
	<dd><?php echo form_input(array('name' => 'head_text', 'value' => $controller->head_text, 'class' => 'long-text'));?></dd>
	<dt>概要エリア名：</dt>
	<dd><?php echo form_input(array('name' => 'description_area_name', 'value' => $controller->description_area_name, 'class' => 'long-text'));?></dd>
	<dd><label><?php echo form_checkbox('open_animate', 1, ($controller->open_animate > 0) ? TRUE : FALSE);?>&nbsp;開閉時にアニメーションを行う</label></dd>
</dl>
