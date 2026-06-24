#!/usr/bin/env php
<?php

/**

title=测试 testreportModel->buildTestreportSearchForm();
timeout=0
cid=0

- 步骤1:缓存模式,检查module属性module @testreport
- 步骤2:缓存模式,检查product字段存在属性hasProductField @1
- 步骤3:缓存模式,检查project字段存在属性hasProjectField @1
- 步骤4:缓存模式,检查execution字段存在属性hasExecutionField @1
- 步骤5:缓存模式,检查title参数存在属性hasTitleParam @1
- 步骤6:缓存模式,objectType为execution,检查module属性module @testreport
- 步骤7:缓存模式,objectType为project,检查module属性module @testreport
- 步骤8:缓存模式,objectType为execution,检查product字段存在属性hasProductField @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$testreport = new testreportModelTest();

r($testreport->buildTestreportSearchFormTest('product', 0, ''))  && p('module')            && e('testreport'); // 步骤1:缓存模式,检查module
r($testreport->buildTestreportSearchFormTest('product', 0, ''))  && p('hasProductField')   && e('1');          // 步骤2:缓存模式,检查product字段存在
r($testreport->buildTestreportSearchFormTest('product', 0, ''))  && p('hasProjectField')   && e('1');          // 步骤3:缓存模式,检查project字段存在
r($testreport->buildTestreportSearchFormTest('product', 0, ''))  && p('hasExecutionField') && e('1');          // 步骤4:缓存模式,检查execution字段存在
r($testreport->buildTestreportSearchFormTest('product', 0, ''))  && p('hasTitleParam')     && e('1');          // 步骤5:缓存模式,检查title参数存在
r($testreport->buildTestreportSearchFormTest('execution', 0, '')) && p('module')           && e('testreport'); // 步骤6:缓存模式,objectType为execution,检查module
r($testreport->buildTestreportSearchFormTest('project', 0, ''))  && p('module')            && e('testreport'); // 步骤7:缓存模式,objectType为project,检查module
r($testreport->buildTestreportSearchFormTest('execution', 0, '')) && p('hasProductField')  && e('1');          // 步骤8:缓存模式,objectType为execution,检查product字段存在