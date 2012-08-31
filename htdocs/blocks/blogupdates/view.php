<?php $data = $controller->get_blog_data();?>
<ul class="sz_blog_updates">
<?php foreach ( $data as $key => $blog ):?>
  <li<?php echo ($key === 0) ? ' class="noline"' : ''?>>
    <?php if ($controller->is_blog_enabled()):?>
    <a href="<?php echo article_link($blog);?>">
      <span class="entrydate"><?php echo date('Y-m-d', strtotime($blog->entry_date));?></span>
      <?php echo prep_str($blog->title);?>
    </a>
    <?php else:?>
    <span class="nolink">
      <span class="entrydate"><?php echo date('Y-m-d', strtotime($blog->entry_date));?></span>
      <?php echo prep_str($blog->title);?>
    </span>
    <?php endif;?>
  </li>
<?php endforeach;?>
<?php if ( count($data) === 0 ):?>
<li><span class="nolink">更新情報はありません。</span>
<?php endif;?>
</ul>