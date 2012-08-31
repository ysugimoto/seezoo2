<?php 
$file = get_file($controller->file_id);
$ut   = new SeezooUtility();
$text = auto_link(prep_str($controller->text));
?>
<table cellspacing="0" cellpadding="0" width="100%">
<tr>
<?php if ( $controller->float_mode === 'left' ):?>
<td width="50%"><img src="<?php echo $ut->convert_image(make_file_path($file), 'jpg', 120, 120, TRUE, 50, TRUE);?>" alt="<?php echo prep_str($file->file_name . '.' . $file->extension);?>" /></td>
<td width="50%"><?php echo nl2br($text);?></td>
<?php else:?>
<td width="50%"><?php echo nl2br($text);?></td>
<td width="50%"><img src="<?php echo $ut->convert_image(make_file_path($file), 'jpg', 120, 120, TRUE, 50, TRUE);?>" alt="<?php echo prep_str($file->file_name . '.' . $file->extension);?>" /></td>
<?php endif;?>
</table>