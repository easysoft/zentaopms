#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';

$story = zenData('story');
$story->id->range('1-5');
$story->version->range('1-3');
$story->title->range('需求{1-5}');
$story->gen(5);

$case = zenData('case');
$case->id->range('1-5');
$case->version->range('1-3');
$case->title->range('用例{1-5}');
$case->gen(5);

su('admin');

/**

title=测试 bugZen::prepareCreateExtras();
timeout=0
cid=15466

- 执行invokeArgs($zen模块的newInstance方法，参数是, [$formData1] 属性project @123
- 执行$result2属性assignedDate @datetime
- 执行invokeArgs($zen模块的newInstance方法，参数是, [$formData3] 属性storyVersion @2
- 执行invokeArgs($zen模块的newInstance方法，参数是, [$formData4] 
 - 属性caseVersion @2
 - 属性result @0
- 执行project) || $result5模块的project == 0方法  @1

*/

global $tester, $app;
$app->rawModule = 'bug';
$app->rawMethod = 'create';

// 设置session和配置
$tester->session->set('project', 123);

// 创建测试用的form对象
function createFormMock($assignedTo, $story, $case, $fromCase = 0) {
    global $tester;
    
    // 设置POST数据模拟表单提交
    $_POST['fromCase'] = $fromCase;
    $_POST['assignedTo'] = $assignedTo;
    $_POST['story'] = $story > 0 ? $story : '';
    $_POST['case'] = $case;
    $_POST['title'] = 'Test Bug';
    $_POST['type'] = 'codeerror';
    $_POST['product'] = 1;
    $_POST['execution'] = 0;
    $_POST['openedBuild'] = 'trunk';
    $_POST['pri'] = 3;
    $_POST['severity'] = 3;
    $_POST['steps'] = 'Test steps';
    
    // 使用真实的form类创建对象
    $formData = form::data($tester->config->bug->form->create);
    
    return $formData;
}

$zen = initReference('bug');
$func = $zen->getMethod('prepareCreateExtras');

// 测试1：非QA组设置项目
$tester->lang->navGroup->bug = 'product';
$formData1 = createFormMock('user1', 1, 0);
r($func->invokeArgs($zen->newInstance(), [$formData1])) && p('project') && e('123');

// 测试2：指派用户设置日期
$formData2 = createFormMock('user1', 0, 0);
$result2 = $func->invokeArgs($zen->newInstance(), [$formData2]);
if(isset($result2->assignedDate) && $result2->assignedDate) $result2->assignedDate = 'datetime';
r($result2) && p('assignedDate') && e('datetime');

// 测试3：关联需求设置版本
$formData3 = createFormMock('', 2, 0);
r($func->invokeArgs($zen->newInstance(), [$formData3])) && p('storyVersion') && e('2');

// 测试4：从用例创建设置版本
$formData4 = createFormMock('', 0, 1, 2);
r($func->invokeArgs($zen->newInstance(), [$formData4])) && p('caseVersion,result') && e('2,0');

// 测试5：QA组不设置项目
$tester->lang->navGroup->bug = 'qa';
$formData5 = createFormMock('', 0, 0);
$result5 = $func->invokeArgs($zen->newInstance(), [$formData5]);
r(!isset($result5->project) || $result5->project == 0) && p() && e('1');