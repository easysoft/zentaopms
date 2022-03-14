#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::getParentPairs();
cid=1
pid=1

 >> 11

*/

$ParentProjectSet = new Program('admin');

r($ParentProjectSet->getCount1())       && p()    && e('11'); //
r($ParentProjectSet->getParentPairs()) && p('1') && e('');  // 获取父项目集的id/name关联数组