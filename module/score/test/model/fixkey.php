#!/usr/bin/env php
<?php
/**

title=测试 scoreModel->fixKey();
cid=1

- 将created改为create @create
- 将opened改为create @create
- 将closed改为close @close
- 将finished改为finish @finish
- 将bugconfirmed改为confirm @confirm
- 将resolved改为resolve @resolve
- 测试不存在的key @fixed

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/score.class.php';

$actionList = array('created', 'opened', 'closed', 'finished', 'bugconfirmed', 'resolved', 'fixed');

$scoreTester = new scoreTest();
r($scoreTester->fixKeyTest($actionList[0])) && p() && e('create');  // 将created改为create
r($scoreTester->fixKeyTest($actionList[1])) && p() && e('create');  // 将opened改为create
r($scoreTester->fixKeyTest($actionList[2])) && p() && e('close');   // 将closed改为close
r($scoreTester->fixKeyTest($actionList[3])) && p() && e('finish');  // 将finished改为finish
r($scoreTester->fixKeyTest($actionList[4])) && p() && e('confirm'); // 将bugconfirmed改为confirm
r($scoreTester->fixKeyTest($actionList[5])) && p() && e('resolve'); // 将resolved改为resolve
r($scoreTester->fixKeyTest($actionList[6])) && p() && e('fixed');   // 测试不存在的key
