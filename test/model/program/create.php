#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/program.class.php';

/**

title=测试 programModel::create();
cid=1
pid=1

创建新项目集 >> 测试新增项目集一
项目集名称为空时 >> 『项目集名称』不能为空。
项目集的开始时间为空 >> 『计划开始』不能为空。
项目集的完成时间为空 >> 『计划完成』不能为空。
项目集的计划完成时间大于计划开始时间 >> 『计划完成』应当大于『2022-01-12』。
项目集的完成日期大于父项目集的完成日期(需要实时更新日期) >> 父项目集的开始日期：2022-01-16，开始日期不能小于父项目集的开始日期

*/

$program = new Program('admin');

$add_itemset = array('1', '2', '3', '4', '5', '6');

r($program->createData($add_itemset[0])) && p('name')                      && e('测试新增项目集一');                        // 创建新项目集
r($program->createData($add_itemset[1])) && p('message[name]:0')           && e('『项目集名称』不能为空。');                // 项目集名称为空时
r($program->createData($add_itemset[2])) && p('message[begin]:0')          && e('『计划开始』不能为空。');                  // 项目集的开始时间为空
r($program->createData($add_itemset[3])) && p('message[end]:0')            && e('『计划完成』不能为空。');                  // 项目集的完成时间为空
r($program->createData($add_itemset[4])) && p('message[end]:0')            && e('『计划完成』应当大于『2022-01-12』。');    // 项目集的计划完成时间大于计划开始时间
r($program->createData($add_itemset[5])) && p('message:begin;message:end') && e('父项目集的开始日期：2022-01-16，开始日期不能小于父项目集的开始日期'); // 项目集的完成日期大于父项目集的完成日期(需要实时更新日期)
system("./ztest init");