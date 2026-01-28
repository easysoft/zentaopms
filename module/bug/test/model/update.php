#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

function initData()
{
    $data = zenData('bug');
    $data->id->range('1-5');
    $data->product->range('1-5');
    $data->branch->range('0-1');
    $data->project->range('0-5');
    $data->execution->range('0-5');
    $data->title->prefix('BUG')->range('1-5');
    $data->openedBuild->range('1-5');
    $data->type->range('[codeerror]');
    $data->status->range('[active]');
    $data->pri->range('[3]');
    $data->severity->range('[3]');

    $data->gen(4);
}

/**

title=bugModel->update();
timeout=0
cid=15407

- 测试更新bug标题
 - 第0条的field属性 @title
 - 第0条的old属性 @BUG1
 - 第0条的new属性 @john

- 测试更新bug类型
 - 第0条的field属性 @type
 - 第0条的old属性 @codeerror
 - 第0条的new属性 @config

- 测试不更改bug标题 @没有数据更新

- 测试不更改bug类型 @没有数据更新

- 测试不输入Bug标题 @『Bug标题』不能为空。

- 测试通知邮件不合法 @『通知邮箱』应当为合法的EMAIL。

- 测试解决者不为空时，不输入解决方案 @『解决方案』不能为空。

- 测试由谁关闭不为空时，不输入解决方案 @『解决方案』不能为空。

- 测试解决方案为重复Bug时，不输入重复Bug值 @『重复Bug』不能为空。

- 测试解决方案为已修复时，不输入解决版本 @『解决版本』不能为空。

*/

initData();

$bugIdList = array(1, 2);

$t_uptitle         = array('title'       => 'john');
$t_uptype          = array('type'        => 'config');
$t_untitle         = array('title'       => 'john');
$t_untype          = array('type'        => 'config');
$t_titleRequire    = array('title'       => '');
$t_unnotifyEmail   = array('notifyEmail' => '123');
$t_resolution1     = array('resolvedBy'  => 'john',      'resolution'    => '');
$t_resolution2     = array('closedBy'    => 'john',      'resolution'    => '');
$t_unduplicateBug  = array('resolution'  => 'duplicate', 'duplicateBug'  => 0);
$t_unresolvedBuild = array('resolution'  => 'fixed',     'resolvedBuild' => '');

$bug = new bugModelTest();
r($bug->updateObject($bugIdList[0], $t_uptitle))         && p('0:field,old,new') && e('title,BUG1,john');                 // 测试更新bug标题
r($bug->updateObject($bugIdList[0], $t_uptype))          && p('0:field,old,new') && e('type,codeerror,config');           // 测试更新bug类型
r($bug->updateObject($bugIdList[0], $t_untitle))         && p()                  && e('没有数据更新');                    // 测试不更改bug标题
r($bug->updateObject($bugIdList[0], $t_untype))          && p()                  && e('没有数据更新');                    // 测试不更改bug类型
r($bug->updateObject($bugIdList[0], $t_titleRequire))    && p('title:0')         && e('『Bug标题』不能为空。');           // 测试不输入Bug标题
r($bug->updateObject($bugIdList[0], $t_unnotifyEmail))   && p('notifyEmail:0')   && e('『通知邮箱』应当为合法的EMAIL。'); // 测试通知邮件不合法
r($bug->updateObject($bugIdList[0], $t_resolution1))     && p('resolution:0')    && e('『解决方案』不能为空。');          // 测试解决者不为空时，不输入解决方案
r($bug->updateObject($bugIdList[0], $t_resolution2))     && p('resolution:0')    && e('『解决方案』不能为空。');          // 测试由谁关闭不为空时，不输入解决方案
r($bug->updateObject($bugIdList[0], $t_unduplicateBug))  && p('duplicateBug:0')  && e('『重复Bug』不能为空。');           // 测试解决方案为重复Bug时，不输入重复Bug值
r($bug->updateObject($bugIdList[0], $t_unresolvedBuild)) && p('resolvedBuild:0') && e('『解决版本』不能为空。');          // 测试解决方案为已修复时，不输入解决版本
