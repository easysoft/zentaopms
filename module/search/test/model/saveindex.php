#!/usr/bin/env php
<?php

/**

title=测试 searchModel::saveIndex();
timeout=0
cid=18308

- 测试步骤1：正常创建bug索引
 - 属性objectType @bug
 - 属性objectID @1
- 测试步骤2：正常创建task索引
 - 属性objectType @task
 - 属性objectID @1
- 测试步骤3：创建doc索引包含附加文件内容
 - 属性objectType @doc
 - 属性objectID @1
- 测试步骤4：测试有效的自定义对象类型
 - 属性objectType @validtype
 - 属性objectID @1
- 测试步骤5：测试中文内容的分词处理
 - 属性objectType @story
 - 属性objectID @1
- 测试步骤6：测试空内容字段
 - 属性objectType @product
 - 属性objectID @1
- 测试步骤7：测试特殊字符和HTML标签处理
 - 属性objectType @project
 - 属性objectID @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

// 清空搜索索引和字典表
zenData('searchindex')->gen(0);
zenData('searchdict')->gen(0);

su('admin');

$search = new searchTest();

r($search->saveIndexTest('bug', 1))     && p('objectType,objectID') && e('bug,1');     // 测试步骤1：正常创建bug索引
r($search->saveIndexTest('task', 1))    && p('objectType,objectID') && e('task,1');    // 测试步骤2：正常创建task索引
r($search->saveIndexTest('doc', 1))     && p('objectType,objectID') && e('doc,1');     // 测试步骤3：创建doc索引包含附加文件内容
r($search->saveIndexTest('validtype', 1)) && p('objectType,objectID') && e('validtype,1'); // 测试步骤4：测试有效的自定义对象类型
r($search->saveIndexTest('story', 1))   && p('objectType,objectID') && e('story,1');  // 测试步骤5：测试中文内容的分词处理
r($search->saveIndexTest('product', 1)) && p('objectType,objectID') && e('product,1'); // 测试步骤6：测试空内容字段
r($search->saveIndexTest('project', 1)) && p('objectType,objectID') && e('project,1'); // 测试步骤7：测试特殊字符和HTML标签处理