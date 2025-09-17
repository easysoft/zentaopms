#!/usr/bin/env php
<?php

/**

title=测试 searchZen::setOptionAndOr();
cid=0

- 测试步骤1：基本功能测试，验证返回数组结构 >> 期望返回包含and和or选项的数组
- 测试步骤2：验证and选项的值和标题 >> 期望value为'and'，title为'并且'
- 测试步骤3：验证or选项的值和标题 >> 期望value为'or'，title为'或者'
- 测试步骤4：验证返回数组的长度 >> 期望返回数组包含2个元素
- 测试步骤5：验证返回数组元素的数据类型 >> 期望每个元素都是stdClass对象

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

su('admin');

$searchTest = new searchTest();

r($searchTest->setOptionAndOrTest()) && p() && e('2'); // 步骤1：基本功能测试，验证返回数组长度
r($searchTest->setOptionAndOrTest()) && p('0:value') && e('and'); // 步骤2：验证第一个元素的value
r($searchTest->setOptionAndOrTest()) && p('0:title') && e('并且'); // 步骤3：验证第一个元素的title
r($searchTest->setOptionAndOrTest()) && p('1:value') && e('or'); // 步骤4：验证第二个元素的value
r($searchTest->setOptionAndOrTest()) && p('1:title') && e('或者'); // 步骤5：验证第二个元素的title