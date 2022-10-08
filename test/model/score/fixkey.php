#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/score.class.php';
su('admin');

/**

title=测试 scoreModel->fixKey();
cid=1
pid=1

action为created >> create
action为opened >> create
action为bugconfirmed >> confirmBug
action为fixed, 不存在于strings中 >> fixed

*/

$actionList = array('created', 'opened', 'bugconfirmed', 'fixed');

$score = new scoreTest();

r($score->fixKeyTest($actionList[0])) && p('') && e('create');      // action为created
r($score->fixKeyTest($actionList[1])) && p('') && e('create');      // action为opened
r($score->fixKeyTest($actionList[2])) && p('') && e('confirmBug');  // action为bugconfirmed
r($score->fixKeyTest($actionList[3])) && p('') && e('fixed');       // action为fixed, 不存在于strings中