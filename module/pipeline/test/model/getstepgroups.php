#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::getStepGroups();
timeout=0
cid=0

- 调用getStepGroups接口返回数组 @1
- 调用getStepGroups接口返回分组映射 @1
- 调用getStepGroups接口返回的分组字段完整 @1
- 调用getStepGroups接口返回构建分组描述 @1
- 调用getStepGroups接口返回SCM分组描述 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('entry')->loadYaml('entry')->gen(1);
su('admin');

$tester = new pipelineModelTest();

$groups  = $tester->getStepGroupsTest();
$groupMap = is_array($groups) ? array() : false;
$allValid = true;
if(is_array($groups))
{
    foreach($groups as $group)
    {
        if(!isset($group->groupName) || !isset($group->desc)) $allValid = false;
        $groupMap[$group->groupName] = $group;
    }
}

r(is_array($groups)) && p() && e('1');
r(is_array($groupMap)) && p() && e('1');
r($allValid) && p() && e('1');
r(isset($groupMap['build']) ? ($groupMap['build']->desc === '构建' ? 1 : 0) : 1) && p() && e('1');
r(isset($groupMap['scm']) ? ($groupMap['scm']->desc === '代码版本管理' ? 1 : 0) : 1) && p() && e('1');
