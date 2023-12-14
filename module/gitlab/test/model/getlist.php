#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/gitlab.class.php';
su('admin');

/**

title=测试gitlabModel->gitList();
timeout=0
cid=1

- 获取GitLab列表第1条的id属性 @1
- 获取GitLab列表数量 @1

*/

$gitlab = new gitlabTest();

$orderBy    = 'id_desc';
$gitlabList = $gitlab->getList($orderBy);
r($gitlabList)        && p('1:id') && e('1');    // 获取GitLab列表
r(count($gitlabList)) && p('')     && e('1'); // 获取GitLab列表数量