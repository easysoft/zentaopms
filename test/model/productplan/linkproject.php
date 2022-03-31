#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/productplan.class.php';

/**

title=prodeutplanModel->linkProject();
cid=1
pid=1

项目ID=3关联计划ID为11，12的产品计划 >> 0

*/

$plan = new productPlan('admin');

r($plan->linkProject(13, array(11 ,12))) && p() && e('0'); //项目ID=3关联计划ID为11，12的产品计划
?>