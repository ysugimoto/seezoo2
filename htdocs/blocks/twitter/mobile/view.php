<?php
$tweet = $controller->get_tweet_by_serverside();
//var_dump($tweet);
?>
<?php if ( ! is_array($tweet) ):?>
<p>ツイートが取得できませんでした。</p>
<?php else:?>
<ul>
<?php foreach ( $tweet as $tw ):?>
<li>
<p><?php echo prep_str($tw->text);?></p>
<div align="right">
<font size="-1"><?php echo date('Y-m-d H:i:s', strtotime($tw->created_at));?></font>
</div>
</li>
<?php endforeach;?>
</ul>
<?php endif;?>