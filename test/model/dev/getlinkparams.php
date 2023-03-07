#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/dev.class.php';

/**

title=测试 devModel::getLinkParams();
cid=1
pid=1

获取空数据         >> 0
获取字符串类型信息 >> index
获取数组类型信息   >> my

*/

global $tester;
$tester->loadModel('dev');
$links = array(
    'empty'  => '',
    'string' => '仪表盘|index|',
    'array'  => array('link' => '地盘|my|index|')
);
r($tester->dev->getLinkParams($links['empty']))  && p()    && e('0');      //获取空数据
r($tester->dev->getLinkParams($links['string'])) && p('1') && e('index'); //获取字符串类型信息
r($tester->dev->getLinkParams($links['array']))  && p('1') && e('my');    //获取数组类型信息
