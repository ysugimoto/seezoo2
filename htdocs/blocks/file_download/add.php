<p>	ファイルをダウンロードさせるブロックを設置します。</p>
<dl>
<dt>ダウンロードファイル：</dt>
<dd><?php echo select_file('file_id');?></dd>
<dt>ダウンロードテキスト:</dt>
<dd><?php echo form_input(array('name' => 'dl_text', 'class' => 'long-text', 'value' => ''));?></dd>
</dl>