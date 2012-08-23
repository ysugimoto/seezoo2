<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="Content-Style-Type" content="text/css" />
	<meta http-equiv="Content-Script-Type" content="text/javascript" />
	<title>seezooのインストール</title>

	<link rel="stylesheet" type="text/css" href="<?php echo $siteUri; ?>css/dashboard.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $siteUri; ?>css/install.css" />
	<script type="text/javascript" src="<?php echo $siteUri; ?>js/config/base.config.js"></script>
	<script type="text/javascript" src="<?php echo $siteUri; ?>js/flint.dev2.min.js"></script>
	<script type="text/javascript">
	FL.load.library('install');
	FL.ready('install', function() { FL.install.init(); });
	</script>
</head>
<body>
	<div id="wrapper">
		<ul id="mainNav">
			<li><span>Installation&nbsp;seezoo</span></li>
		</ul>
		<!-- // #end mainNav -->
		<div id="containerHolder">
			<div id="container">
				<div id="container_full">
					<h3 class="install_caption">seezooをインストール</h3>
					<?php if ( $Validation->errorString() || $dbError === TRUE ):?>
					<div class="msg">
					<?php echo $Validation->errorString();?>
					<?php if ( $dbError === TRUE ):?>
					入力されたデータでデータベースの接続ができませんでした。
					<?php endif;?>
					</div>
					<?php endif;?>
					<h4 class="install_caption">ファイルの書き込み権限</h4>
					<div class="install_permission_wrapper">
						<ul class="install_permissions">
						<?php $cnt = 0;?>
						<?php foreach ($filePermissions as $key => $value):?>
							<li<?php if ($cnt === 0) echo ' class="first"';?>>
								<p>
								<em class="path_icon"><?php echo prep_str($key);?></em>への書き込み権限
								<span>
								<?php if ($value === TRUE):?>
								<?php echo $Helper->install->setInstallIcons('check.gif', $siteUri);?>&nbsp;OK
								<?php else:?>
								<?php echo $Helper->install->setInstallIcons('delete.png', $siteUri);?>&nbsp;書き込み権限がありません
								<?php endif;?>
								</span>
								</p>
							</li>
							<?php $cnt++;?>
						<?php endforeach;?>
						</ul>
					</div>
					<h4 class="install_caption">サーバー/PHPのインストール要件チェック</h4>
					<div class="install_permission_wrapper">
						<ul class="install_permissions">
						<li class="first">
							<p>
							<em class="path_icon">&nbsp;<?php echo $Helper->install->setInstallIcons('config.png', $siteUri);?>&nbsp;Apache&nbsp;mod_rewrite</em>モジュールの利用可否
							<span>
								<?php if ($isModRewriteEnable === 0):?>
								<?php echo $Helper->install->setInstallIcons('delete.png', $siteUri);?>&nbsp;利用できません
								<?php elseif ($isModRewriteEnable === 1):?>
								<?php echo $Helper->install->setInstallIcons('check.gif', $siteUri);?>&nbsp;利用可能
								<?php else:?>
								<?php echo $Helper->install->setInstallIcons('warning.png', $siteUri);?>&nbsp;不明
								<?php endif;?>
							</span>
							</p>
						</li>
						<li>
							<p>
							<em class="path_icon">&nbsp;<?php echo $Helper->install->setInstallIcons('config.png', $siteUri);?>&nbsp;PHP&nbsp;version&nbsp;:&nbsp;<?php echo PHP_VERSION;?></em>&gt;&nbsp;5.1.2
							<span>
								<?php if ($phpVersion === FALSE):?>
								<?php echo $Helper->install->setInstallIcons('delete.png', $siteUri);?>&nbsp;5.0以上が必要です
								<?php else:?>
								<?php echo $Helper->install->setInstallIcons('check.gif', $siteUri);?>&nbsp;OK
								<?php endif;?>
							</span>
							</p>
						</li>
						<li>
							<p>
							<em class="path_icon">&nbsp;<?php echo $Helper->install->setInstallIcons('config.png', $siteUri);?>&nbsp;json_encode関数</em>が利用可能かどうか
							<span>
								<?php if ($isJson === FALSE):?>
								<?php echo $Helper->install->setInstallIcons('delete.png', $siteUri);?>&nbsp;利用できません
								<?php else:?>
								<?php echo $Helper->install->setInstallIcons('check.gif', $siteUri);?>&nbsp;OK
								<?php endif;?>
							</span>
							</p>
						</li>
						<li>
							<p>
							<em class="path_icon">&nbsp;<?php echo $Helper->install->setInstallIcons('config.png', $siteUri);?>&nbsp;SimpleXML関数</em>が利用可能かどうか
							<span>
								<?php if ($isXml === FALSE):?>
								<?php echo $Helper->install->setInstallIcons('delete.png', $siteUri);?>&nbsp;利用できません
								<?php else:?>
								<?php echo $Helper->install->setInstallIcons('check.gif', $siteUri);?>&nbsp;OK
								<?php endif;?>
							</span>
							</p>
						</li>
						<li>
							<p>
							<em class="path_icon">&nbsp;<?php echo $Helper->install->setInstallIcons('config.png', $siteUri);?>&nbsp;GD関数</em>が利用可能かどうか
							<span>
								<?php if ($isGd === FALSE):?>
								<?php echo $Helper->install->setInstallIcons('delete.png', $siteUri);?>&nbsp;利用できません
								<?php else:?>
								<?php echo $Helper->install->setInstallIcons('check.gif', $siteUri);?>&nbsp;OK
								<?php endif;?>
							</span>
							</p>
						</li>
						<li>
							<p>
							<em class="path_icon">&nbsp;<?php echo $Helper->install->setInstallIcons('config.png', $siteUri);?>&nbsp;mbsting関数</em>が利用可能かどうか
							<span>
								<?php if ($isMbstring === FALSE):?>
								<?php echo $Helper->install->setInstallIcons('delete.png', $siteUri);?>&nbsp;利用できません
								<?php else:?>
								<?php echo $Helper->install->setInstallIcons('check.gif', $siteUri);?>&nbsp;OK
								<?php endif;?>
							</span>
							</p>
						</li>
						</ul>
					</div>
					<?php if (in_array(FALSE, $filePermissions)):?>
					<div class="permission_error">
						ディレクトリ/ファイルに書き込み権限が無いものがあります。権限を再度チェックしてください。<br />
						<a href="javascript:void(0)" id="reload"><?php echo $Helper->install->setInstallIcons('back.png', $siteUri);?>&nbsp;再チェック</a>
					</div>
					<div id="install_data_wrapper" style="display:none">
					<?php elseif ($phpVersion === FALSE):?>
					<div class="permission_error">
						PHPのバージョンが動作対象外です。PHPのバージョン5.0以上が対象です。
						<a href="javascript:void(0)" id="reload"><?php echo $Helper->install->setInstallIcons('back.png', $siteUri);?>&nbsp;再チェック</a>
					</div>
					<div id="install_data_wrapper" style="display:none">
					<?php elseif ($isJson === FALSE || $isXml === FALSE || $isGd === FALSE):?>
					<div class="permission_error">
						Seezooの動作に必要な関数が利用できません。PHPのバージョンを確認してください。
						<a href="javascript:void(0)" id="reload"><?php echo $Helper->install->setInstallIcons('back.png', $siteUri);?>&nbsp;再チェック</a>
					</div>			
					<div id="install_data_wrapper" style="display:none">
					<?php else:?>
					<div id="install_data_wrapper">
					<?php endif;?>
							<h4 class="install_caption">インストール情報の入力</h4>
							<div id="input_install_data">
								<?php echo $Helper->form->open($siteUri . DISPATCHER . '/install/do_install', array('id' => 'sz_install_form'));?>
								<?php $cnt = 0;?>
								<table>
									<tbody>
										<?php foreach ($Validation->getFields() as $field ):?>
										<tr<?php if ($cnt++ % 2 > 0) echo ' class="odd"';?>>
											<th>
												<?php echo prep_str($field->getLabel());?>
												<?php if ( $field->getName() === 'admin_password'):?>
												<a href="javascript:void(0)" id="randomize">
													<?php echo $Helper->install->setInstallIcons('lock.png', $siteUri);?>&nbsp;ランダム生成
												</a>
												<?php endif;?>
											</th>
											<td>
											<?php echo $Helper->form->text(array(
												'name'     => $field->getName(),
												'id'       => $field->getName(),
												'value'    => $field->getValue(FALSE),
												'tabindex' => $cnt
											));?>
											</td>
										</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
								<p id="login_btn">
									<?php foreach ($hidden as $name => $value) : ?>
									<?php echo $Helper->form->hidden($name, $value); ?>
									<?php endforeach; ?>
									<input type="submit" alt="インストール" value="Seezooをインストール!" id="btn" disabled="disabled" />
								</p>
								<?php echo $Helper->form->close();?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<p id="footer"></p>
	</div>
</body>
</html>
