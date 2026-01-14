#!/usr/bin/env php
<?php

/**

title=productpanModel->updateStatus();
timeout=0
cid=17653

- 修改状态为doing
 - 属性id @1
 - 属性title @计划1
 - 属性status @doing
- 修改状态为done
 - 属性id @2
 - 属性title @计划2
 - 属性status @done
- 修改状态为closed
 - 属性id @3
 - 属性title @计划3
 - 属性status @closed
- 修改状态为wait
 - 属性id @4
 - 属性title @计划4
 - 属性status @wait
- 修改状态为doing
 - 属性id @5
 - 属性title @计划5
 - 属性status @doing

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('productplan')->loadYaml('productplan')->gen(10);
$plan = new productPlan('admin');

r($plan->updateStatus(1, 'doing'))  && p('id,title,status') && e('1,计划1,doing');  //修改状态为doing
r($plan->updateStatus(2, 'done'))   && p('id,title,status') && e('2,计划2,done');   //修改状态为done
r($plan->updateStatus(3, 'closed')) && p('id,title,status') && e('3,计划3,closed'); //修改状态为closed
r($plan->updateStatus(4, 'wait'))   && p('id,title,status') && e('4,计划4,wait');   //修改状态为wait
r($plan->updateStatus(5, 'doing'))  && p('id,title,status') && e('5,计划5,doing');  //修改状态为doing
