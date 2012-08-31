<div class="sz_form_separator">
<ul class="sz_tabs clearfix">
	<li><a href="#tab_image_content1"  class="active">画像設定</a></li>
	<li><a href="#tab_image_content2">パラメータ設定</a></li>
</ul>
<div id="tab_image_content1" class="tab_content">
<p>切り替える画像を選択してください。</p>
<dl>
	<dt>画像の追加：</dt>
	<dd><?php echo select_file_multiple('file_ids');?></dd>
</dl>
</div>
<div id="tab_image_content2" class="tab_content" style="display:none">
<p>スライドショーのパラメータを設定します。</p>
<dl>
	<dt>再生順指定：</dt>
	<dd>
		<?php echo form_dropdown('play_type', $controller->get_play_types(), 0, 'id="sz_slide_play_type"');?>
	</dd>
	<dt>切り替えエフェクト：</dt>
	<dd><?php echo form_dropdown('slide_type', $controller->get_slide_types())?></dd>
	<dt>切り替え時間：</dt>
	<dd><?php echo form_input(array('name' => 'delay_time', 'value' => '3', 'class' => 'tiny-text imedis'))?>(秒)</dd>
</dl>
</div>
</div>