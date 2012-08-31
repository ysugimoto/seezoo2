<?php $controller->setup_tab();?>
<?php if ($controller->is_edit_mode()):?>
<div class="sz_tab_handle_block sz_edit_handles" style="background:#fff;color:#333">タブハンドル</div>
</div></div>
<?php endif;?>
<div class="sz_tab_block_area">
	<?php if ( count($controller->tabs) > 1 ):?>
	<ul class="sz_tab_block">
		
		<?php foreach ($controller->tabs as $key => $value):?>
		<li style="width:<?php echo round(100 / count($controller->tabs))?>%">
			<a href="javascript:void(0)"<?php if ($key == 0) echo ' class="active"';?>>
				<?php echo $value;?>
				<span class="sz_tab_radius_left">&nbsp;</span>
				<span class="sz_tab_radius_right">&nbsp;</span>
			</a>
		</li>
		<?php endforeach;?>
		
	</ul>
	<?php endif;?>
	<?php foreach ($controller->tabs as $key => $value):?>
	<div class="sz_tab_block_contents<?php if ($key > 0) echo ' tab_init_hide';?>">
		<?php echo $this->load->area($value);?>
		
		<?php if ( $controller->link_inner > 0 ):?>
		<p class="sz_tab_block_next_prev">
		
			<?php if ( $key > 0 ):?>
			<a href="#" class="tab_block_prev" rel="tab_<?php echo $key;?>">&laquo;&nbsp;前へ</a>
			<?php endif;?>
			
			<?php if ( $key < count($controller->tabs) - 1 ):?>
			<a href="#" class="tab_block_next" rel="tab_<?php echo $key;?>">次へ&nbsp;&raquo;</a>
			<?php endif;?>
			
		</p>
		<?php endif;?>
		
	</div>
	<?php endforeach;?>
</div>
