<div class="sz_block_separator">
<p>twitterデータを取得して表示します。</p>
<dl>
	<dt><label for="view_type">表示形式</label></dt>
	<dd><?php echo form_dropdown('view_type', $controller->get_view_type_array(),'', 'id="view_type"');?></dd>
	<dt><label for="user_name">ユーザー名</label></dt>
	<dd><?php echo form_input(array('name' => 'user_name', 'id' => 'user_name', 'class' => 'ime_dis', 'value' => ''));?></dd>
	<dt><label for="view_limit">表示件数</label></dt>
	<dd><?php echo form_input(array('name' => 'view_limit', 'id' => 'ivew_limit', 'size' => 2, 'value' => '10'));?></dd>
</dl>
</div>