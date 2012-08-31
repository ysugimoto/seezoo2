<?php $controller->set_up();?>
<div class="sz_video_block" id="block_id_<?php echo $controller->block_id;?>">
<?php if ($controller->is_edit_mode()):?>
<div class="sz_video_block_mask invisible" style="width:100%;height:100%;background:#ccc;">
	<p>編集中は非表示のコンテンツ：ビデオプレイヤー<br />
	ロードするビデオ：<?php echo $controller->file->file_name . '.' . $controller->file->extension;?>
	</p>
</div>
<?php else:?>
<video width="<?php echo $controller->display_width;?>" height="<?php echo $controller->display_height;?>" controls="controls">
<source src="<?php echo make_file_path($controller->file, '', TRUE)?>" type="video/<?php echo $controller->file->extension?>" />
<p>お使いのブラウザではサポートされていません。<?php echo anchor('action/download_file/' . $controller->file->file_id, 'こちら');?>からファイルをダウンロードできます。</p>
</video>
<?php endif;?>
</div>