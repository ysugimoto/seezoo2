<p style="color:#c00;margin : 8px 0;">※チェックを入れたユーザーに対して権限が設定されます。</p>
<table class="striped">
  <tbody>
    <tr>
      <th>&nbsp;</th>
      <th>ページへのアクセス</th>
      <th>ページの編集</th>
      <th>ページの公開</th>
    </tr>
    <?php $cnt = 0;?>
    <?php foreach ($users as $key => $user):?>
    <tr<?php if (++$cnt % 2 === 0) echo ' class="alt"';?>>
      <td><?php echo prep_str($user->user_name);?></td>
      <?php if ( $key == 1 || $user->admin_flag > 0 ):?>
      <td colspan="3" class="center">管理者権限により許可</td>
      <?php else:?>
      <td class="pp_ch">
        <?php echo $Helper->form->checkbox('permission[]', $key, ( $key === 0 || $key === 'm' || $value->admin_flag < 1 ) ? TRUE : FALSE);?>
      </td>
      <td class="pp_ch">
        <?php if ( $key === 0 || $key === 'm' ):?>
        -
        <?php else:?>
        <?php echo $Helper->form->checkbox('permission_edit[]', $key);?>
        <?php endif;?>
      </td>
      <td class="pp_ch">
        <?php if ( $key === 0 || $key === 'm' ):?>
        -
        <?php else:?>
        <?php echo $Helper->form->checkbox('permissions_approve[]', $key);?>
        <?php endif;?>
      </td>
      <?php endif;?>
    </tr>
  <?php endforeach;?>
  </tbody>
</table>
