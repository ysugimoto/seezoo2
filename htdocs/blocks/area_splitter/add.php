<div class="sz_as_separator">
<p>
	編集エリアを分割します。<br />
	設置するコンテンツエリア名は重複しないような名前をつけてください。
</p>
<ul class="sz_tabs clearfix">
	<li><a href="#tab_asp_1" class="active">一般設定</a></li>
	<li><a href="#tab_asp_2">エリア分割比率設定</a></li>
</ul>
<div id="tab_asp_1" class="tab_content sz_asp_tc">
<div class="as_input_area">
<h4>コンテンツ名</h4>
<div id="as_contents_frame">
<p><label><?php echo form_input(array('name' => 'as_contents_name[]', 'class' => 'txt-long'));?></label><a href="javascript:void(0)" class="as_delete"><?php echo set_image('delete.png', TRUE)?>&nbsp;削除</a></p>
<p><label><?php echo form_input(array('name' => 'as_contents_name[]', 'class' => 'txt-long'));?></label><a href="javascript:void(0)" class="as_delete"><?php echo set_image('delete.png', TRUE)?>&nbsp;削除</a></p>
</div>
<p class="add_as_wrapper"><a href="javascript:void(0)" id="add_as">コンテンツを追加する</a></p>
</div>
</div>
<div id="tab_asp_2" class="tab_content sz_asp_tc" style="display:none">

<div id="sz_asp_previews_wrapper" class="clearfix">
<div class="sz_asp_previews">
<p>
<em>50</em>%
<?php echo form_hidden('as_contents_pers[]', 50);?>
</p>
<span>&nbsp;</span>
</div>
<div class="sz_asp_previews asp_last">
<p>
<em>50</em>%
<?php echo form_hidden('as_contents_pers[]', 50);?>
</p>
<span>&nbsp;</span>
</div>
</div>
<p id="reset_division">
<a href="javascript:void(0)">
<?php echo set_image('alias.png', TRUE);?>&nbsp;等間隔にリセット
</a>
</p>
</div>
</div>