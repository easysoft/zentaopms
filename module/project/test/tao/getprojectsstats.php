#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";

su('admin');

zdTable('project')->config('project')->gen(8);

/**

title=测试 projectTao::getProjectsStats()
timeout=0
cid=1

PHP Warning:  Undefined array key 1 in /opt/zentao/gitlab/zentaopms/test/lib/init.php on line 317
PHP Deprecated:  substr(): Passing null to parameter #1 ($string) of type string is deprecated in /opt/zentao/gitlab/zentaopms/test/lib/init.php on line 317
PHP Warning:  Undefined array key 1 in /opt/zentao/gitlab/zentaopms/test/lib/init.php on line 317
PHP Deprecated:  substr(): Passing null to parameter #1 ($string) of type string is deprecated in /opt/zentao/gitlab/zentaopms/test/lib/init.php on line 317
PHP Warning:  Undefined array key 1 in /opt/zentao/gitlab/zentaopms/test/lib/init.php on line 317
PHP Deprecated:  substr(): Passing null to parameter #1 ($string) of type string is deprecated in /opt/zentao/gitlab/zentaopms/test/lib/init.php on line 317
PHP Warning:  Undefined array key 1 in /opt/zentao/gitlab/zentaopms/test/lib/init.php on line 317
PHP Deprecated:  substr(): Passing null to parameter #1 ($string) of type string is deprecated in /opt/zentao/gitlab/zentaopms/test/lib/init.php on line 317
- 执行$projects['my'] @3

- 执行$projects['other'] @3

- 执行projects['my'][1]['wait'][0]模块的status方法 @wait

- 执行projects['my'][3]['doing'][0]模块的status方法 @doing

- 执行projects['other'][2]['wait'][0]模块的status方法 @wait

- 执行projects['other'][4]['doing'][0]模块的status方法 @doing

*/

global $tester;
$tester->loadModel('project');
$projects = $tester->project->getProjectsStats();

r(count($projects['my']))                    && p() && e('3');
r(count($projects['other']))                 && p() && e('3');
r($projects['my'][1]['wait'][0]->status)     && p() && e('wait');
r($projects['my'][3]['doing'][0]->status)    && p() && e('doing');
r($projects['other'][2]['wait'][0]->status)  && p() && e('wait');
r($projects['other'][4]['doing'][0]->status) && p() && e('doing');