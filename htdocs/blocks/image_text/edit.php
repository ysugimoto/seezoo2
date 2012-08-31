<div class="sz_block_separator">
<p>テキストと画像を回り込みで配置します。</p>
<dl>
	<dt><label for="fid">画像</label></dt>
	<dd>
			<?php echo select_file('file_id', $controller->file_id);?>
	</dd>
	<dt><label for="text">テキスト</label></dt>
	<dd><?php echo form_textarea(array('name' => 'text', 'id' => 'text', 'cols' => 8, 'rows' => 8, 'value' => $controller->text, 'style' => 'width:100%;height:100px'));?></dd>
	<dt><label for="float_mode">配置モード</label></dt>
	<dd>
		<?php echo form_dropdown('float_mode', $controller->get_float_mode_list(), $controller->float_mode, 'id="float_mode"');?>
	</dd>
</dl>
</div>
