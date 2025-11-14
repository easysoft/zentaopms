#!/usr/bin/env php
<?php

/**

title=测试 miscModel::getRemind();
timeout=0
cid=17213

- 步骤1：默认配置返回0 @0
- 步骤2：showAnnual为空返回0 @0
- 步骤3：annualShowed已设置返回0 @0
- 步骤4：检查返回包含年度总结提醒的内容 @<h4>新增年度总结功能</h4><p>12.0版本后，新增年度总结功能，可以到『统计->年度总结』页面查看。 是否现在<a href="report-annualData.html" target="_blank" id="showAnnual" class="btn mini primary">查看</a></p>
- 步骤5：标记设置后再次调用返回年度总结内容 @<h4>新增年度总结功能</h4><p>12.0版本后，新增年度总结功能，可以到『统计->年度总结』页面查看。 是否现在<a href="report-annualData.html" target="_blank" id="showAnnual" class="btn mini primary">查看</a></p>

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/misc.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$miscTest = new miscTest();

// 4. 测试步骤1：默认配置状态下的返回值
r($miscTest->getRemindTest()) && p() && e('0'); // 步骤1：默认配置返回0

// 5. 测试步骤2：showAnnual为空时的行为
global $config;
$config->global->showAnnual = '';
$config->global->annualShowed = '';
r($miscTest->getRemindTest()) && p() && e('0'); // 步骤2：showAnnual为空返回0

// 6. 测试步骤3：showAnnual设置但annualShowed已设置的情况
$config->global->showAnnual = '1';
$config->global->annualShowed = '1';
r($miscTest->getRemindTest()) && p() && e('0'); // 步骤3：annualShowed已设置返回0

// 7. 测试步骤4：showAnnual设置且annualShowed为空的情况
$config->global->showAnnual = '1';
unset($config->global->annualShowed);
r($miscTest->getRemindTest()) && p() && e('<h4>新增年度总结功能</h4><p>12.0版本后，新增年度总结功能，可以到『统计->年度总结』页面查看。 是否现在<a href="report-annualData.html" target="_blank" id="showAnnual" class="btn mini primary">查看</a></p>'); // 步骤4：检查返回包含年度总结提醒的内容

// 8. 测试步骤5：验证设置annualShowed标记后再次调用的行为
r($miscTest->getRemindTest()) && p() && e('<h4>新增年度总结功能</h4><p>12.0版本后，新增年度总结功能，可以到『统计->年度总结』页面查看。 是否现在<a href="report-annualData.html" target="_blank" id="showAnnual" class="btn mini primary">查看</a></p>'); // 步骤5：标记设置后再次调用返回年度总结内容