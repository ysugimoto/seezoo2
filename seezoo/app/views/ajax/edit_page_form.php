<?php echo $Helper->form->open($page->page_id, array('id' => 'sz-page_add_form'));?>
<h3>ページ情報を編集</h3>
<ul class="sz_tabs clearfix">
  <li><a href="#tab_content1" class="active">ページ設定</a></li>
  <li><a href="#tab_content3">アクセス権限</a></li>
  <li><a href="#tab_content2">使用テンプレート</a></li>
</ul>
<div id="tab_content1" class="tab_content">
  <?php echo $this->loadView('ajax/page_base');?>
</div>
<div id="tab_content2" class="init_hide tab_content">
  <table>
    <tbody>
      <?php foreach ( $templates as $key => $value ):?>
      <tr>
        <td>
          <input type="radio" name="template_id" id="tpid_<?php echo $value->template_id;?>" value="<?php echo $value->template_id;?>"<?php if ( $page->template_id == $value->template_id ) { echo ' checked="checked"';}?> />
        </td>
        <td>
          <label for="tpid_<?php echo $value->template_id;?>">
          <?php if ( file_exists(ROOTPATH . 'templates/' . $value->template_handle . '/image.jpg')):?>
            <img src="<?php echo file_link()?>templates/<?php echo prep_str($value->template_handle);?>/image.jpg" alt="" />
          <?php else:?>
            <img src="<?php echo file_link()?>images/no_image.gif" alt=""/>
          <?php endif;?>
          </label>
        </td>
        <td>
          <p style="font-weight:bold"><?php echo prep_str($value->template_name);?></p>
          <p><?php echo nl2br(prep_str($value->description));?></p>
        </td>
      </tr>
    <?php endforeach;?>
    </tbody>
  </table>
</div>
<div id="tab_content3" class="init_hide tab_content">
  <?php echo $this->loadView('ajax/page_permissions_edit');?>
</div>
<p class="sz_add_form_submit">
  <?php echo $Helper->form->hidden('page_id', $page->page_id);?>
  <?php echo $Helper->form->hidden('sz_token', $token);?>
  <?php echo $Helper->form->hidden('page_path_id', $page->page_path_id);?>
  <?php echo $Helper->form->hidden('version_number', $page->version_number);?>
  <input type="hidden" name="process" value="page_edit_config" />
  <input type="submit" value="ページ情報を保存する" class="page_submit" />
</p>
<?php echo $Helper->form->close();?>