#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/project.class.php';
$db->switchDB();
su('admin');

/**

title=测试 projectModel->create();
cid=1
pid=1

创建新项目 >> 测试新增项目一
项目名称为空时 >> 『项目名称』不能为空。
项目的完成时间为空 >> 『计划完成』不能为空。
项目的计划完成时间大于计划开始时间 >> 『计划完成』应当大于『2022-02-07』。
项目的开始时间为空 >> 项目集的最小开始日期：2022-02-07，项目的开始日期不能小于项目集的最小开始日期
项目的完成日期大于父项目的完成日期(需要实时更新日期) >> 项目集的最小开始日期：2022-02-07，项目的开始日期不能小于项目集的最小开始日期;

*/

global $tester;
$tester->app->loadConfig('execution');
$project = new Project();

$data = array(
    'parent'     => 1,
    'name'       => '测试新增项目一',
    'budget'     => '', 
    'budgetUnit' => 'CNY',
    'begin'      => '2022-02-07',
    'end'        => '2022-03-01',
    'desc'       => '测试项目描述',
    'acl'        => 'private',
    'whitelist'  => '',
    'PM'         => '',
    'products'   => array(1)
);

$normalProject = $data;

$emptyNameProject = $data;
$emptyNameProject['name'] = '';

$emptyBeginProject = $data;
$emptyBeginProject['begin'] = '';

$emptyEndProject = $data;
$emptyEndProject['end'] = '';

$beginGtEndProject = $data;
$beginGtEndProject['end'] = '2022-01-10';

$moreThanParent = $data;
$moreThanParent['parent'] = '1';
$moreThanParent['begin']  = '2018-01-01';

r($project->create($normalProject))     && p('name')                      && e('测试新增项目一');                       // 创建新项目
r($project->create($emptyNameProject))  && p('message[name]:0')           && e('『项目名称』不能为空。');               // 项目名称为空时
r($project->create($emptyEndProject))   && p('message[end]:0')            && e('『计划完成』不能为空。');               // 项目的完成时间为空
r($project->create($beginGtEndProject)) && p('message[end]:0')            && e('『计划完成』应当大于『2022-02-07』。'); // 项目的计划完成时间大于计划开始时间
r($project->create($emptyBeginProject)) && p('message:begin')             && e('项目集的最小开始日期：2022-02-07，项目的开始日期不能小于项目集的最小开始日期');  // 项目的开始时间为空
r($project->create($moreThanParent))    && p('message:begin;message:end') && e('项目集的最小开始日期：2022-02-07，项目的开始日期不能小于项目集的最小开始日期;'); // 项目的完成日期大于父项目的完成日期(需要实时更新日期)
$db->restoreDB();