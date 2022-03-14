#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::getPairs();
cid=1
pid=1

获取项目集个数 >> 10
获取项目集id/name 的关联数组 >> 项目集1
获取项目集id/name 的关联数组 >> 项目集9

*/

$noItemsets = new Program('admin');

r($noItemsets->getCount()) && p()     && e('10');      // 获取项目集个数
r($noItemsets->getPairs()) && p('1')  && e('项目集1'); // 获取项目集id/name 的关联数组
r($noItemsets->getPairs()) && p('9')  && e('项目集9'); // 获取项目集id/name 的关联数组
r($noItemsets->getPairs()) && p('11') && e('');        // 获取不存在的项目集id/name 的关联数组