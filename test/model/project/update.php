#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';
$db->switchDB();
su('admin');

/**

title=测试 projectModel::update();
cid=1
pid=1

正常更新项目的情况 >> 测试更新项目十
未关联产品的项目 >> 最少关联一个产品
更新项目名称为空时 >> 『项目名称』不能为空。
当计划完成为空时更新项目信息 >> 『计划开始』不能为空。
当计划完成小于计划开始时 >> 『计划完成』不能为空。
父项目的开始日期大于子项目的开始日期时 >> 『计划完成』应当大于『2022-07-01』。
项目开始、结束日期和子项目不符的情况 >> 项目集的最小开始日期：2022-02-15，项目的开始日期不能小于项目集的最小开始日期;项目集的最大完成日期：2022-04-22，项目的完成日期不能大于项目集的最大完成日期

*/

global $tester;
$tester->app->loadConfig('execution');

$project = new Project();

$data = array(
    'parent' => '0',
    'name' => '测试更新项目十',
    'begin' => '2020-10-10',
    'end' => '2022-06-01',
    'acl' => 'private',
    'budget' => '100',
    'budgetUnit' => 'CNY',
    'PM' => 'admin',
    'products' => array(1, 2, 3),
    'whitelist' => array('dev10', 'dev12')
);

$normalProject = $data;

$noProductProject = $data;
$noProductProject['products'] = array();

$emptyTitleProject = $data;
$emptyTitleProject['name'] = '';

$emptyBeginProject = $data;
$emptyBeginProject['begin'] = '';

$emptyEndProject = $data;
$emptyEndProject['end'] = '';

$beginGtEndProject = $data;
$beginGtEndProject['begin'] = '2022-07-01';

$beginLtParentProject = $data;
$beginLtParentProject['parent'] = '9';
$beginLtParentProject['begin']  = '2019-01-01';

r($project->update(10, $normalProject))        && p('name')                      && e('测试更新项目十');         // 正常更新项目的情况
r($project->update(10, $noProductProject))     && p('message:0')                 && e('最少关联一个产品');       // 未关联产品的项目
r($project->update(10, $emptyTitleProject))    && p('message[name]:0')           && e('『项目名称』不能为空。'); // 更新项目名称为空时
r($project->update(10, $emptyBeginProject))    && p('message[begin]:0')          && e('『计划开始』不能为空。'); // 当计划完成为空时更新项目信息
r($project->update(10, $emptyEndProject))      && p('message[end]:0')            && e('『计划完成』不能为空。'); // 当计划完成小于计划开始时
r($project->update(10, $beginGtEndProject))    && p('message[end]:0')            && e('『计划完成』应当大于『2022-07-01』。'); // 父项目的开始日期大于子项目的开始日期时
r($project->update(10, $beginLtParentProject)) && p('message:begin;message:end') && e('项目集的最小开始日期：2022-02-15，项目的开始日期不能小于项目集的最小开始日期;项目集的最大完成日期：2022-04-22，项目的完成日期不能大于项目集的最大完成日期'); // 项目开始、结束日期和子项目不符的情况
$db->restoreDB();