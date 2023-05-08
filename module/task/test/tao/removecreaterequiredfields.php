#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/task.class.php';

zdTable('project')->config('project_removecreaterequiredfields')->gen(9);

$tasks = array();
for($i = 0; $i < 18; $i ++)
{
    $tasks[$i] = new stdclass();
    $tasks[$i]->execution = ($i + 1) % 9;
    if(empty($tasks[$i]->execution)) $tasks[$i]->execution = 9;
    if($i < 9) $tasks[$i]->type = 'dev';
    if($i >= 9)  $tasks[$i]->type = 'test';
}

/**

title=taskModel->removeCreateRequiredFields();
timeout=0
cid=1

sed: can't read /home/tianshujie/repo/zentaopms/test/config/my.php: No such file or directory
- 测试任务执行为短期执行 阶段类型为mix 任务类型为dev 没有选择需求时候的必填项 @name,type,execution,story,estimate,estStarted,deadline,module

- 测试任务执行为短期执行 阶段类型为mix 任务类型为dev 有选择需求时候的必填项 @name,type,execution,story,estimate,estStarted,deadline,module

- 测试任务执行为短期执行 阶段类型为request 任务类型为dev 没有选择需求时候的必填项 @name,type,execution,estimate,estStarted,deadline,module

- 测试任务执行为短期执行 阶段类型为request 任务类型为dev 有选择需求时候的必填项 @name,type,execution,estimate,estStarted,deadline,module

- 测试任务执行为短期执行 阶段类型为review 任务类型为dev 没有选择需求时候的必填项 @name,type,execution,estimate,estStarted,deadline,module

- 测试任务执行为短期执行 阶段类型为review 任务类型为dev 有选择需求时候的必填项 @name,type,execution,estimate,estStarted,deadline,module

- 测试任务执行为长期执行 阶段类型为mix 任务类型为dev 没有选择需求时候的必填项 @name,type,execution,story,estimate,estStarted,deadline,module

- 测试任务执行为长期执行 阶段类型为mix 任务类型为dev 有选择需求时候的必填项 @name,type,execution,story,estimate,estStarted,deadline,module

- 测试任务执行为长期执行 阶段类型为request 任务类型为dev 没有选择需求时候的必填项 @name,type,execution,estimate,estStarted,deadline,module

- 测试任务执行为长期执行 阶段类型为request 任务类型为dev 有选择需求时候的必填项 @name,type,execution,estimate,estStarted,deadline,module

- 测试任务执行为长期执行 阶段类型为review 任务类型为dev 没有选择需求时候的必填项 @name,type,execution,estimate,estStarted,deadline,module

- 测试任务执行为长期执行 阶段类型为review 任务类型为dev 有选择需求时候的必填项 @name,type,execution,estimate,estStarted,deadline,module

- 测试任务执行为运维执行 阶段类型为mix 任务类型为dev 没有选择需求时候的必填项 @name,type,execution,estimate,estStarted,deadline,module

- 测试任务执行为运维执行 阶段类型为mix 任务类型为dev 有选择需求时候的必填项 @name,type,execution,estimate,estStarted,deadline,module

- 测试任务执行为运维执行 阶段类型为request 任务类型为dev 没有选择需求时候的必填项 @name,type,execution,estimate,estStarted,deadline,module

- 测试任务执行为运维执行 阶段类型为request 任务类型为dev 有选择需求时候的必填项 @name,type,execution,estimate,estStarted,deadline,module

- 测试任务执行为运维执行 阶段类型为review 任务类型为dev 没有选择需求时候的必填项 @name,type,execution,estimate,estStarted,deadline,module

- 测试任务执行为运维执行 阶段类型为review 任务类型为dev 有选择需求时候的必填项 @name,type,execution,estimate,estStarted,deadline,module

- 测试任务执行为短期执行 阶段类型为mix 任务类型为test 没有选择需求时候的必填项 @name,type,execution,story,estimate,estStarted,deadline,module

- 测试任务执行为短期执行 阶段类型为mix 任务类型为test 有选择需求时候的必填项 @name,type,execution

- 测试任务执行为短期执行 阶段类型为request 任务类型为test 没有选择需求时候的必填项 @name,type,execution,estimate,estStarted,deadline,module

- 测试任务执行为短期执行 阶段类型为request 任务类型为test 有选择需求时候的必填项 @name,type,execution

- 测试任务执行为短期执行 阶段类型为review 任务类型为test 没有选择需求时候的必填项 @name,type,execution,estimate,estStarted,deadline,module

- 测试任务执行为短期执行 阶段类型为review 任务类型为test 有选择需求时候的必填项 @name,type,execution

- 测试任务执行为长期执行 阶段类型为mix 任务类型为test 没有选择需求时候的必填项 @name,type,execution,story,estimate,estStarted,deadline,module

- 测试任务执行为长期执行 阶段类型为mix 任务类型为test 有选择需求时候的必填项 @name,type,execution

- 测试任务执行为长期执行 阶段类型为request 任务类型为test 没有选择需求时候的必填项 @name,type,execution,estimate,estStarted,deadline,module

- 测试任务执行为长期执行 阶段类型为request 任务类型为test 有选择需求时候的必填项 @name,type,execution

- 测试任务执行为长期执行 阶段类型为review 任务类型为test 没有选择需求时候的必填项 @name,type,execution,estimate,estStarted,deadline,module

- 测试任务执行为长期执行 阶段类型为review 任务类型为test 有选择需求时候的必填项 @name,type,execution

- 测试任务执行为运维执行 阶段类型为mix 任务类型为test 没有选择需求时候的必填项 @name,type,execution,estimate,estStarted,deadline,module

- 测试任务执行为运维执行 阶段类型为mix 任务类型为test 有选择需求时候的必填项 @name,type,execution

- 测试任务执行为运维执行 阶段类型为request 任务类型为test 没有选择需求时候的必填项 @name,type,execution,estimate,estStarted,deadline,module

- 测试任务执行为运维执行 阶段类型为request 任务类型为test 有选择需求时候的必填项 @name,type,execution

- 测试任务执行为运维执行 阶段类型为review 任务类型为test 没有选择需求时候的必填项 @name,type,execution,estimate,estStarted,deadline,module

- 测试任务执行为运维执行 阶段类型为review 任务类型为test 有选择需求时候的必填项 @name,type,execution

*/

