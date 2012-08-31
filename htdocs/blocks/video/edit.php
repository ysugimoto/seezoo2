<div class="sz_block_separator">
<p>動画ファイルを指定してください。</p>
<dl>
	<dt>動画ファイル：</dt>
	<dd><?php echo select_file('file_id', $controller->file_id, 'flv|mp4');?></dd>
	<dt>横幅：</dt>
	<dd><?php echo form_input(array('name' => 'display_width', 'value' => $controller->display_width, 'class' => 'imedis'));?></dd>
	<dt>縦幅：</dt>
	<dd><?php echo form_input(array('name' => 'display_height', 'value' => $controller->display_height, 'class' => 'imedis'));?></dd>
</dl>
</div>