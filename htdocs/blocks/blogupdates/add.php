<p>ブログの更新情報を表示します。<br />表示条件を設定してください。</p>
<dl>
  <dt><label for="view_count">表示件数</label></dt>
  <dd><?php echo form_input(array('name' => 'view_count', 'id' => 'view_count', 'value' => '10', 'class' => 'tiny-text'))?></dd>
  <dt><label for="category">表示カテゴリ</label></dt>
  <dd><?php echo form_dropdown('category', $controller->get_blog_categories(), 0, 'id="category"');?></dd>
  <dt><label for="posted_user">投稿者</label></dt>
  <dd><?php echo form_dropdown('posted_user', $controller->get_blog_posted_users(), 0, 'id="posted_user"');?></dd>
  <dt>その他設定</dt>
  <dd>
    <label><?php echo form_checkbox('accept_comment_only', 1, FALSE);?>&nbsp;コメントを許可してる投稿のみ表示する</label><br />
    <label><?php echo form_checkbox('accept_trackback_only', 1, FALSE);?>&nbsp;トラックバックを許可してる投稿のみ表示する</label>
</dl>
