<?php echo $Helper->form->open($page->page_id, array('id' => 'sz-page_add_form'));?>
<ul class="tabs sz_tabs">
  <li><a href="#tab_content1" class="active">ページ設定</a></li>
  <li><a href="#tab_content3">アクセス権限</a></li>
  <li><a href="#tab_content2">使用テンプレート</a></li>
</ul>
<div id="tab_content1" class="tab_content">
  <?php echo $this->loadView('ajax/elements/page_base', array('parent_path' => $page->page_path));?>
</div>
<div id="tab_content2" class="init_hide tab_content">
  <table class="striped">
    <tbody>
      <?php foreach ( $templates as $key => $value ):?>
      <tr>
        <td>
          <?php echo $Helper->form->radio(
            'template_id',
            $value->template_id,
            ( $seezoo->site->default_template_id == $value->template_id) ? TRUE : FALSE,
            '',
            array('id' => 'tpid_' . $value->template_id)
          );?>
        </td>
        <td>
          <label for="tpid_<?php echo $value->template_id;?>">
            <?php if ( file_exists(ROOTPATH . 'templates/' . $value->template_handle . '/image.jpg') ):?>
            <img src="<?php echo file_link('templates/' . $value->template_handle . '/image.jpg');?>" alt="" class="style1" />
            <?php else:?>
            <img src="<?php echo file_link('images/no_image.gif');?>" alt="" class="style1"/>
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
  <?php echo $this->loadView('ajax/elements/page_permissions');?>
</div>
<p class="center mt20">
  <input type="hidden" name="page_id" value="<?php echo $page->page_id;?>" />
  <input type="hidden" name="sz_token" value="<?php echo $token;?>" />
  <input type="hidden" name="process" value="page_add" />
  <input type="hidden" name="from_po" value="<?php echo $fromPO;?>" />
  <button type="submit" class="medium blue center">
    <span class="icon white" data-icon="+" style="display:inline-block">
      <span aria-hidden="true">+</span>
    </span>
    ページを作成する
  </button>
 
</p>
<?php echo $Helper->form->close();?>