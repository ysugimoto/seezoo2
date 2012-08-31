<?php $this->loadView('elements/header');?>

<!--++ main_img ++-->
<div id="main_img">

<?php echo area_create('main_image');?>

</div>
<!--++ main_img end ++-->

<!--++ contents_wrap ++-->
<div id="contents_wrap" class="clearfix">

<!--++ main ++-->
<div id="main">
<?php echo area_create('main');?>
</div>
<!--++ main end ++-->

<!--++ sub ++-->
<div id="sub">

<?php echo area_create('submenu');?>
</div>
<!--++ sub end ++-->

</div>
<!--++ contents_wrap end ++-->




<!--++ footer ++-->
<div id="footer">

<?php echo area_create('footer_navigation');?>

<address>Copyright&nbsp;&copy;&nbsp;<?php echo date('Y');?>&nbsp;<?php echo prep_str(SITE_TITLE);?></address>

</div>
<!--++ footer end ++-->

<?php $this->loadView('elements/footer.php');?>


