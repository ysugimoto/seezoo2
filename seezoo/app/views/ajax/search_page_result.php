<?php if ( count($pages) > 0 ):?>
<?php foreach ( $pages as $page ):?>
<div class="sz_section clearfix" pid="<?php echo $page->page_id;?>">
  <img src="<?php echo file_link()?>images/dashboard/file.png" />
  <span pid="<?php echo $page->page_id;?>"><?php echo prep_str($page->page_title);?></span>
  <em><?php echo prep_str($page->page_path);?></em>
</div>
<?php endforeach;?>
<?php else:?>
<p>ヒットしませんでした。</p>
<?php endif;?>
