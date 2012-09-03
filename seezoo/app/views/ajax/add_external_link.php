<?php echo $Helper->form->open($page_id, array('id' => 'sz-page_add_form'));?>
<h3>外部リンクを追加</h3>
<div class="external_link_form">
	<h4>外部リンクを作成します。リンク先のURLとタイトルを入力してください。</h4>
	<table>
		<tbody>
			<tr class="odd">
				<td class="cap">外部リンクURL</td>
				<td><?php echo $Helper->form->text(array('name' => 'external_link', 'class' => 'long-text', 'id' => 'add_external_uri'));?></td>
			</tr>
			<tr>
				<td class="cap">リンクタイトル</td>
				<td><?php echo $Helper->form->text(array('name' => 'page_title', 'class' => 'long-text', 'id' => 'add_external_title'));?></td>
			</tr>
			<tr class="odd">
				<td class="cap">&nbsp;</td>
				<td>
					<label><?php echo $Helper->form->checkbox('navigation_show', 1, TRUE);?>&nbsp;ナビゲーションに表示する</label>
				</td>
			</tr>
			<tr>
				<td class="cap">&nbsp;</td>
				<td>
					<label><?php echo $Helper->form->checkbox('target_blank', 1, FALSE);?>&nbsp;新規タブ（ウインドウで開く）</label>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<p class="sz_add_form_submit">
	<input type="hidden" name="from_po" value="1" />
	<input type="hidden" name="page_id" value="<?php echo $page_id;?>" />
	<input type="hidden" name="sz_token" value="<?php echo $token;?>" />
	<input type="hidden" name="process" value="external_page_add" />
	<input type="submit" value="外部リンクを追加する" class="page_submit" />
</p>
<?php echo $Helper->form->close();?>