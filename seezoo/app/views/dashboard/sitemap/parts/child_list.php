<?php if ( count($childs) > 0 ):?>
<ul>
  <?php foreach ( $childs as $page ):?>
  <li id="page_<?php echo $page->page_id;?>" class="sz_soratble<?php echo ( $page->childs ) ? ' ch close': '';?>">
    <div class="sz_sitemap_page movable<?php if ( (int)$page->alias_to > 0 ) { echo ' alias';} elseif ( ! empty($page->external_link) ) { echo ' external'; } ?>" pid="<?php echo $page->page_id;?>" parent="<?php echo $page->parent;?>" d_o="<?php echo $page->display_order;?>" sys="<?php echo $page->is_system_page;?>">
    <?php if ( $page->childs):?>
      <img src="<?php echo file_link();?>images/dashboard/folder.png" class="sort_page" />
    <?php elseif ( (int)$page->alias_to > 0 ):?>
      <?php echo set_image('dashboard/alias.png');?>
    <?php elseif ( ! empty($page->external_link) ):?>
      <?php echo set_image('dashboard/external.png');?>
    <?php elseif ( (int)$page->is_system_page > 0 ):?>
      <img src="<?php echo file_link();?>images/dashboard/system.png" class="sort_page" />
    <?php else:?>
      <img src="<?php echo file_link();?>images/dashboard/file.png" class="sort_page" />
    <?php endif;?>
      <span pid="<?php echo $page->page_id;?>" class="ttl" d_o="<?php echo $page->display_order;?>" ssl="<?php echo $page->is_ssl_page;?>">
        <?php echo prep_str($page->page_title);?><span><?php if ( (int)$page->childs > 0 ) echo '&nbsp;(' . $page->childs . ')';?></span>
      </span>
    </div>
    <?php if ( $page->childs ):?>
    <a href="javascript:void(0)" class="close_dir oc">&nbsp;</a>
    <?php endif;?>
  </li>
  <?php endforeach;?>
</ul>
<?php endif;?>