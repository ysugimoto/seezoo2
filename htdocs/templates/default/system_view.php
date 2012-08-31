<?php $this->sz_include('elements/header.php');?>

<!--++ main_img ++-->
<div id="main_img">

<?php echo $this->load->area('main_image');?>

</div>
<!--++ main_img end ++-->

<!--++ contents_wrap ++-->
<div id="contents_wrap" class="clearfix">

<!--++ main ++-->
<div id="main">

<?php echo set_content();?>
</div>
<!--++ main end ++-->

<!--++ sub ++-->
<div id="sub">

<?php echo set_menu();?>
<?php echo $this->load->area('submenu');?>

</div>
<!--++ sub end ++-->

</div>
<!--++ contents_wrap end ++-->




<!--++ footer ++-->
<div id="footer">

<address>Copyright&nbsp;&copy;&nbsp;<?php echo date('Y');?>&nbsp;<?php echo prep_str(SITE_TITLE);?></address>

</div>
<!--++ footer end ++-->

<?php $this->sz_include('elements/footer.php');?>


