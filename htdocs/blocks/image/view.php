<div class="sz_file_block">
<?php if (!empty($controller->action_file_id)
				&& !empty($controller->action_method)):?>
				
<a href="<?php echo $controller->set_action_file_path();?>" class="<?php echo $controller->action_method;?>" title="<?php echo (!empty($controller->alt)) ? $controller->alt : '';?>">
	<?php echo $controller->set_image_tag();?>
</a>
<?php $controller->load_javascript();?>

<?php elseif ($controller->get_link_path() !== FALSE):?>
<a href="<?php echo prep_str($controller->link_path);?>">
	<?php echo $controller->set_image_tag();?>
</a>
<?php else:?>
<?php echo $controller->set_image_tag();?>
<?php endif;?>

</div>