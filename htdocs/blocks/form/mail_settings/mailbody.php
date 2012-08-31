<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>

<?php echo $form_title;?>に送信がありました。

送信内容は以下の通りです。

<?php foreach ($answers as $answer):?>
<?php echo $answer['name'];?>  :  <?php echo $answer['value'];?>


<?php endforeach;?>


詳細は管理画面のレポートから確認できます。
<?php echo page_link();?>dashboard/reports（ログインが必要です）

----------------------------------------------------------------------------------------------------------
