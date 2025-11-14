#!/usr/bin/env php
<?php

/**

title=测试 customZen::assignFieldListForSet();
timeout=0
cid=15931

- 执行customTest模块的assignFieldListForSetTest方法，参数是'story', 'priList', 'zh-cn', 'zh-cn' 属性fieldListType @array
- 执行customTest模块的assignFieldListForSetTest方法，参数是'story', 'priList', 'all', 'zh-cn' 属性lang2Set @all
- 执行customTest模块的assignFieldListForSetTest方法，参数是'task', 'typeList', 'en', 'zh-cn' 属性lang2Set @en
- 执行customTest模块的assignFieldListForSetTest方法，参数是'bug', 'statusList', 'zh-tw', 'zh-cn' 属性lang2Set @zh-tw
- 执行customTest模块的assignFieldListForSetTest方法，参数是'project', 'sourceList', '', 'zh-cn' 属性fieldListType @array
- 执行customTest模块的assignFieldListForSetTest方法，参数是'testmodule', 'testfield', 'zh-cn', 'zh-cn' 属性fieldListType @array
- 执行customTest模块的assignFieldListForSetTest方法，参数是'bug', 'priList', 'zh-cn', 'zh-cn' 属性dbFieldsType @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('lang')->loadYaml('assignfieldlistforset/lang', false, 2)->gen(100);

su('admin');

$customTest = new customZenTest();

r($customTest->assignFieldListForSetTest('story', 'priList', 'zh-cn', 'zh-cn')) && p('fieldListType') && e('array');
r($customTest->assignFieldListForSetTest('story', 'priList', 'all', 'zh-cn')) && p('lang2Set') && e('all');
r($customTest->assignFieldListForSetTest('task', 'typeList', 'en', 'zh-cn')) && p('lang2Set') && e('en');
r($customTest->assignFieldListForSetTest('bug', 'statusList', 'zh-tw', 'zh-cn')) && p('lang2Set') && e('zh-tw');
r($customTest->assignFieldListForSetTest('project', 'sourceList', '', 'zh-cn')) && p('fieldListType') && e('array');
r($customTest->assignFieldListForSetTest('testmodule', 'testfield', 'zh-cn', 'zh-cn')) && p('fieldListType') && e('array');
r($customTest->assignFieldListForSetTest('bug', 'priList', 'zh-cn', 'zh-cn')) && p('dbFieldsType') && e('array');