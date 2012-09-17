<?php echo $this->loadView('dashboard/dashboard_header');?>
<div class="col_9 widget" id="page_wrapper">
  <h3>サイトマップ</h3>
  <div id="sitemap">
    <?php echo $this->loadView('dashboard/sitemap/parts/page_structure');?>
  </div>
  <div id="sitemap_search_result" style="display: none">
    <div id="sz_sitemap_search_result_box"></div>
    <p>
      <a href="javascript:void(0)" id="toggle_box">&laquo;ツリー表示へ戻る</a>
    </p>
  </div>
</div>
<div id="sitemap_menu" class="col_3 widget">
  <h3>ページ検索</h3>
  <form id="sz_sitemap_page_search_dashboard" action="" method="get">
    <p>
      <label>
        ページタイトル:<br />
        <?php echo $Helper->form->text(array('name' => 'page_title', 'value' => '', 'class' => 'col_12'));?>
      </label>
    </p>
    <p>
      <label>
        ページパス:<br />
        <?php echo $Helper->form->text(array('name' => 'page_path', 'value' => '', 'class' => 'col_12'));?>
      </label>
    </p>
    <hr />
    <p>
      <input type="hidden" name="from_dh" value="1" />
      <button class="medium" id="sz_sitemap_search_do"><span data-icon="s" class="icon medium"></span>検索</button>
    </p>
  </form>
</div>
<?php echo $this->loadView('dashboard/dashboard_footer');?>
