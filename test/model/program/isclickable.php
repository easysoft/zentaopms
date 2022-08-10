#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::createStakeholder();
cid=1
pid=1

传入项目集1，动作为closed，判断是否可点击 >> 1
传入项目集1，动作为activate，判断是否可点击 >> 0
传入项目集1，动作为suspend，判断是否可点击 >> 1
传入项目集2，动作为closed，判断是否可点击 >> 1
传入项目集2，动作为activate，判断是否可点击 >> 0
传入项目集2，动作为activate，判断是否可点击 >> 1

*/

global $tester;
$tester->loadModel('program');

$program1 = $tester->program->getById(1);
$program2 = $tester->program->getById(2);

r($tester->program->isClickable($program1, 'close'))    && p('') && e('1'); // 传入项目集1，动作为closed，判断是否可点击
r($tester->program->isClickable($program1, 'activate')) && p('') && e('0'); // 传入项目集1，动作为activate，判断是否可点击
r($tester->program->isClickable($program1, 'suspend'))  && p('') && e('1'); // 传入项目集1，动作为suspend，判断是否可点击
r($tester->program->isClickable($program2, 'close'))    && p('') && e('1'); // 传入项目集2，动作为closed，判断是否可点击
r($tester->program->isClickable($program2, 'activate')) && p('') && e('0'); // 传入项目集2，动作为activate，判断是否可点击
r($tester->program->isClickable($program2, 'suspend'))  && p('') && e('1'); // 传入项目集2，动作为activate，判断是否可点击