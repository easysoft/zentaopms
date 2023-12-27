#!/usr/bin/env php
<?php

/**

title=测试 solutionModel->create();
timeout=0
cid=1

- 没有选择应用 @0
- 正确选择了应用属性components @{"git":{"id":58,"name":"gitlab","alias":"GitLab","chart":"gitlab","app_version":"15.3.4","version":"2023.10.901","logo":"https:\/\/img.qucheng.com\/app\/g\/gitlab-icon.svg","status":"waiting"}}
- 选择两个应用属性components @{"git":{"id":58,"name":"gitlab","alias":"GitLab","chart":"gitlab","app_version":"15.3.4","version":"2023.10.901","logo":"https:\/\/img.qucheng.com\/app\/g\/gitlab-icon.svg","status":"waiting"},"ci":{"id":59,"name":"jenkins","alias":"Jenkins","chart":"jenkins","app_version":"2.401.3","version":"2023.10.901","logo":"https:\/\/img.qucheng.com\/app\/j\/jenkins-icon.svg","status":"waiting"}}

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/solution.class.php';

zdTable('user')->gen(5);
zdTable('solution')->config('solution')->gen(0);
zdTable('instance')->config('instance')->gen(0);

$params = array(
    'git'      => '',
    'ci'       => '',
    'analysis' => '',
    'artifact' => ''
);

$solutionModel = new solutionTest();
r($solutionModel->createTest($params)) && p() && e('0'); // 没有选择应用

$params['git'] = 'gitlab';
r($solutionModel->createTest($params)) && p('components', '|') && e('{"git":{"id":58,"name":"gitlab","alias":"GitLab","chart":"gitlab","app_version":"15.3.4","version":"2023.10.901","logo":"https:\/\/img.qucheng.com\/app\/g\/gitlab-icon.svg","status":"waiting"}}'); // 正确选择了应用

$params['ci'] = 'jenkins';
r($solutionModel->createTest($params)) && p('components', '|') && e('{"git":{"id":58,"name":"gitlab","alias":"GitLab","chart":"gitlab","app_version":"15.3.4","version":"2023.10.901","logo":"https:\/\/img.qucheng.com\/app\/g\/gitlab-icon.svg","status":"waiting"},"ci":{"id":59,"name":"jenkins","alias":"Jenkins","chart":"jenkins","app_version":"2.401.3","version":"2023.10.901","logo":"https:\/\/img.qucheng.com\/app\/j\/jenkins-icon.svg","status":"waiting"}}'); // 选择两个应用