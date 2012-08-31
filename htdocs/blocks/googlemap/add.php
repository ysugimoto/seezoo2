<?php $api_key = $controller->get_api_key_from_site_info();?>
<div class="sz_block_separator">
<p>Google&nbsp;Mapsを表示します。</p>
<dl>
	<dt><label for="version">使用するAPIのバージョン</label></dt>
	<dd>
		<?php echo form_dropdown('version', array(2 => 'version 2', 3 => 'version 3'), 3, 'id="version"');?><br />
		<span style="color:#c00">※Internet&nbsp;Explorer&nbsp;6を対象に入れる場合はversion&nbsp;2を使用してください</span>
	</dd>
	<dt><label for="api_key">APIキー</label></dt>
	<dd>
		<?php echo form_input(array('name' => 'api_key', 'id' => 'api_key', 'value' => $api_key, 'class' => 'ime-dis long-text', 'disabled' => 'disabled'))?>
		<?php if (empty($api_key)):?>
		<br /><input type="checkbox" name="eternal" value="1" disabled="disabled" />&nbsp;サイトの情報として保存する<br />
		<a href="http://code.google.com/apis/maps/signup.html" target="_blank">このサイトのGoogle&nbsp;Maps&nbsp;APIキーを取得する</a>
		<?php endif;?>
	</dd>
	<dt><label for="address">地図を表示する場所</label></dt>
	<dd><?php echo form_input(array('name' => 'address', 'id' => 'address', 'class' => 'long-text'));?></dd>
	<dt><label for="zoom">拡大率</label></dt>
	<dd>
		<?php echo form_input(array('name' => 'zoom', 'id' => 'zoom', 'size' => 2, 'value' => 14));?>
		<span>拡大率は0～17の間で設定できます。17が拡大率の最大値です。</span>
	</dd>	
</dl>
</div>
