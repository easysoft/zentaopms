#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/setting.class.php';
su('admin');

zdTable('config')->gen(7);

/**

title=测试 settingModel->getSysAndPersonalConfig();
timeout=0
cid=1

- 测试account值为system，可正常查询system的数据 @1
- 测试account值为admin，可正常查询system和admin的数据 @1
- 测试account值为dev，可正常查询system和dev的数据 @1
- 测试account值为空，可正常查询system的数据 @1

*/

$accountList = array('system', 'admin', 'dev', '');

$setting = new settingTest();

r($setting->getSysAndPersonalConfigTest($accountList[0])) && p() && e('1'); //测试account值为system，可正常查询system的数据
r($setting->getSysAndPersonalConfigTest($accountList[1])) && p() && e('1'); //测试account值为admin，可正常查询system和admin的数据
r($setting->getSysAndPersonalConfigTest($accountList[2])) && p() && e('1'); //测试account值为dev，可正常查询system和dev的数据
r($setting->getSysAndPersonalConfigTest($accountList[3])) && p() && e('1'); //测试account值为空，可正常查询system的数据