<?php $controller->setup_effect_javascript();?>

<?php if ((int)$controller->play_type !== 3):?>
<div class="sz_slideshow_block" style="height:<?php echo $controller->calculate_max_height();?>px;" id="sz_slideshow_block_id_<?php echo $controller->orig_block_id;?>">
<?php echo $controller->set_slide_type_prefix();?>

<?php foreach ($controller->set_slide_file_list() as $key => $value):?>
<?php echo $controller->set_image($value, $key);?>
<?php endforeach;?>

<?php echo $controller->set_slide_type_suffix();?>
</div>
<?php echo $controller->generate_caption();?>

<?php else:?>
<div id="sz_slide_garalley_<?php echo $controller->orig_block_id;?>" style="height:<?php echo $controller->calculate_max_height();?>px;">
	<img src="<?php echo $controller->set_first_image();?>" alt="" />
</div>
<ul class="sz_garalley">

<?php foreach ($controller->set_slide_file_list() as $key => $value):?>
<?php echo $controller->set_image($value, $key);?>
<?php endforeach;?>

</ul>
<?php endif;?>