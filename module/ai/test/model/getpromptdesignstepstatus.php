#!/usr/bin/env php
<?php

/**

title=测试 aiModel::getPromptDesignStepStatus();
timeout=0
cid=15057

- 步骤1：新建定时智能体基础信息页，设置提词不可点 属性setprompt @disabled
- 步骤2：新建定时智能体基础信息页，当前步骤为基础信息 属性basicinfo @current
- 步骤3：新建定时智能体基础信息页，预览不可点 属性preview @disabled
- 步骤4：已保存定时智能体基础信息页，设置提词可点 属性setprompt @clickable
- 步骤5：已保存定时智能体设置提词页，基础信息已完成 属性basicinfo @active
- 步骤6：已保存定时智能体设置提词页，预览可点 属性preview @clickable
- 步骤7：新建普通智能体基础信息页，下一步不可点 属性setinputfields @disabled

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiTest = new aiModelTest();

$timerSteps  = array('basicinfo', 'setprompt', 'preview');
$normalSteps = array('basicinfo', 'setinputfields', 'setinputform', 'setprompt', 'preview');

r($aiTest->getPromptDesignStepStatusTest($timerSteps, 'basicinfo', 'basicinfo', 0))  && p('setprompt')      && e('disabled');  // 步骤1：新建定时智能体基础信息页，设置提词不可点
r($aiTest->getPromptDesignStepStatusTest($timerSteps, 'basicinfo', 'basicinfo', 0))  && p('basicinfo')      && e('current');   // 步骤2：新建定时智能体基础信息页，当前步骤为基础信息
r($aiTest->getPromptDesignStepStatusTest($timerSteps, 'basicinfo', 'basicinfo', 0))  && p('preview')        && e('disabled');  // 步骤3：新建定时智能体基础信息页，预览不可点
r($aiTest->getPromptDesignStepStatusTest($timerSteps, 'basicinfo', 'basicinfo', 1))  && p('setprompt')      && e('clickable'); // 步骤4：已保存定时智能体基础信息页，设置提词可点
r($aiTest->getPromptDesignStepStatusTest($timerSteps, 'setprompt', 'setprompt', 1))  && p('basicinfo')      && e('active');    // 步骤5：已保存定时智能体设置提词页，基础信息已完成
r($aiTest->getPromptDesignStepStatusTest($timerSteps, 'setprompt', 'setprompt', 1))  && p('preview')        && e('clickable'); // 步骤6：已保存定时智能体设置提词页，预览可点
r($aiTest->getPromptDesignStepStatusTest($normalSteps, 'basicinfo', 'basicinfo', 0)) && p('setinputfields') && e('disabled');  // 步骤7：新建普通智能体基础信息页，下一步不可点
