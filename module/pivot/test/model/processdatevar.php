#!/usr/bin/env php
<?php
/**
title=测试 pivotModel->processDataVar();
cid=1
pid=1

测试空值        >> 0
测试非法值      >> 123123123
测试$MONDAY     >> 1
测试$SUNDAY     >> 1
测试$MONTHBEGIN >> 1
测试$MONTHEND   >> 1
*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/pivot.class.php';


$pivot = new pivotTest();

$varList = array('', '123123123', '$MONDAY', '$SUNDAY', '$MONTHBEGIN', '$MONTHEND');

$monday = date('Y-m-d', strtotime('last monday'));
$sunday = date('Y-m-d', strtotime('this sunday'));
$monthbegin = date('Y-m-01');
$monthend = date('Y-m-t');

r($pivot->processDateVar($varList[0])) && p('')        && e('0');                    //测试空值
r($pivot->processDateVar($varList[1])) && p('')        && e('123123123');             //测试非法值生成是正确
r($pivot->processDateVar($varList[2]) === $monday)     && p('') && e('1');   //测试$MONDAY生成是否正确
r($pivot->processDateVar($varList[3]) === $sunday)     && p('') && e('1');   //测试$SUNDAY生成是否正确
r($pivot->processDateVar($varList[4]) === $monthbegin) && p('') && e('1');   //测试$MONTHBEGIN生成是否正确
r($pivot->processDateVar($varList[5]) === $monthend)   && p('') && e('1');   //测试$MONTHEND生成是否正确
