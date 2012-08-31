<div class="sz_block_separator">
<p>Google&nbsp;Mapsを表示します。</p>
<dl>
	<dt><label for="version">使用するAPIのバージョン</label></dt>
	<dd>
		<?php echo form_dropdown('version', array(2 => 'version 2', 3 => 'version 3'), (int)$controller->version, 'id="version"');?>
		<span class="red">（※Interner Explorer 6を対象に入れる場合はversion 2を使用してください）</span>
	</dd>
	<dt><label for="api_key">APIキー</label></dt>
	<dd>
	<?php if ($controller->version == 3):?>
	<?php echo form_input(array('name' => 'api_key', 'id' => 'api_key', 'value' => '', 'class' => 'ime-dis long-text', 'disabled' => 'disabled'));?>
	<?php else:?>
	<?php echo form_input(array('name' => 'api_key', 'id' => 'api_key', 'value' => $controller->api_key, 'class' => 'ime-dis long-text'))?>
	<?php endif;?>
	<?php if (!empty($api_key)):?>
	<input type="text" name="eternal" value="1" checked="checked" />&nbsp;サイトの情報として保存する<br />
	<a href="http://code.google.com/apis/maps/signup.html" target="_blank">このサイトのGoogle&nbsp;Maps&nbsp;APIキーを取得する</a>
	<?php endif;?>
	</dd>
	<dt><label for="address">地図を表示する場所</label></dt>
	<dd><?php echo form_input(array('name' => 'address', 'id' => 'address', 'value' => $controller->address, 'class' => 'long-text'));?></dd>
	<dt><label for="zoom">拡大率</label></dt>
	<dd>
		<?php echo form_input(array('name' => 'zoom', 'id' => 'zoom', 'size' => 2, 'value' => $controller->zoom));?>
		<span>拡大率は0～17の間で設定できます。17が拡大率の最大値です。</span>
	</dd>	
</dl>
</div>
