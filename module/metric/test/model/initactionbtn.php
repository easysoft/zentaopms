#!/usr/bin/env php
<?php
/**
title=initActionBtn
cid=1
pid=1
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
