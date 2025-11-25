#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::prepareEditExtras();
timeout=0
cid=0

- 执行testcaseTest模块的prepareEditExtrasTest方法，参数是$formData1, $oldCase, $postData1
 - 属性id @1
 - 属性version @1
 - 属性lastEditedBy @admin
- 执行testcaseTest模块的prepareEditExtrasTest方法，参数是$formData2, $oldCase, $postData2 第message条的0属性 @步骤1不能为空
- 执行testcaseTest模块的prepareEditExtrasTest方法，参数是$formData3, $oldCase, $postData3 属性version @2
- 执行testcaseTest模块的prepareEditExtrasTest方法，参数是$formData4, $oldCase, $postData4 属性storyVersion @2
- 执行testcaseTest模块的prepareEditExtrasTest方法，参数是$formData5, $oldCase5, $postData5 属性script @&lt;script&gt;alert(&quot;test&quot;)&lt;/script&gt;

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. zendata数据准备
$caseTable = zenData('case');
$caseTable->id->range('1-10');
$caseTable->product->range('1');
$caseTable->module->range('0');
$caseTable->story->range('1,2,3,4,5');
$caseTable->title->range('测试用例1,测试用例2,测试用例3,测试用例4,测试用例5');
$caseTable->version->range('1');
$caseTable->status->range('normal,wait');
$caseTable->lastEditedDate->range('`2023-09-01 10:00:00`');
$caseTable->storyVersion->range('1');
$caseTable->auto->range('no,auto');
$caseTable->script->range('[]');
$caseTable->gen(10);

$stepTable = zenData('casestep');
$stepTable->id->range('1-20');
$stepTable->case->range('1-10');
$stepTable->version->range('1');
$stepTable->desc->range('步骤描述1,步骤描述2,步骤描述3');
$stepTable->expect->range('期望结果1,期望结果2,期望结果3');
$stepTable->type->range('step,group,item');
$stepTable->gen(20);

$storyTable = zenData('story');
$storyTable->id->range('1-10');
$storyTable->product->range('1');
$storyTable->title->range('需求1,需求2,需求3,需求4,需求5');
$storyTable->version->range('1,2,3,4,5');
$storyTable->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$testcaseTest = new testcaseZenTest();

// 5. 准备测试数据
$oldCase = new stdClass();
$oldCase->id = 1;
$oldCase->product = 1;
$oldCase->module = 0;
$oldCase->story = 1;
$oldCase->title = '测试用例标题1';
$oldCase->version = 1;
$oldCase->status = 'normal';
$oldCase->lastEditedDate = '2023-09-01 10:00:00';
$oldCase->storyVersion = 1;
$oldCase->script = '';
$oldCase->lib = 0;

// 设置oldCase的steps属性（getStatusForUpdate需要）
$step1 = new stdClass();
$step1->desc = '步骤1';
$step1->expect = '期望1';
$step1->type = 'step';

$step2 = new stdClass();
$step2->desc = '步骤2';
$step2->expect = '期望2';
$step2->type = 'step';

$step3 = new stdClass();
$step3->desc = '步骤3';
$step3->expect = '期望3';
$step3->type = 'step';

$oldCase->steps = array($step1, $step2, $step3);

// 6. 测试步骤
// 步骤1:正常情况 - 有效的steps和expects数据
$formData1 = array(
    'steps' => array('步骤1', '步骤2', '步骤3'),
    'expects' => array('期望1', '期望2', '期望3'),
    'title' => '测试用例标题1',
    'story' => 1,
    'auto' => 'no',
    'linkCase' => '',
    'script' => '',
    'branch' => 0,
    'module' => 0,
    'product' => 1,
    'stage' => array()
);
$postData1 = array(
    'steps' => array('步骤1', '步骤2', '步骤3'),
    'expects' => array('期望1', '期望2', '期望3'),
    'stepType' => array('step', 'step', 'step'),
    'lastEditedDate' => '2023-09-01 10:00:00'
);
r($testcaseTest->prepareEditExtrasTest($formData1, $oldCase, $postData1)) && p('id,version,lastEditedBy') && e('1,1,admin');

// 步骤2:边界情况 - expect存在但对应的step为空
$formData2 = array(
    'steps' => array('步骤1', '', '步骤3'),
    'expects' => array('期望1', '期望2', '期望3'),
    'title' => '测试用例标题1',
    'story' => 1,
    'auto' => 'no',
    'linkCase' => '',
    'script' => '',
    'branch' => 0,
    'module' => 0,
    'product' => 1,
    'stage' => array()
);
$postData2 = array(
    'steps' => array('步骤1', '', '步骤3'),
    'expects' => array('期望1', '期望2', '期望3'),
    'stepType' => array('step', 'step', 'step'),
    'lastEditedDate' => '2023-09-01 10:00:00'
);
r($testcaseTest->prepareEditExtrasTest($formData2, $oldCase, $postData2)) && p('message:0') && e('步骤1不能为空');

// 步骤3:步骤变更情况 - 修改step内容导致版本号+1
$formData3 = array(
    'steps' => array('修改后的步骤1', '步骤2', '步骤3'),
    'expects' => array('期望1', '期望2', '期望3'),
    'title' => '测试用例标题1',
    'story' => 1,
    'auto' => 'no',
    'linkCase' => '',
    'script' => '',
    'branch' => 0,
    'module' => 0,
    'product' => 1,
    'stage' => array()
);
$postData3 = array(
    'steps' => array('修改后的步骤1', '步骤2', '步骤3'),
    'expects' => array('期望1', '期望2', '期望3'),
    'stepType' => array('step', 'step', 'step'),
    'lastEditedDate' => '2023-09-01 10:00:00'
);
r($testcaseTest->prepareEditExtrasTest($formData3, $oldCase, $postData3)) && p('version') && e('2');

// 步骤4:story变更 - story ID改变时storyVersion应更新
$formData4 = array(
    'steps' => array('步骤1', '步骤2'),
    'expects' => array('期望1', '期望2'),
    'title' => '测试用例标题1',
    'story' => 2,
    'auto' => 'no',
    'linkCase' => '',
    'script' => '',
    'branch' => 0,
    'module' => 0,
    'product' => 1,
    'stage' => array()
);
$postData4 = array(
    'steps' => array('步骤1', '步骤2'),
    'expects' => array('期望1', '期望2'),
    'stepType' => array('step', 'step'),
    'lastEditedDate' => '2023-09-01 10:00:00'
);
r($testcaseTest->prepareEditExtrasTest($formData4, $oldCase, $postData4)) && p('storyVersion') && e('2');

// 步骤5:auto为auto且有script时进行HTML实体转义
$oldCase5 = clone $oldCase;
$oldCase5->script = '<script>alert("test")</script>';
$formData5 = array(
    'steps' => array('步骤1', '步骤2'),
    'expects' => array('期望1', '期望2'),
    'title' => '测试用例标题1',
    'story' => 1,
    'auto' => 'auto',
    'script' => '<script>alert("test")</script>',
    'linkCase' => '',
    'branch' => 0,
    'module' => 0,
    'product' => 1,
    'stage' => array()
);
$postData5 = array(
    'steps' => array('步骤1', '步骤2'),
    'expects' => array('期望1', '期望2'),
    'stepType' => array('step', 'step'),
    'lastEditedDate' => '2023-09-01 10:00:00'
);
r($testcaseTest->prepareEditExtrasTest($formData5, $oldCase5, $postData5)) && p('script') && e('&lt;script&gt;alert(&quot;test&quot;)&lt;/script&gt;');