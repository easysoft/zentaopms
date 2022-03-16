#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::getProductPairs();
cid=1
pid=1

根据项目或项目集ID获取关联产品详情 >> 已关闭的正常产品24
获取不存在的项目或项目集 >> Not Found
根据项目或项目集指派情况获取关联产品详情 >> 正常产品1
根据项目或项目集状态获取关联产品详情 >> 多平台产品90
查看ID=1，有指派，所有状态的关联产品数量 >> 9
查看ID=1，有指派，未关闭的关联产品数量 >> 5

*/

$getItemsets = new Program('admin');

$t_program = array('1', '1000', 'all', 'all', '1', 'assign', 'all', '1', 'assign', 'noclosed');

r($getItemsets->getProductPairsByID($t_program[0]))     && p('24')      && e('已关闭的正常产品24'); //根据项目或项目集ID获取关联产品详情
r($getItemsets->getProductPairsByID($t_program[1]))     && p('message') && e('Not Found');          //获取不存在的项目或项目集
r($getItemsets->getProductPairsByMod($t_program[2]))    && p('1')       && e('正常产品1');          //根据项目或项目集指派情况获取关联产品详情
r($getItemsets->getProductPairsByStatus($t_program[3])) && p('90')      && e('多平台产品90');       //根据项目或项目集状态获取关联产品详情
r($getItemsets->getCount2($t_program[4], $t_program[5], $t_program[6])) && p() && e('9');           //查看ID=1，有指派，所有状态的关联产品数量
r($getItemsets->getCount2($t_program[7], $t_program[8], $t_program[9])) && p() && e('5');           //查看ID=1，有指派，未关闭的关联产品数量