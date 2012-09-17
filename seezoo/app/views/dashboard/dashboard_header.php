<!DOCTYPE html>
<html>
  <head>
    <title>dashboard</title>
    <meta charset="UTF-8">
    <meta name="description" content="" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_link();?>css/dashboard/kickstart.css" media="all" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_link();?>css/dashboard/dashboard.css" media="all" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_link();?>css/ajax_style.css" media="all" />
    <!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <script type="text/javascript" src="<?php echo base_link();?>js/kickstart_lite.js"></script>
    <?php echo flint_execute('segment');?>
  </head>
  <body>
    <div id="leftpane">
      <header>
        <p class="center">
          <a href="<?php echo page_link('dashboard');?>">Dashboard</a>
        </p>
      </header>
      <nav>
       <?php echo $seezoo->buildDashboardMenu();?>
      </nav>
    </div>
    <div id="headmenu_wrap">
      <ul id="headmenu" class="menu">
        <!--<li><a href="#" class="logo">seezoo</a></li>-->
        <li><a href="#"><span class="icon small white" data-icon="+"></span></a></li>
        <li class="right"><a href="<?php echo page_link('logout');?>"><span class="icon white small" data-icon="Q"></span></a></li>
        <li class="right"><a href="<?php echo page_link();?>"><span class="icon white small" data-icon=":"></span></a></li>
      </ul>
    </div>
    <div id="rightpane">
      <h1><?php echo $seezoo->page->page_title;?></h1>