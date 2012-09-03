<?php echo $this->loadView('dashboard/dashboard_header');?>
<!--  dashboard contents -->
<!-- h2 stays for breadcrumbs -->
<h2>ページ管理</h2>
<div id="main">
  <h3>ページ管理</h3>
  <div class="clearfix" id="page_wrapper">
    <div id="sitemap">
      <?php echo $this->loadView('dashboard/sitemap/parts/page_structure');?>
    </div>
    <div id="sitemap_search_result" style="display: none">
      <div id="sz_sitemap_search_result_box"></div>
      <p>
        <a href="javascript:void(0)" id="toggle_box">
          &laquo;ツリー表示へ戻る
        </a>
      </p>
    </div>
    <div id="sitemap_menu">
      <form id="sz_sitemap_page_search_dashboard" action="" method="get">
        <p>
          <label>
            ページタイトル:<br />
            <?php echo $Helper->form->text(array('name' => 'page_title', 'value' => ''));?>
          </label>
        </p>
        <p>
          <label>
            ページパス:<br />
            <?php echo $Helper->form->text(array('name' => 'page_path', 'value' => ''));?>
          </label>
        </p>
        <p>
          <input type="hidden" name="from_dh" value="1" />
          <input type="button" id="sz_sitemap_search_do" value="検索" />
        </p>
      </form>
    </div>
  </div>
</div>
<!-- // #main -->
<?php echo $this->loadView('dashboard/dashboard_footer');?>
