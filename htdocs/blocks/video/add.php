<div class="sz_block_separator">
<p>動画ファイルを指定してください。</p>
<dl>
	<dt>動画ファイル：</dt>
	<dd><?php echo select_file('file_id', 0, 'flv|mp4');?></dd>
	<dt>横幅：</dt>
	<dd><?php echo form_input(array('name' => 'display_width', 'value' => 600, 'class' => 'imedis'));?></dd>
	<dt>縦幅：</dt>
	<dd><?php echo form_input(array('name' => 'display_height', 'value' => 400, 'class' => 'imedis'));?></dd>
</dl>
</div>