$selectTestStoryList = array(false, true);

$task = new taskTest();

r($task->removeCreateRequiredFieldsTest($tasks[0], $selectTestStoryList[0]))   && p() && e('name,type,execution,story,estimate,estStarted,deadline,module'); // 测试任务执行为短期执行 阶段类型为mix 任务类型为dev 没有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[0], $selectTestStoryList[1]))   && p() && e('name,type,execution,story,estimate,estStarted,deadline,module'); // 测试任务执行为短期执行 阶段类型为mix 任务类型为dev 有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[1], $selectTestStoryList[0]))   && p() && e('name,type,execution,estimate,estStarted,deadline,module');       // 测试任务执行为短期执行 阶段类型为request 任务类型为dev 没有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[1], $selectTestStoryList[1]))   && p() && e('name,type,execution,estimate,estStarted,deadline,module');       // 测试任务执行为短期执行 阶段类型为request 任务类型为dev 有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[2], $selectTestStoryList[0]))   && p() && e('name,type,execution,estimate,estStarted,deadline,module');       // 测试任务执行为短期执行 阶段类型为review 任务类型为dev 没有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[2], $selectTestStoryList[1]))   && p() && e('name,type,execution,estimate,estStarted,deadline,module');       // 测试任务执行为短期执行 阶段类型为review 任务类型为dev 有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[3], $selectTestStoryList[0]))   && p() && e('name,type,execution,story,estimate,estStarted,deadline,module'); // 测试任务执行为长期执行 阶段类型为mix 任务类型为dev 没有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[3], $selectTestStoryList[1]))   && p() && e('name,type,execution,story,estimate,estStarted,deadline,module'); // 测试任务执行为长期执行 阶段类型为mix 任务类型为dev 有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[4], $selectTestStoryList[0]))   && p() && e('name,type,execution,estimate,estStarted,deadline,module');       // 测试任务执行为长期执行 阶段类型为request 任务类型为dev 没有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[4], $selectTestStoryList[1]))   && p() && e('name,type,execution,estimate,estStarted,deadline,module');       // 测试任务执行为长期执行 阶段类型为request 任务类型为dev 有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[5], $selectTestStoryList[0]))   && p() && e('name,type,execution,estimate,estStarted,deadline,module');       // 测试任务执行为长期执行 阶段类型为review 任务类型为dev 没有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[5], $selectTestStoryList[1]))   && p() && e('name,type,execution,estimate,estStarted,deadline,module');       // 测试任务执行为长期执行 阶段类型为review 任务类型为dev 有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[6], $selectTestStoryList[0]))   && p() && e('name,type,execution,estimate,estStarted,deadline,module');       // 测试任务执行为运维执行 阶段类型为mix 任务类型为dev 没有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[6], $selectTestStoryList[1]))   && p() && e('name,type,execution,estimate,estStarted,deadline,module');       // 测试任务执行为运维执行 阶段类型为mix 任务类型为dev 有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[7], $selectTestStoryList[0]))   && p() && e('name,type,execution,estimate,estStarted,deadline,module');       // 测试任务执行为运维执行 阶段类型为request 任务类型为dev 没有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[7], $selectTestStoryList[1]))   && p() && e('name,type,execution,estimate,estStarted,deadline,module');       // 测试任务执行为运维执行 阶段类型为request 任务类型为dev 有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[8], $selectTestStoryList[0]))   && p() && e('name,type,execution,estimate,estStarted,deadline,module');       // 测试任务执行为运维执行 阶段类型为review 任务类型为dev 没有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[8], $selectTestStoryList[1]))   && p() && e('name,type,execution,estimate,estStarted,deadline,module');       // 测试任务执行为运维执行 阶段类型为review 任务类型为dev 有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[9], $selectTestStoryList[0]))   && p() && e('name,type,execution,story,estimate,estStarted,deadline,module'); // 测试任务执行为短期执行 阶段类型为mix 任务类型为test 没有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[9], $selectTestStoryList[1]))   && p() && e('name,type,execution');                                           // 测试任务执行为短期执行 阶段类型为mix 任务类型为test 有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[10], $selectTestStoryList[0]))  && p() && e('name,type,execution,estimate,estStarted,deadline,module');       // 测试任务执行为短期执行 阶段类型为request 任务类型为test 没有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[10], $selectTestStoryList[1]))  && p() && e('name,type,execution');                                           // 测试任务执行为短期执行 阶段类型为request 任务类型为test 有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[11], $selectTestStoryList[0]))  && p() && e('name,type,execution,estimate,estStarted,deadline,module');       // 测试任务执行为短期执行 阶段类型为review 任务类型为test 没有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[11], $selectTestStoryList[1]))  && p() && e('name,type,execution');                                           // 测试任务执行为短期执行 阶段类型为review 任务类型为test 有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[12], $selectTestStoryList[0]))  && p() && e('name,type,execution,story,estimate,estStarted,deadline,module'); // 测试任务执行为长期执行 阶段类型为mix 任务类型为test 没有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[12], $selectTestStoryList[1]))  && p() && e('name,type,execution');                                           // 测试任务执行为长期执行 阶段类型为mix 任务类型为test 有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[13], $selectTestStoryList[0]))  && p() && e('name,type,execution,estimate,estStarted,deadline,module');       // 测试任务执行为长期执行 阶段类型为request 任务类型为test 没有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[13], $selectTestStoryList[1]))  && p() && e('name,type,execution');                                           // 测试任务执行为长期执行 阶段类型为request 任务类型为test 有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[14], $selectTestStoryList[0]))  && p() && e('name,type,execution,estimate,estStarted,deadline,module');       // 测试任务执行为长期执行 阶段类型为review 任务类型为test 没有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[14], $selectTestStoryList[1]))  && p() && e('name,type,execution');                                           // 测试任务执行为长期执行 阶段类型为review 任务类型为test 有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[15], $selectTestStoryList[0]))  && p() && e('name,type,execution,estimate,estStarted,deadline,module');       // 测试任务执行为运维执行 阶段类型为mix 任务类型为test 没有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[15], $selectTestStoryList[1]))  && p() && e('name,type,execution');                                           // 测试任务执行为运维执行 阶段类型为mix 任务类型为test 有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[16], $selectTestStoryList[0]))  && p() && e('name,type,execution,estimate,estStarted,deadline,module');       // 测试任务执行为运维执行 阶段类型为request 任务类型为test 没有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[16], $selectTestStoryList[1]))  && p() && e('name,type,execution');                                           // 测试任务执行为运维执行 阶段类型为request 任务类型为test 有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[17], $selectTestStoryList[0]))  && p() && e('name,type,execution,estimate,estStarted,deadline,module');       // 测试任务执行为运维执行 阶段类型为review 任务类型为test 没有选择需求时候的必填项
r($task->removeCreateRequiredFieldsTest($tasks[17], $selectTestStoryList[1]))  && p() && e('name,type,execution');                                           // 测试任务执行为运维执行 阶段类型为review 任务类型为test 有选择需求时候的必填项