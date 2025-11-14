#!/usr/bin/env php
<?php

/**

title=测试 blockModel::getModelType4Projects();
timeout=0
cid=15231

- 执行blockTest模块的getModelType4ProjectsTest方法，参数是array  @0
- 执行blockTest模块的getModelType4ProjectsTest方法，参数是array  @scrum
- 执行blockTest模块的getModelType4ProjectsTest方法，参数是array  @waterfall
- 执行blockTest模块的getModelType4ProjectsTest方法，参数是array  @all
- 执行blockTest模块的getModelType4ProjectsTest方法，参数是array  @scrum
- 执行blockTest模块的getModelType4ProjectsTest方法，参数是array  @scrum
- 执行blockTest模块的getModelType4ProjectsTest方法，参数是array  @waterfall
- 执行blockTest模块的getModelType4ProjectsTest方法，参数是array  @all
- 执行blockTest模块的getModelType4ProjectsTest方法，参数是array  @0
- 执行blockTest模块的getModelType4ProjectsTest方法，参数是array  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$table = zenData('project');
$table->id->range('1-20');
$table->name->range('Project1,Project2,Project3,Project4,Project5,Project6,Project7,Project8,Project9,Project10,Project11,Project12,Project13,Project14,Project15,Project16,Project17,Project18,Project19,Project20');
$table->type->range('project');
$table->model->range('scrum,scrum,scrum,kanban,kanban,kanban,agileplus,agileplus,agileplus,waterfall,waterfall,waterfall,waterfallplus,waterfallplus,waterfallplus,``,``,``,``,``');
$table->gen(20);

su('admin');

$blockTest = new blockModelTest();

r($blockTest->getModelType4ProjectsTest(array())) && p() && e('0');
r($blockTest->getModelType4ProjectsTest(array(1, 2, 3))) && p() && e('scrum');
r($blockTest->getModelType4ProjectsTest(array(10, 11, 12))) && p() && e('waterfall');
r($blockTest->getModelType4ProjectsTest(array(1, 10))) && p() && e('all');
r($blockTest->getModelType4ProjectsTest(array(4, 5, 6))) && p() && e('scrum');
r($blockTest->getModelType4ProjectsTest(array(7, 8, 9))) && p() && e('scrum');
r($blockTest->getModelType4ProjectsTest(array(13, 14, 15))) && p() && e('waterfall');
r($blockTest->getModelType4ProjectsTest(array(4, 13))) && p() && e('all');
r($blockTest->getModelType4ProjectsTest(array(999))) && p() && e('0');
r($blockTest->getModelType4ProjectsTest(array(16, 17, 18))) && p() && e('0');