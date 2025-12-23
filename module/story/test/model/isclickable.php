#!/usr/bin/env php
<?php

/**

title=测试 storyModel->isclickable();
timeout=0
cid=18571

- 判断需求2是否有变更按钮操作权限 @1
- 判断需求2是否有评审按钮操作权限 @0
- 判断需求2是否有关闭按钮操作权限 @1
- 判断需求2是否有激活按钮操作权限 @0
- 判断需求2是否有指派按钮操作权限 @1
- 判断需求2是否有创建用例按钮操作权限 @1
- 判断需求2是否有批量创建按钮操作权限 @1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('story')->gen(5);

global $tester;
$storyModel = $tester->loadModel('story');
$story = $storyModel->getById(2);

r($storyModel::isClickable($story, 'change'))      && p() && e('1'); // 判断需求2是否有变更按钮操作权限
r($storyModel::isClickable($story, 'review'))      && p() && e('0'); // 判断需求2是否有评审按钮操作权限
r($storyModel::isClickable($story, 'close'))       && p() && e('1'); // 判断需求2是否有关闭按钮操作权限
r($storyModel::isClickable($story, 'activate'))    && p() && e('0'); // 判断需求2是否有激活按钮操作权限
r($storyModel::isClickable($story, 'assignto'))    && p() && e('1'); // 判断需求2是否有指派按钮操作权限
r($storyModel::isClickable($story, 'createcase'))  && p() && e('1'); // 判断需求2是否有创建用例按钮操作权限
r($storyModel::isClickable($story, 'batchcreate')) && p() && e('1'); // 判断需求2是否有批量创建按钮操作权限
