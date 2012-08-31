<div class="sz_form_separator">
<ul class="sz_tabs clearfix">
	<li><a href="#tab_image_content1"  class="active">基本設定</a></li>
	<li><a href="#tab_image_content2">アクション</a></li>
</ul>
<div id="tab_image_content1" class="tab_content">
<p>設置したい画像を選択してください。</p>
<dl>
	<dt>画像ファイル：</dt>
	<dd><?php echo select_file('file_id');?></dd>
	<dt>マウスオーバーで表示する画像：</dt>
	<dd><?php echo select_file('hover_file_id');?></dd>
	<dt>alt属性：</dt>
	<dd><?php echo form_input(array('name' => 'alt', 'value' => '', 'class' => 'long-text'))?></dd>
	<dt>リンク先URL：</dt>
	<dd>
		<label><input type="checkbox" name="link_type" id="toggle_link" value="1" />&nbsp;外部リンクを設定する</label>	
		<div><?php echo select_page('link_to_page_id');?></div>
		<p style="display:none;margin:0;padding:0;"><?php echo form_input(array('name' => 'link_to', 'style' => 'ime-mode:disabled', 'class' => 'long-text'))?></p>
	</dd>
</dl>
</div>
<div id="tab_image_content2" class="tab_content" style="display:none">
<p>画像に対してアクションを設定できます。</p>
<dl>
	<dt>アクション：</dt>
	<dd><?php echo form_dropdown('action_method', $controller->get_action_methods());?></dd>
	<dt>アクション後の画像：</dt>
	<dd><?php echo select_file('action_file_id');?></dd>
</dl>
</div>
</div>