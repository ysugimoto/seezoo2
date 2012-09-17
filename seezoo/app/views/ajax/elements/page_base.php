<table class="striped">
  <tbody>
    <tr class="alt">
      <td valign="top">
        ページタイトル<br />
        <?php if ( isset($page) && ! empty($page->page_title) ):?>
        <?php echo $Helper->form->text(array('name' => 'page_title', 'value' => $page->page_title, 'id' => 'sz_input_page_title'))?>
        <?php else:?>
        <?php echo $Helper->form->text(array('name' => 'page_title', 'value' => '', 'id' => 'sz_input_page_title'))?>
        <?php endif;?>
      </td>
      <td>
        <?php if ( isset($page) && (int)$page->is_system_page > 0 ):?>
        ページパス(システムページのため変更不可)<br />
        <p class="no_editable" style="margin:0px">
          <?php echo trim(rawurldecode(parse_path_segment(prep_str($page->page_path), TRUE)) . '/' .rawurldecode(parse_path_segment(prep_str($page->page_path))));?>
          <?php echo $Helper->form->hidden('page_path', $page->page_path);?>
        </p>
        <?php else:?>
        ページパス(バージョン管理対象外)<br />
        <?php if ( isset($page) && !empty($page->page_path) ):?>
        <span>
          <?php if ( $page->page_id > 1 ):?>
            <?php echo rawurldecode(parse_path_segment(prep_str($page->page_path), TRUE)) . '/';?>
            <?php echo $Helper->form->hidden('parent_page_path', rawurldecode(parse_path_segment(prep_str($page->page_path), TRUE)) . '/');?>
          <?php else:?>
            <?php echo '/'?>
            <?php echo $Helper->form->hidden('parent_page_path', '');?>
          <?php endif;?>
        </span>
        <?php echo $Helper->form->text(array('name' => 'page_path', 'value' => rawurldecode(parse_path_segment($page->page_path)), 'id' => 'sz_input_page_path'))?>
        <?php else:?>
        <?php echo rawurldecode(prep_str($parent_path)) . '/';?>
        <?php echo $Helper->form->hidden('parent_page_path', rawurldecode($parent_path) . '/');?>
        <?php echo $Helper->form->input(array('name' => 'page_path', 'value' => '', 'id' => 'sz_input_page_path'))?>
        <?php endif;?>
        <p style="margin:0">
          <a href="javascript:void(0)" id="check_exists" rel="<?php echo ( isset($page) ) ? $page->page_id : 0;?>">ページパスが存在するかチェック</a>
        </p>
        <?php endif;?>
      </td>
    </tr>
    <tr>
      <td>
        メタタグタイトル<br />
        <?php if ( isset($page) && ! empty($page->meta_title) ):?>
          <?php echo $Helper->form->text(array('name' => 'meta_title', 'value' => $page->meta_title));?>
        <?php else:?>
          <?php echo $Helper->form->text(array('name' => 'meta_title', 'value' => ''));?>
        <?php endif;?>
      </td>
      <td>
        公開日時<br />
        <?php if ( isset($page) ):?>
          <?php echo $Helper->form->text(array('name' => 'public_ymd', 'value' => set_public_datetime('Y-m-d', $page->public_datetime), 'class' => 'imedis', 'size' => 12));?>
          <?php echo $Helper->form->selectbox('public_time', hour_list(), set_public_datetime('H', $page->public_datetime));?>:
          <?php echo $Helper->form->selectbox('public_minute', minute_list(), set_public_datetime('i', $page->public_datetime));?>
        <?php else:?>
          <?php echo $Helper->form->text(array('name' => 'public_ymd','value' => date('Y-m-d', time()),'class' => 'imedis', 'size' => 12));?>
          <?php echo $Helper->form->selectbox('public_time', hour_list(), date('H', time()));?>:
          <?php echo $Helper->form->selectbox('public_minute', minute_list(), date('i', time()));?>
        <?php endif;?>
      </td>
    </tr>
    <tr class="alt">
      <td colspan="2">
        メタキーワード(複数設定する場合はカンマで区切ってください)<br />
        <?php if ( isset($page) && ! empty($page->meta_keyword) ):?>
          <?php echo $Helper->form->textarea(array('name' => 'meta_keyword', 'value' => $page->meta_keyword, 'cols' => 40, 'rows' => 2));?>
        <?php else:?>
          <?php echo $Helper->form->textarea(array('name' => 'meta_keyword', 'value' => '', 'cols' => 40, 'rows' => 2));?>
        <?php endif;?>
      </td>
    </tr>
    <tr>
      <td colspan="2">
        概要ワード<br />
        <?php if ( isset($page) && ! empty($page->meta_description) ):?>
          <?php echo $Helper->form->textarea(array('name' => 'meta_description', 'value' => $page->meta_description, 'cols' => 40, 'rows' => 2));?>
        <?php else:?>
          <?php echo $Helper->form->textarea(array('name' => 'meta_description', 'value' => '', 'cols' => 40, 'rows' => 2));?>
        <?php endif;?>
      </td>
    </tr>
    <tr class="alt">
      <td>
        <label>
          <?php echo $Helper->form->checkbox('navigation_show', 1, ( ! isset($page) || ( isset($page) && (int)$page->navigation_show === 1)) ? TRUE : FALSE);?>&nbsp;ナビゲーションに表示させる
        </label>
      </td>
      <td>
        <label>
          <?php echo $Helper->form->checkbox('target_blank', 1, ( isset($page) && (int)$page->target_blank === 1 ) ? TRUE : FALSE);?>&nbsp;別ウインドウ（タブ）でリンクを開く
        </label>
      </td>
    </tr>
    <?php if ( ! empty($seezoo->site->ssl_base_url) ):?>
    <tr>
      <td colspan="2">
        <label>
          <?php echo $Helper->form->checkbox('is_ssl_page', 1, ( isset($page) && (int)$page->is_ssl_page === 1 ) ? TRUE : FALSE);?>&nbsp;このページをSSL通信にする
        </label>
      </td>
    </tr>
    <?php endif;?>
  </tbody>
</table>