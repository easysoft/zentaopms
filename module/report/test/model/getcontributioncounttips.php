#!/usr/bin/env php
<?php

/**

title=测试 reportModel::getContributionCountTips();
timeout=0
cid=0

- 执行reportTest模块的getContributionCountTipsTest方法，参数是'company'  @全公司在已选年份的贡献数据，包含：<br>任务：创建、完成、关闭、取消、指派<br>研发需求：创建、评审、关闭、指派<br>用户需求：创建、评审、关闭、指派<br>业务需求：创建、评审、关闭、指派<br>Bug：创建、解决、关闭、指派<br>用例：创建<br>测试单：关闭<br>文档：创建、编辑<br>
- 执行reportTest模块的getContributionCountTipsTest方法，参数是'dept'  @已选部门的用户在已选年份的贡献数据，包含：<br>任务：创建、完成、关闭、取消、指派<br>研发需求：创建、评审、关闭、指派<br>用户需求：创建、评审、关闭、指派<br>业务需求：创建、评审、关闭、指派<br>Bug：创建、解决、关闭、指派<br>用例：创建<br>测试单：关闭<br>文档：创建、编辑<br>
- 执行reportTest模块的getContributionCountTipsTest方法，参数是'user'  @已选用户在已选年份的贡献数据，包含：<br>任务：创建、完成、关闭、取消、指派<br>研发需求：创建、评审、关闭、指派<br>用户需求：创建、评审、关闭、指派<br>业务需求：创建、评审、关闭、指派<br>Bug：创建、解决、关闭、指派<br>用例：创建<br>测试单：关闭<br>文档：创建、编辑<br>
- 执行reportTest模块的getContributionCountTipsTest方法，参数是'company'  @~包含：~
- 执行reportTest模块的getContributionCountTipsTest方法，参数是'dept'  @~文档：创建、编辑~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/report.unittest.class.php';

su('admin');

$reportTest = new reportTest();

r($reportTest->getContributionCountTipsTest('company')) && p() && e('全公司在已选年份的贡献数据，包含：<br>任务：创建、完成、关闭、取消、指派<br>研发需求：创建、评审、关闭、指派<br>用户需求：创建、评审、关闭、指派<br>业务需求：创建、评审、关闭、指派<br>Bug：创建、解决、关闭、指派<br>用例：创建<br>测试单：关闭<br>文档：创建、编辑<br>');
r($reportTest->getContributionCountTipsTest('dept')) && p() && e('已选部门的用户在已选年份的贡献数据，包含：<br>任务：创建、完成、关闭、取消、指派<br>研发需求：创建、评审、关闭、指派<br>用户需求：创建、评审、关闭、指派<br>业务需求：创建、评审、关闭、指派<br>Bug：创建、解决、关闭、指派<br>用例：创建<br>测试单：关闭<br>文档：创建、编辑<br>');
r($reportTest->getContributionCountTipsTest('user')) && p() && e('已选用户在已选年份的贡献数据，包含：<br>任务：创建、完成、关闭、取消、指派<br>研发需求：创建、评审、关闭、指派<br>用户需求：创建、评审、关闭、指派<br>业务需求：创建、评审、关闭、指派<br>Bug：创建、解决、关闭、指派<br>用例：创建<br>测试单：关闭<br>文档：创建、编辑<br>');
r($reportTest->getContributionCountTipsTest('company')) && p() && e('~包含：~');
r($reportTest->getContributionCountTipsTest('dept')) && p() && e('~文档：创建、编辑~');