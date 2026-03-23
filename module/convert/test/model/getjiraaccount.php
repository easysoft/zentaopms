#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getJiraAccount();
timeout=0
cid=15772

- 步骤1：空userKey输入 @0
- 步骤2：有效JIRAUSER前缀ID @0
- 步骤3：不存在的JIRAUSER前缀ID @0
- 步骤4：直接用户账号名 @0
- 步骤5：不存在的用户账号 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$convertTest = new convertModelTest();

r($convertTest->getJiraAccountTest(''))             && p() && e('0'); // 步骤1：空userKey输入
r($convertTest->getJiraAccountTest('JIRAUSER3'))    && p() && e('0'); // 步骤2：有效JIRAUSER前缀ID
r($convertTest->getJiraAccountTest('JIRAUSER999'))  && p() && e('0'); // 步骤3：不存在的JIRAUSER前缀ID
r($convertTest->getJiraAccountTest('testuser'))     && p() && e('0'); // 步骤4：直接用户账号名
r($convertTest->getJiraAccountTest('nonexistuser')) && p() && e('0'); // 步骤5：不存在的用户账号
