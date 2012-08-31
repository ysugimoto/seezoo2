<?php $controller->set_up();?>
<div class="sz_video_block" id="block_id_<?php echo $controller->block_id;?>" style="width:<?php echo $controller->display_width;?>px;height:<?php echo $controller->display_height;?>px;">
<?php if ($controller->is_edit_mode()):?>
<div class="sz_video_block_mask invisible" style="width:100%;height:100%;background:#ccc;">
	<p>編集中は非表示のコンテンツ：ビデオプレイヤー<br />
	ロードするビデオ：<?php echo $controller->file->file_name . '.' . $controller->file->extension;?>
	</p>
</div>
<?php else:?>
<?php echo $controller->set_video();?>
<?php endif;?>
</div>