#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getZentaoFields();
timeout=0
cid=0

- 执行convertTest模块的getZentaoFieldsTest方法，参数是'story'
 - 属性module @所属模块
 - 属性source @来源
 - 属性keywords @关键词
 - 属性mailto @抄送给
- 执行convertTest模块的getZentaoFieldsTest方法，参数是'bug'
 - 属性module @所属模块
 - 属性keywords @关键词
 - 属性type @Bug类型
 - 属性mailto @抄送给
- 执行convertTest模块的getZentaoFieldsTest方法，参数是'task'
 - 属性module @所属模块
 - 属性type @任务类型
 - 属性mailto @抄送给
- 执行convertTest模块的getZentaoFieldsTest方法，参数是'testcase'
 - 属性module @所属模块
 - 属性type @用例类型
 - 属性keywords @关键词
 - 属性mailto @抄送给
- 执行convertTest模块的getZentaoFieldsTest方法，参数是'notexist'  @0
- 执行convertTest模块的getZentaoFieldsTest方法，参数是'epic'
 - 属性module @所属模块
 - 属性source @来源
 - 属性keywords @关键词
 - 属性mailto @抄送给
- 执行convertTest模块的getZentaoFieldsTest方法，参数是'requirement'
 - 属性module @所属模块
 - 属性source @来源
 - 属性keywords @关键词
 - 属性mailto @抄送给
- 执行convertTest模块的getZentaoFieldsTest方法，参数是''  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$convertTest = new convertModelTest();

r($convertTest->getZentaoFieldsTest('story')) && p('module;source;keywords;mailto') && e('所属模块;来源;关键词;抄送给');
r($convertTest->getZentaoFieldsTest('bug')) && p('module;keywords;type;mailto') && e('所属模块;关键词;Bug类型;抄送给');
r($convertTest->getZentaoFieldsTest('task')) && p('module;type;mailto') && e('所属模块;任务类型;抄送给');
r($convertTest->getZentaoFieldsTest('testcase')) && p('module;type;keywords;mailto') && e('所属模块;用例类型;关键词;抄送给');
r($convertTest->getZentaoFieldsTest('notexist')) && p() && e('0');
r($convertTest->getZentaoFieldsTest('epic')) && p('module;source;keywords;mailto') && e('所属模块;来源;关键词;抄送给');
r($convertTest->getZentaoFieldsTest('requirement')) && p('module;source;keywords;mailto') && e('所属模块;来源;关键词;抄送给');
r($convertTest->getZentaoFieldsTest('')) && p() && e('0');