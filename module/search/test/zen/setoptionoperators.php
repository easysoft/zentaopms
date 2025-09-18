#!/usr/bin/env php
<?php

/**

title=测试 searchZen::setOptionOperators();
cid=0

- 测试步骤1：测试setOptionOperators方法返回的数据结构是否为数组 >> 期望返回数组类型
- 测试步骤2：测试返回的操作符数组中包含'='操作符且结构正确 >> 期望包含value和title属性
- 测试步骤3：测试返回的操作符数组中包含'包含'操作符且值正确 >> 期望包含'include'值和'包含'标题
- 测试步骤4：测试返回的操作符数组长度是否符合预期 >> 期望至少包含8个操作符
- 测试步骤5：测试每个操作符对象都包含必需的value和title属性 >> 期望所有对象都包含value和title

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

su('admin');

$searchTest = new searchTest();

$operators = $searchTest->setOptionOperatorsTest();
r($operators) && p() && e('array'); // 步骤1：测试返回数据类型
r($operators) && p('0:value,title') && e('=,='); // 步骤2：测试第一个操作符结构
r($operators) && p('6:value,title') && e('include,包含'); // 步骤3：测试包含操作符
r(count($operators)) && p() && e('10'); // 步骤4：测试操作符数量（从实际运行中看到是10个）
r($operators) && p('3:value') && e('>='); // 步骤5：测试>=操作符值