<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Content-Style-Type" content="text/css" />
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<meta name="robots" content="noindex,nofollow" />
<title>seezoo admin panel</title>

<!-- CSS -->
<link href="<?php echo file_link();?>css/dashboard.css" rel="stylesheet" type="text/css" media="screen" />
<link href="<?php echo file_link();?>css/ajax_styles.css" rel="stylesheet" type="text/css" media="screen" />
<?php if (ADVANCE_UA === 'ie6'):?>
<link rel="stylesheet" type="text/css" href="<?php echo file_link();?>css/edit_base_advance_ie6.css" />
<?php elseif (ADVANCE_UA === 'ie7'):?>
<link rel="stylesheet" type="text/css" href="<?php echo file_link();?>css/edit_base_advance_ie7.css" />
<?php endif;?>
<?php echo flint_execute('segment');?>
</head>
<body>
  <div id="wrapper">
  <!-- h1 tag stays for the logo, you can use the a tag for linking the index page -->
    <!-- You can name the links with lowercase, they will be transformed to uppercase by CSS, we prefered to name them with uppercase to have the same effect with disabled stylesheet -->
    <ul id="mainNav">
      <li><a href="<?php echo page_link('dashboard');?>" class="active">Dashboard</a></li> <!-- Use the "active" class for the active menu item  -->
      <?php if ( $site->is_maintenance > 0 ):?>
      <li><a class="maintenance" href="<?php echo page_link('dashboard/site_settings')?>">現在、メンテナンスモードに設定されています！</a></li>
      <?php endif;?>
      <li class="logout"><a href="<?php echo page_link('logout')?>">ログアウト</a></li>
      <li class="logout"><a href="<?php echo page_link()?>">サイトに戻る</a></li>
      <?php if ( $Lead->isRollbackUser() ):?>
      <li class="logout"><a href="<?php echo page_link('dashboard/panel/rollback_user')?>">元のユーザに戻る</a></li>
      <?php endif;?>
    </ul>
    <!-- // #end mainNav -->
    
    <div id="containerHolder">
      <?php if ( ! isset($sidebar) || $sidebar !== FALSE ):?>
      <div id="container">
        <div id="sidebar">
          <?php echo $seezoo->buildDashboardMenu();?> 
        </div>
        <!-- // #sidebar -->
      <?php else:?>
      <div id="container_full">
      <?php endif;?>