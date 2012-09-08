<p style="color:#c00;margin : 8px 0;font-size:14px;line-height:1.4">
  チェックを入れたユーザーに対して権限が設定されます。<br />また、ページのアクセス権限はバージョン管理対象外です。
</p>
<p style="border:solid 1px #ccc;padding:8px;background:#fdfdfd;font-size:14px;">
<label>
  <input type="checkbox" name="is_mobile_only" value="1"<?php if ( ! isset($page) || ( isset($page) && $page->is_mobile_only > 0)) { echo ' checked="checked"';} ?> />&nbsp;モバイルキャリアからのみのアクセスに制限する</label>
</p>

<table class="page_permissions">
  <tbody>
    <tr>
      <th>&nbsp;</th>
      <th>ページへのアクセス</th>
      <th>ページの編集</th>
      <th>ページの公開</th>
    </tr>
    <?php $cnt = 0;?>
    <?php foreach ( $user_list as $key => $value ):?>
    <tr<?php if ( ++$cnt % 2 === 0 ) echo ' class="odd"';?>>
      <td><?php echo prep_str($value->user_name);?></td>
      <?php if ($key == 1 || $value->admin_flag == 1):?>
      <td colspan="3" style="text-align:center">管理者権限により許可</td>
      <?php else:?>
      <td class="pp_ch">
        <input type="checkbox" name="permission[]" value="<?php echo $key;?>"<?php if ($seezoo->hasPagePermission($page->allow_access_user, $key)) { echo ' checked="checked"';}?> />
      </td>
      <td class="pp_ch">
        <?php if ( $key === 0 || $key === 'm' ):?>
        -
        <?php else:?>
        <input type="checkbox" name="permission_edit[]" value="<?php echo $key;?>"<?php if ($seezoo->hasPagePermission($page->allow_edit_user, $key)) { echo ' checked="checked"';}?> />
      <?php endif;?>
      </td>
      <td class="pp_ch">
        <?php if ( $key === 0 || $key === 'm' ):?>
        -
        <?php else:?>
        <input type="checkbox" name="permission_approve[]" value="<?php echo $key;?>"<?php if ($seezoo->hasPagePermission($page->allow_approve_user, $key)) { echo ' checked="checked"';}?> />
      <?php endif;?>
      </td>
    </tr>
    <?php endif;?>
    <?php endforeach;?>
  </tbody>
</table>