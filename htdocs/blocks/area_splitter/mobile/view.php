<?php if ($controller->is_edit_mode()):?>
<div class="sz_as_handle_block sz_edit_handles" style="background:#fff;color:#333;">分割エリアハンドル</div>
</div></div>
<?php endif;?>
<?php $tabs = $controller->get_areas();?>
<table border="0" width="100%" cellpadding="0" cellspacing="0">
<tr>
<?php foreach ($tabs as $key => $value):?>
<td width="<?php echo $value->contents_per;?>%" style="overflow:hidden">
<?php echo $this->load->area(prep_str($value->contents_name));?>
</td>
<?php endforeach;?>
</tr>
</table>

</div>
	