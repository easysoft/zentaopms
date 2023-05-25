#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/score.class.php';
su('admin');

/**

title=测试 scoreModel->reset();
cid=1
pid=1

重置积分成功 >> finish,0

*/

$score = new scoreTest();

r($score->resetTest()) && p('status,number') && e('finish,0'); // 重置积分成功