<div class="sz_form_separator">
<ul id="sz_form_tab" class="clearfix">
	<li><a href="#content1" class="active">質問の追加</a></li>
	<li><a href="#content4">質問の編集</a></li>
	<li><a href="#content2">配置順の設定</a></li>
	<li><a href="#content3">フォームに関する設定</a></li>
	<li><a href="#content5">自動返信メール本文</a></li>
</ul>
</div>
<div id="form_msg">質問を追加しました。</div>
<div class="tab_wrapper">
<div id="content1" class="tabcontents">
	<table class="add_forms">
		<tbody>
			<tr>
				<th><label for="question_name">質問名</label></th>
				<td><?php echo form_input(array('name' => 'question_name', 'id' => 'question_name'))?></td>
			</tr>
			<tr>
				<th><label for="question_caption">例示テキスト</label></th>
				<td><?php echo form_input(array('name' => 'caption', 'id' => 'question_caption', 'class' => 'middle-text'))?>&nbsp;※例示などを表示できます。</td>
			</tr>
			<tr>
				<th><label for="class_name">class属性</label></th>
				<td><?php echo form_input(array('name' => 'class_name', 'id' => 'class_name'))?></td>
			</tr>
			<tr>
				<th>入力タイプ</th>
				<td>
					<div class="types public">
					<span>汎用タイプ</span>
					<p><label><?php echo form_radio('question_type', 'text');?>&nbsp;テキストフィールド</label></p>
					<p><label><?php echo form_radio('question_type', 'textarea');?>&nbsp;テキストボックス</label></p>
					<p><label><?php echo form_radio('question_type', 'radio');?>&nbsp;ラジオボタン</label></p>
					<p><label><?php echo form_radio('question_type', 'checkbox');?>&nbsp;チェックボックス</label></p>
					<p><label><?php echo form_radio('question_type', 'select');?>&nbsp;プルダウン</label></p>
<!-- we not use for our security policy.
					<p><label><?php echo form_radio('question_type', 'file');?>&nbsp;ファイル添付</label></p>					
-->
					</div>
					<div class="types special">
					<span>固定フォーマット</span>
					<p><label><?php echo form_radio('question_type', 'email');?>&nbsp;自動返信用メールアドレス</label></p>
					<p><label><?php echo form_radio('question_type', 'pref');?>&nbsp;都道府県リスト</label></p>
					<p><label><?php echo form_radio('question_type', 'birth_year');?>&nbsp;誕生日西暦年リスト</label></p>
					<p><label><?php echo form_radio('question_type', 'month');?>&nbsp;月リスト</label></p>
					<p><label><?php echo form_radio('question_type', 'day');?>&nbsp;日付リスト</label></p>
					<p><label><?php echo form_radio('question_type', 'hour');?>&nbsp;24時間リスト</label></p>
					<p><label><?php echo form_radio('question_type', 'minute');?>&nbsp;分リスト</label></p>
					</div>
				</td>
			</tr>
			<tr id="option_area_radio" class="init_hide">
				<th>オプション項目</th>
				<td>
					<a href="javascript:void(0)" class="option_add radio"><?php echo set_image('plus.png', TRUE)?>オプション項目を追加</a><br />
					<p>ラベル：<input type="text" name="radio_option_label[]" value="" />&nbsp;&nbsp;<a href="javascript:void(0)" class="option_delete radio">削除</a></p>
				</td>
			</tr>
			<tr id="option_area_checkbox" class="init_hide">
				<th>オプション項目</th>
				<td>
					<a href="javascript:void(0)" class="option_add checkbox"><?php echo set_image('plus.png', TRUE)?>オプション項目を追加</a>
					<p>ラベル：<input type="text" name="checkbox_option_label[]" value="" />&nbsp;&nbsp;<a href="javascript:void(0)" class="option_delete checkbox">削除</a></p>
				</td>
			</tr>
			<tr id="option_area_select" class="init_hide">
				<th>オプション項目</th>
				<td>
					<a href="javascript:void(0)" class="option_add select"><?php echo set_image('plus.png', TRUE)?>オプション項目を追加</a>
					<p>ラベル：<input type="text" name="select_option_label[]" value="" />&nbsp;&nbsp;<a href="javascript:void(0)" class="option_delete select">削除</a></p>
				</td>
			</tr>
			<tr id="option_textarea" class="init_hide">
				<th>オプション項目</th>
				<td>
					<p>行数：<input type="text" name="sz_form_rows" value="" />&nbsp;列数：<input type="text" name="sz_form_cols" value="" /></p>
				</td>
			</tr>
