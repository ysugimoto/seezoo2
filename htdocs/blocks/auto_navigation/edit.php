<div class="sz_block_separator">
<ul class="sz_tabs clearfix autonav">
	<li class="active"><a href="#tab_content_1">設定</a></li>
	<li><a href="#tab_content_2" class="preview">プレビュー</a></li>
</ul>
<div id="tab_content_1" class="tab_content">
	<dl>
		<dt><label for="sort_order">表示順</label></dt>
		<dd><?php echo form_dropdown('sort_order', $controller->get_sort_order_list(), $controller->sort_order, 'id="sort_order"');?></dd>
		<dt><label for="master_page_id">基点ページ(デフォルトトップページ)</label></dt>
		<dd>
			<?php echo select_page('based_page_id', (int)$controller->based_page_id);?>
			<label><?php echo form_checkbox('show_base_page', 1, ((int)$controller->show_base_page === 1) ? TRUE : FALSE, 'id="show_base_page"');?>&nbsp;基点ページをナビゲーションに含める</label>
		</dd>
		<dt><label for="subpage_level">子ページ表示階層</label></dt>
		<dd><?php echo form_dropdown('subpage_level', $controller->get_display_page_levels(), $controller->subpage_level, 'id="subpage_level"');?>階層まで表示</dd>
<!-- 		<dt><label for="manual_selected_pages">マニュアル指定</label></dt>-->
<!-- 		<dd><?php echo form_input(array('name' => 'manual_selected_page', 'id' => 'manual_selected_page', 'readonly' => 'readonly'));?></dd>-->
		<dt><label for="handle_class">class属性</label></dt>
		<dd><?php echo form_input(array('name' => 'handle_class', 'id' => 'handle_class', 'value' => $controller->handle_class, 'style' => 'ime-mode:disabled'));?></dd>
		<dt><label for="display_mode">表示方法</label></dt>
		<dd>
			<?php echo form_dropdown('display_mode', $controller->get_display_mode(), $controller->display_mode, 'id="display_mode"')?><br />
			<span id="display_mode_caption"><?php echo $controller->set_display_mode_text();?></span>
		</dd>
		<dt><label for="current_class">現在いるページに付与するclass名</label></dt>
		<dd><?php echo form_input(array('name' => 'current_class', 'id' => 'current_class', 'value' => $controller->current_class, 'style' => 'ime-mode:disabled'));?></dd>
	</dl>
</div>
<div id="tab_content_2" class="tab_content" style="display : none">
	<div id="sz_autonav_preview" class="loading">
	</div>
</div>
</div>