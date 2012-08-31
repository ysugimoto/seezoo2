<?php if ($controller->is_edit_mode()):?>
<div class="sz_as_handle_block sz_edit_handles" style="background:#fff;color:#333;">分割エリアハンドル</div>
</div></div>
<?php endif;?>
<?php $tabs = $controller->get_areas();?>
<div class="sz_area_splitter_wrapper">
<?php foreach ($tabs as $key => $value):?>
<div class="sz_as_contents" style="width:<?php echo $value->contents_per;?>%;">
	<?php echo $this->load->area(prep_str($value->contents_name));?>
</div>
<?php endforeach;?>
</div>