<!-- we not use for our security policy.
			<tr id="file_option" class="init_hide">
				<th>オプション項目</th>
				<td>
					<p>
						許可するファイルタイプ：<br />
						<?php foreach ($controller->accept_file_ext() as $key => $value):?>
						<label><input type="checkbox" name="file_ext[]" value="<?php echo $key;?>" />&nbsp;<?php echo $value;?></label><br />
						<?php endforeach;?>
					</p>
					<p>
						許可するファイルサイズ(KB)：<br />
						<label><input type="text" name="file_size" value="" />&nbsp;KBまで</label>
					</p>
				</td>
			</tr>
-->
			<tr>
				<th>入力ルール</th>
				<td>
					<p><label><?php echo form_checkbox('validate_rules[]', 'required');?>&nbsp;必須入力にする</label></p>
					<p><label><?php echo form_checkbox('validate_rules[]', 'valid_email');?>&nbsp;メールアドレスの形式チェックを行う</label></p>
					<p><label><?php echo form_checkbox('validate_rules[]', 'numeric');?>&nbsp;数値のみの入力を許可する</label></p>
					<p><label><?php echo form_input(array('name' => 'max_length', 'value' => '', 'class' => 'text-int', 'id' => 'rule_max_length'));?>&nbsp;文字以内の入力に制限する</label></p>
					<p><label><?php echo form_input(array('name' => 'min_length', 'value' => '', 'class' => 'text-int', 'id' => 'rule_min_length'));?>&nbsp;文字以上の入力に制限する</label></p>

				</td>
			</tr>
			<tr class="sz_fm_sbt">
				<td colspan ="2">
					<p class="submit">
						<input type="hidden" name="question_key" id="q_key" value="<?php echo $controller->generate_key()?>" />
						<input type="button" value="質問を追加する" id="question_add" />
					</p>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<div id="content2" class="tabcontents init_hide">
	<p style="text-align:center">配置順を変更した場合、保存ボタンを押して保存してください。</p>
	<ul id="form_preview">

	</ul>
	<p class="submit" id="sort_wrapper">
	</p>
</div>
<div id="content3" class="tabcontents init_hide">
	<table class="add_forms">
		<tbody>
			<tr>
				<th>フォームのタイトル</th>
				<td><?php echo form_input(array('name' => 'form_title', 'value' => $controller->get_site_data(), 'size' => 50));?></td>
			</tr>
			<tr>
				<th>フォームに付けるclass名</th>
				<td><?php echo form_input(array('name' => 'form_class_name', 'value' => '', 'size' => 50));?></td>
			</tr>
			<tr>
				<th>送信完了時のメッセージ<br />(HTML有効)</th>
				<td>
					<?php echo form_textarea(array('name' => 'thanks_msg', 'value' => '送信が完了しました。ありがとうございます。', 'rows' => 3, 'cols' => 50, 'style="font-size : 12px;"'));?>
				</td>
			</tr>
			<tr>
				<th>自動返信メールの設定</th>
				<td><input type="checkbox" value="1" name="is_remail" id="is_remail" />&nbsp;問い合わせの通知を受ける
					<p id="remail" style="display:none;">
						<?php echo form_input(array('name' => 're_mail', 'value' => $controller->get_user_email(), 'class' => 'ime-dis', 'size' => '50'))?><br />のアドレスにメールで通知する
					</p>
				</td>
			</tr>
			<tr>
				<th>画像認証（CAPTCHA）設定</th>
				<td><input type="checkbox" name="use_captcha" value="1" />&nbsp;画像認証を有効にする </td>
			</tr>
		</tbody>
	</table>
