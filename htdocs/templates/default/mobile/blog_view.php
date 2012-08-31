<html>
<head>
<?php echo $this->load->view('header_required');?>
</head>
<body bgcolor="#ffffff" text="#000000">
<h1 align="center"><?php echo SITE_TITLE;?></h1>

<?php echo $this->load->area('global_navigation');?>

<?php echo $this->load->area('main_image');?>

<?php echo set_content();?>

<address>Copyright&nbsp;&copy;&nbsp;<?php echo date('Y');?>&nbsp;<?php echo prep_str(SITE_TITLE);?></address>

<?php echo $this->load->view('footer_required');?>
</body>
</html>