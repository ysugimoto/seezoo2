<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ja" lang="ja">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="Content-Style-Type" content="text/css" />
  <meta http-equiv="Content-Script-Type" content="text/javascript" />
  <title>seezooのインストールが完了しました</title>

  <script type="text/javascript" src="<?php echo $siteUri; ?>js/config/base.config.js"></script>
  <script type="text/javascript" src="<?php echo $siteUri; ?>js/flint.dev.js"></script>

  <link rel="stylesheet" type="text/css" href="<?php echo $siteUri; ?>css/dashboard.css" />
  <link rel="stylesheet" type="text/css" href="<?php echo $siteUri; ?>css/install.css" />
</head>
<body>
  <div id="wrapper">
    <ul id="mainNav">
      <li><span>Installation&nbsp;Seezoo</span></li> <!-- Use the "active" class for the active menu item  -->
    </ul>
    <!-- // #end mainNav -->
    <div id="containerHolder">
      <div id="container">
        <div id="container_full">
          <div id="install_complete_box">
            <h2>インストール完了</h2>
            <p class="install_complete">
              seezooのインストールが完了しました！<br />
              ログインIDとパスワードを忘れないようにしてください。<br />
            </p>
            <p class="to_top">
              <a href="<?php echo $siteUri;?>">&laquo;トップページへ</a>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <a href="<?php echo $siteUri;?>index.php/dashboard/">管理画面へ&raquo;</a>
            </p>
          </div>
        </div>
      </div>
    </div>
    <p id="footer"></p>
  </div>
</body>
</html>
