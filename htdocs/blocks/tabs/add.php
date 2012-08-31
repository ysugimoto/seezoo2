<div class="sz_tab_separator">
<p>
	タブ切り替えが行えるコンテンツを生成します。<br />
	それぞれのコンテンツエリア名は重複しないような名前をつけてください。
</p>
<div class="tab_input_area">
<h4>コンテンツ名</h4>
<div id="tab_contents_frame">
<p>
	<label><?php echo form_input(array('name' => 'tab_contents_name[]', 'class' => 'txt-long'));?></label>
	<a href="javascript:void(0)" class="tab_delete"><?php echo set_image('delete.png', TRUE)?>&nbsp;削除</a>
</p>
</div>
<p class="add_tab_wrapper"><a href="javascript:void(0)" id="add_tab">コンテンツを追加する</a></p>
</div>
<hr class="sep" />
<br />
<p><label><?php echo form_checkbox('single_contents', 1, TRUE);?>&nbsp;コンテンツが1つの場合はタブ表示をしない</label></p>
<p><label><?php echo form_checkbox('link_inner', 1, FALSE);?>&nbsp;コンテンツの中でタブの切り替えリンクを表示する</label></p>
</div>