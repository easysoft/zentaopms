#!/usr/bin/env php
<?php

/**

title=initActionBtn
timeout=0
cid=1

- 执行metric模块的initActionBtn方法，参数是$metric1 
 - 第0条的name属性 @edit
 - 第0条的disabled属性 @no
 - 第1条的name属性 @implement
 - 第1条的disabled属性 @no
 - 第2条的name属性 @delist
 - 第2条的disabled属性 @no
- 执行metric模块的initActionBtn方法，参数是$metric2 
 - 第0条的name属性 @edit
 - 第0条的disabled属性 @no
 - 第1条的name属性 @implement
 - 第1条的disabled属性 @no
 - 第2条的name属性 @delist
 - 第2条的disabled属性 @yes
 - 第3条的name属性 @delete

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/calc.class.php';

$metric = new metricTest();

$action1 = array(array('name' => 'edit'), array('name' => 'implement'), array('name' => 'delist'));
$action2 = $action1;
$action2[] = array('name' => 'delete');

$priv1 = array('canEdit' => true, 'canImplement' => true, 'canDelist' => true);
$priv2 = $priv1;
$priv2['canDelist'] = false;

$metric1 = (object)array_merge(array('actions' => $action1), $priv1);
$metric2 = (object)array_merge(array('actions' => $action2), $priv2);
r($metric->initActionBtn($metric1)) && p('0:name,disabled;1:name,disabled;2:name,disabled') && e('edit,no;implement,no;delist,no');
r($metric->initActionBtn($metric2)) && p('0:name,disabled;1:name,disabled;2:name,disabled;3:name') && e('edit,no;implement,no;delist,yes;delete');