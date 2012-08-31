<div class="sz_form_separator">
<ul class="sz_tabs clearfix">
	<li><a href="#tab_image_content1"  class="active">画像設定</a></li>
	<li><a href="#tab_image_content2">パラメータ設定</a></li>
</ul>
<div id="tab_image_content1" class="tab_content">
<p>切り替える画像を選択してください。</p>
<dl>
	<dt>画像の追加：</dt>
	<dd><?php echo select_file_multiple('file_ids', explode(':', $controller->file_ids), explode(':', $controller->page_ids));?></dd>
</dl>
</div>
<div id="tab_image_content2" class="tab_content" style="display:none">
<p>スライドショーのパラメータを設定します。</p>
<dl>
	<dt>再生順指定：</dt>
	<dd><?php echo form_dropdown('play_type', $controller->get_play_types(), $controller->play_type, 'id="sz_slide_play_type"');?></dd>
	<dt>切り替えエフェクト：</dt>
	<dd><?php echo form_dropdown('slide_type', $controller->get_slide_types(), $controller->slide_type)?></dd>
	<dt>切り替え時間：</dt>
	<dd><?php echo form_input(array('name' => 'delay_time', 'value' => $controller->delay_time / 1000, 'class' => 'tiny-text imedis'))?>(秒)</dd>
	<dt>キャプションの表示：</dt>
	<dd><label><?php echo form_checkbox('is_caption', 1, $controller->is_caption);?>&nbsp;表示する</label></dd>
</dl>
</div>
</div>