</div>
<div id="content5" class="tabcontents init_hide">
	<ul>
		<li>設置する質問の中に「自動返信用メールアドレス」が含まれる場合のみ有効です。</li>
		<li><strong>{{FORM_DATA}}</strong>の部分にお問い合わせ内容が挿入されます。特別な場合を除いて削除しないようにしてください。</li>
		<li>HTMLは無効です。</li>
	</ul>
	<p>
		送信メールタイトル：
		<?php echo form_input(array('name' => 'auto_reply_mail_subject', 'class' => 'auto_reply_mail_subject', 'value' => Form_block::DEFAULT_MAIL_SUBJECT));?>
	</p>
	
	<p>
		送信メール本文：
		<?php echo form_textarea(array('name' => 'auto_reply_mailbody', 'class' => 'auto_reply_mailbody', 'value' => Form_block::DEFAULT_MAIL_BODY));?>
	</p>
</div>
<div id="content4" class="tabcontents init_hide">
	<p id="edit_select_msg">編集する質問を「配置順設定」から選択してください。</p>
	<table class="add_forms init_hide" id="edit_table">
		<tbody>
			<tr>
				<th><label for="question_name_edit">質問名</label></th>
				<td><?php echo form_input(array('name' => 'question_name_edit', 'id' => 'question_name_edit'))?></td>
			</tr>
			<tr>
				<th><label for="question_caption_edit">例示テキスト</label></th>
				<td><?php echo form_input(array('name' => 'caption_edit', 'id' => 'question_caption_edit', 'class' => 'middle-text'))?>&nbsp;※例示などを表示できます。</td>
			</tr>
			<tr>
				<th><label for="class_name_edit">class属性</label></th>
				<td><?php echo form_input(array('name' => 'class_name_edit', 'id' => 'class_name_edit'))?></td>
			</tr>
			<tr>
				<th>入力タイプ</th>
				<td>
					<div class="types public">
					<span>汎用タイプ</span>
					<p><label><?php echo form_radio('question_type_edit', 'text');?>&nbsp;テキストフィールド</label></p>
					<p><label><?php echo form_radio('question_type_edit', 'textarea');?>&nbsp;テキストボックス</label></p>
					<p><label><?php echo form_radio('question_type_edit', 'radio');?>&nbsp;ラジオボタン</label></p>
					<p><label><?php echo form_radio('question_type_edit', 'checkbox');?>&nbsp;チェックボックス</label></p>
					<p><label><?php echo form_radio('question_type_edit', 'select');?>&nbsp;プルダウン</label></p>
<!-- we not use for our security policy.
					<p><label><?php echo form_radio('question_type', 'file');?>&nbsp;ファイル添付</label></p>					
