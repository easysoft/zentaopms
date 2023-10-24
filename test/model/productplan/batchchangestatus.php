#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

/**

title=productpanModel->batchChangeStatus();
cid=1
pid=1

将planiID=4，5的计划状态修改为doing，打印4旧状态 >> wait
将planiID=4, 5的计划状态修改为doing，打印4新状态 >> doing
将planiID=4, 5的计划状态修改为doing，打印5旧状态 >> wait
将planiID=4, 5的计划状态修改为doing，打印5新状态 >> doing
将planiID=4, 5的计划状态修改回wait， 打印4的当前状态 >> doing
将planiID=4, 5的计划状态修改回wait， 打印4的修改后的状态 >> wait
将planiID=4, 5的计划状态修改回wait， 打印5的当前状态 >> doing
将planiID=4, 5的计划状态修改回wait， 打印5的修改后的状态 >> wait

*/
$plan = new productPlan('admin');

$postID = array('planIDList' => array(4, 5));

$status = $plan->batchChangeStatus('doing', $postID);
$status4 = $status[4];
$status5 = $status[5];

$status = $plan->batchChangeStatus('wait', $postID);
$status6 = $status[4];
$status7 = $status[5];

r($status4) && p('0:old') && e('wait');  //将planiID=4，5的计划状态修改为doing，打印4旧状态
r($status4) && p('0:new') && e('doing'); //将planiID=4, 5的计划状态修改为doing，打印4新状态
r($status5) && p('0:old') && e('wait');  //将planiID=4, 5的计划状态修改为doing，打印5旧状态
r($status5) && p('0:new') && e('doing'); //将planiID=4, 5的计划状态修改为doing，打印5新状态
r($status6) && p('0:old') && e('doing'); //将planiID=4, 5的计划状态修改回wait， 打印4的当前状态
r($status6) && p('0:new') && e('wait');  //将planiID=4, 5的计划状态修改回wait， 打印4的修改后的状态
r($status7) && p('0:old') && e('doing'); //将planiID=4, 5的计划状态修改回wait， 打印5的当前状态
r($status6) && p('0:new') && e('wait');  //将planiID=4, 5的计划状态修改回wait， 打印5的修改后的状态
?>
