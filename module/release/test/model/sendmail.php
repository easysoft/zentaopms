#!/usr/bin/env php
<?php

/**

title=测试 releaseModel::sendmail();
timeout=0
cid=18011

- 步骤1：空releaseID @empty
- 步骤2：正常releaseID @success
- 步骤3：有stories的release @success
- 步骤4：有bugs的release @success
- 步骤5：不存在的releaseID @no_release

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('product')->gen(5);
zenData('release')->loadYaml('release')->gen(5);
zenData('story')->gen(10);
zenData('bug')->gen(10);
zenData('user')->gen(5);
su('admin');

$releaseIds = array(0, 1, 2, 3, 99);

$releaseTest = new releaseModelTest();

r($releaseTest->sendmailTest($releaseIds[0])) && p() && e('empty'); // 步骤1：空releaseID
r($releaseTest->sendmailTest($releaseIds[1])) && p() && e('success'); // 步骤2：正常releaseID
r($releaseTest->sendmailTest($releaseIds[2])) && p() && e('success'); // 步骤3：有stories的release
r($releaseTest->sendmailTest($releaseIds[3])) && p() && e('success'); // 步骤4：有bugs的release
r($releaseTest->sendmailTest($releaseIds[4])) && p() && e('no_release'); // 步骤5：不存在的releaseID