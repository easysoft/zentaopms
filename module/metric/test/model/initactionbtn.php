#!/usr/bin/env php
<?php

/**

title=initActionBtn
timeout=0
cid=17135

- 测试拥有全部权限的操作按钮显示
 - 第0条的name属性 @edit
 - 第0条的disabled属性 @no
 - 第1条的name属性 @implement
 - 第1条的disabled属性 @no
 - 第2条的name属性 @delist
 - 第2条的disabled属性 @no
- 测试拥有部分权限的操作按钮显示
 - 第0条的name属性 @edit
 - 第0条的disabled属性 @no
 - 第1条的name属性 @implement
 - 第1条的disabled属性 @no
 - 第2条的name属性 @delist
 - 第2条的disabled属性 @yes
 - 第3条的name属性 @delete
- 测试没有任何权限的操作按钮显示
 - 第0条的name属性 @edit
 - 第0条的disabled属性 @yes
 - 第1条的name属性 @implement
 - 第1条的disabled属性 @yes
 - 第2条的name属性 @delist
 - 第2条的disabled属性 @yes
 - 第3条的name属性 @delete
- 测试没有任何权限的操作列显示 @0

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/calc.unittest.class.php';

$metric = new metricTest();

$action1 = array(array('name' => 'edit'), array('name' => 'implement'), array('name' => 'delist'));
$action2 = $action1;
$action2[] = array('name' => 'delete');

$priv1 = array('canEdit' => true, 'canImplement' => true, 'canDelist' => true);
$priv2 = $priv1;
$priv2['canDelist'] = false;

$priv3 = array('canEdit' => false, 'canImplement' => false, 'canDelist' => false);

$cols1 = array('actions' => array());
$cols2 = array('actions' => array());
$cols3 = array('actions' => array());

$metric1 = (object)array_merge(array('actions' => $action1), $priv1, array('builtin' => '1'));
$metric2 = (object)array_merge(array('actions' => $action2), $priv2, array('builtin' => '1'));
$metric3 = (object)array_merge(array('actions' => $action2), $priv3, array('builtin' => '1'));

list($cols1, $actions1) = $metric->initActionBtn($metric1, $cols1);
list($cols2, $actions2) = $metric->initActionBtn($metric2, $cols2);
list($cols3, $actions3) = $metric->initActionBtn($metric3, $cols3);

r($actions1) && p('0:name,disabled;1:name,disabled;2:name,disabled') && e('edit,no;implement,no;delist,no'); // 测试拥有全部权限的操作按钮显示
r($actions2) && p('0:name,disabled;1:name,disabled;2:name,disabled;3:name') && e('edit,no;implement,no;delist,yes;delete'); // 测试拥有部分权限的操作按钮显示
r($actions3) && p('0:name,disabled;1:name,disabled;2:name,disabled;3:name') && e('edit,yes;implement,yes;delist,yes;delete'); // 测试没有任何权限的操作按钮显示
r(empty($cols3)) && p('') && e('0'); // 测试没有任何权限的操作列显示