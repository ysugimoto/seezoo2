<!DOCTYPE html> 
<html lang="ja"> 
<head>
<?php echo $this->load->view('header_required');?>
<link rel="stylesheet" type="text/css" href="<?php echo $template_path;?>css/smartphone.css" />
<script type="text/javascript" src="<?php echo $template_path;?>js/smartphone.js"></script>
</head>

<body>

<header>
<h1><?php echo prep_str(SITE_TITLE);?></h1>
<a href="javascript:void(0)" class="toggle"><?php echo set_image('plus.png', TRUE);?></a>
<nav>
<?php echo $seezoo->get_global_navigation('sz_sp_nav', 'sz_sp_nav_active');?>
</nav>
</header>
<div id="content">
<?php echo $this->load->area('main_image');?>

<?php echo $this->load->area('main');?>
</div>

<footer>
<address>Copyright&nbsp;&copy;&nbsp;<?php echo date('Y');?>&nbsp;<?php echo prep_str(SITE_TITLE);?></address>
</footer>
<?php echo $this->load->view('footer_required');?>

</body>
</html>