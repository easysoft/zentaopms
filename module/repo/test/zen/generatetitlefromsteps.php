#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

/**

title=测试 repoZen::generateTitleFromSteps();
timeout=0
cid=0

- 步骤1：正常输入，从HTML步骤中提取标题 @打开页面
- 步骤2：空字符串输入返回空 @0
- 步骤3：空白字符输入返回空 @0
- 步骤4：带步骤前缀的行提取标题 @输入账号
- 步骤5：超长标题截断为100字符 @床前明月光疑是地上霜举头望明月低头思故乡床前明月光疑是地上霜举头望明月低头思故乡床前明月光疑是地上霜举头望明月低头思故乡床前明月光疑是地上霜举头望明月低头思故乡床前明月光疑是地上霜举头望明月低头思故乡...

*/

su('admin');
$test = new repoZenTest();

r($test->generateTitleFromStepsTest('<p>1. 打开页面</p><br><p>2. 点击按钮</p>')) && p() && e('打开页面'); // 步骤1：正常输入
r($test->generateTitleFromStepsTest('')) && p() && e('0'); // 步骤2：空字符串输入
r($test->generateTitleFromStepsTest('   ')) && p() && e('0'); // 步骤3：空白字符输入
r($test->generateTitleFromStepsTest('步骤1：输入账号<br>步骤2：输入密码')) && p() && e('输入账号'); // 步骤4：步骤前缀
r($test->generateTitleFromStepsTest('床前明月光疑是地上霜举头望明月低头思故乡床前明月光疑是地上霜举头望明月低头思故乡床前明月光疑是地上霜举头望明月低头思故乡床前明月光疑是地上霜举头望明月低头思故乡床前明月光疑是地上霜举头望明月低头思故乡床前明月光疑是地上霜举头望明月低头思故乡')) && p() && e('床前明月光疑是地上霜举头望明月低头思故乡床前明月光疑是地上霜举头望明月低头思故乡床前明月光疑是地上霜举头望明月低头思故乡床前明月光疑是地上霜举头望明月低头思故乡床前明月光疑是地上霜举头望明月低头思故乡...'); // 步骤5：超长标题截断
