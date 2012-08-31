<?php echo xml_define();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php echo $seezoo->loadHeader();?>
<link href="<?php echo $template_path;?>css/default.css" rel="stylesheet" type="text/css" />
</head>

<body>
<!--++ allitem ++-->
<div id="allitem">


<!--++ header ++-->
<div id="header">

<h1><?php echo SITE_TITLE;?></h1>

<!--++ header_nav ++-->
<div id="header_nav">

<?php echo area_create('primary_navigation');?>

</div>
<!--++ header_nav end ++-->

</div>
<!--++ header end ++-->

<!--++ nav ++-->
<div id="nav">

<?php echo area_create('global_navigation');?>

</div>
<!--++ nav end ++-->