-->
					</div>
					<div class="types special">
					<span>固定フォーマット</span>
					<p><label><?php echo form_radio('question_type_edit', 'email');?>&nbsp;自動返信用メールアドレス</label></p>
					<p><label><?php echo form_radio('question_type_edit', 'pref');?>&nbsp;都道府県リスト</label></p>
					<p><label><?php echo form_radio('question_type_edit', 'birth_year');?>&nbsp;誕生日西暦年リスト</label></p>
					<p><label><?php echo form_radio('question_type_edit', 'month');?>&nbsp;月リスト</label></p>
					<p><label><?php echo form_radio('question_type_edit', 'day');?>&nbsp;日付リスト</label></p>
					<p><label><?php echo form_radio('question_type_edit', 'hour');?>&nbsp;24時間リスト</label></p>
					<p><label><?php echo form_radio('question_type_edit', 'minute');?>&nbsp;分リスト</label></p>
					</div>
				</td>
			</tr>
			<tr id="option_area_radio_edit" class="init_hide">
				<th>オプション項目</th>
				<td>
					<a href="javascript:void(0)" class="option_add edit radio"><?php echo set_image('plus.png', TRUE)?>オプション項目を追加</a>
					<div>
					<p>ラベル：<input type="text" name="radio_option_label_edit[]" value="" /&>&nbsp;&nbsp;<a href="javascript:void(0)" class="option_delete radio">削除</a></p>
					</div>
				</td>
			</tr>
			<tr id="option_area_checkbox_edit" class="init_hide">
				<th>オプション項目</th>
				<td>
					<a href="javascript:void(0)" class="option_add edit checkbox"><?php echo set_image('plus.png', TRUE)?>オプション項目を追加</a>
					<div>
					<p>ラベル：<input type="text" name="checkbox_option_label[]" value="" /&>&nbsp;&nbsp;<a href="javascript:void(0)" class="option_delete radio">削除</a></p>
					</div>
				</td>
			</tr>
			<tr id="option_area_select_edit" class="init_hide">
				<th>オプション項目</th>
				<td>
					<a href="javascript:void(0)" class="option_add edit select"><?php echo set_image('plus.png', TRUE)?>オプション項目を追加</a>
					<div>
					<p>ラベル：<input type="text" name="select_option_label[]" value="" /&>&nbsp;&nbsp;<a href="javascript:void(0)" class="option_delete radio">削除</a></p></div>
				</td>
			</tr>
			<tr id="option_textarea_edit" class="init_hide">
				<th>オプション項目</th>
				<td>
					<p>行数：<input type="text" name="sz_form_rows" value="" />&nbsp;列数：<input type="text" name="sz_form_cols" value="" /></p>
				</td>
			</tr>
<!--  we not use for our security policy. 
			<tr id="file_option_edit" class="init_hide">
				<th>オプション項目</th>
				<td>
					<p>
						許可するファイルタイプ：<br />
						<?php foreach ($controller->accept_file_ext() as $key => $value):?>
						<label><input type="checkbox" name="file_ext_edit[]" value="<?php echo $key;?>" />&nbsp;<?php echo $value;?></label><br />
						<?php endforeach;?>
					</p>
					<p>
						許可するファイルサイズ(KB)：<br />
						<label><input type="text" name="file_size" value="" />&nbsp;KBまで</label>
					</p>
				</td>
			</tr>
-->
			<tr	>
				<th>入力ルール</th>
				<td>
					<p><label><?php echo form_checkbox('validate_rules_edit[]', 'required');?>&nbsp;必須入力にする</label></p>
					<p><label><?php echo form_checkbox('validate_rules_edit[]', 'valid_email');?>&nbsp;メールアドレスの形式チェックを行う</label></p>
					<p><label><?php echo form_checkbox('validate_rules_edit[]', 'numeric');?>&nbsp;数値のみの入力を許可する</label></p>
					<p><label><?php echo form_input(array('name' => 'max_length_edit', 'value' => '', 'class' => 'text-int', 'id' => 'rule_max_length'));?>&nbsp;文字以内の入力に制限する</label></p>
					<p><label><?php echo form_input(array('name' => 'min_length_edit', 'value' => '', 'class' => 'text-int', 'id' => 'rule_min_length'));?>&nbsp;文字以上の入力に制限する</label></p>

				</td>
			</tr>
			<tr>
				<td colspan ="2">
					<p class="submit">
						<input type="hidden" name="quertion_key_edit" id="q_key_edit", value="" />
						<input type="button" value="質問を編集する" id="question_edit" />
					</p>
				</td>
			</tr>
		</tbody>
	</table>
</div>
</div>
