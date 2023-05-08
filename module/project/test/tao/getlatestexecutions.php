#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

su('admin');

zdTable('project')->config('project')->gen(8);

/**

title=测试 projectTao::getLatestExecutions();
timeout=0
cid=1

PHP Warning:  Undefined array key 1 in /opt/zentao/gitlab/zentaopms/test/lib/init.php on line 317
PHP Deprecated:  substr(): Passing null to parameter #1 ($string) of type string is deprecated in /opt/zentao/gitlab/zentaopms/test/lib/init.php on line 317
PHP Warning:  Undefined array key 1 in /opt/zentao/gitlab/zentaopms/test/lib/init.php on line 317
PHP Deprecated:  substr(): Passing null to parameter #1 ($string) of type string is deprecated in /opt/zentao/gitlab/zentaopms/test/lib/init.php on line 317
- 执行executions[3]模块的name方法 @项目3

- 执行executions[3]模块的code方法 @project3

*/

global $tester;
$tester->loadModel('project');
$executions = $tester->project->getLatestExecutions();

r($executions[3]->name) && p() && e('项目3');
r($executions[3]->code) && p() && e('project3');