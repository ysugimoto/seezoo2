<?php if ($controller->is_edit_mode()):?>
<div style="text-align : center;	height : 58px;border : solid 1px #ccc;">アコーディオンブロックハンドル</div>
<?php endif;?>
<?php echo $controller->build_head();?>
<div class="sz_accordion_wrapper<?php echo ($controller->open_animate > 0) ? ' open_animate' : '';?><?php echo ($controller->is_edit_mode()) ? ' edit' : '';?>">
	<div class="sz_accordion_body">
		<?php echo $this->load->area(prep_str($controller->description_area_name), TRUE, FALSE);?>
	</div>
</div>
