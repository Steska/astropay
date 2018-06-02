<?php

/* @var $this yii\web\View
    @var array $params;
 * @var array $result;
 */

$this->title = 'My Yii Application';
?>
<div class="site-index">
    <?php
    foreach ($params as $key => $value){
        echo '<p><b>'.$key. '</b>                     '. $value.'</p>';
    }
    echo "<b>Token</b>: ".$result;
    ?>


</div>
