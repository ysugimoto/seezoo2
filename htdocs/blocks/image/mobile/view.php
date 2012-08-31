<?php 
$ut   = new SeezooUtility();
$path = $ut->convert_image(get_file($controller->file_id, TRUE), 'jpg', 300, 200, TRUE, 50, TRUE);
?>
<img src="<?php echo $path;?>" alt="<?php echo ((!empty($controller->alt)) ? prep_str($controller->alt) : '');?>" />