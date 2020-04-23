<?php
$lang->testreport->common       = '测试报告';
$lang->testreport->browse       = '报告列表';
$lang->testreport->create       = '创建报告';
$lang->testreport->edit         = '编辑报告';
$lang->testreport->delete       = '删除报告';
$lang->testreport->export       = '导出';
$lang->testreport->exportAction = '导出报告';
$lang->testreport->view         = '报告详情';
$lang->testreport->recreate     = '重新生成报告';

$lang->testreport->title       = '标题';
$lang->testreport->product     = "所属{$lang->productCommon}";
$lang->testreport->bugTitle    = 'Bug 标题';
$lang->testreport->storyTitle  = "{$lang->storyCommon}标题";
$lang->testreport->project     = '所属项目';
$lang->testreport->testtask    = '测试版本';
$lang->testreport->tasks       = $lang->testreport->testtask;
$lang->testreport->startEnd    = '起止时间';
$lang->testreport->owner       = '负责人';
$lang->testreport->members     = '参与人员';
$lang->testreport->begin       = '开始时间';
$lang->testreport->end         = '结束时间';
$lang->testreport->stories     = "测试的{$lang->storyCommon}";
$lang->testreport->bugs        = '测试的Bug';
$lang->testreport->builds      = '版本信息';
$lang->testreport->goal        = '项目目标';
$lang->testreport->cases       = '用例';
$lang->testreport->bugInfo     = 'Bug分布';
$lang->testreport->report      = '总结';
$lang->testreport->legacyBugs  = '遗留的Bug';
$lang->testreport->createdBy   = '由谁创建';
$lang->testreport->createdDate = '创建时间';
$lang->testreport->objectID    = '所属对象';
$lang->testreport->objectType  = '对象类型';
$lang->testreport->profile     = '概况';
$lang->testreport->value       = '值';
$lang->testreport->none        = '无';
$lang->testreport->all         = '所有报告';
$lang->testreport->deleted     = '已删除';
$lang->testreport->selectTask  = '按测试单创建报告';

$lang->testreport->legendBasic       = '基本信息';
$lang->testreport->legendStoryAndBug = '测试范围';
$lang->testreport->legendBuild       = '测试轮次';
$lang->testreport->legendCase        = '关联的用例';
$lang->testreport->legendLegacyBugs  = '遗留的Bug';
$lang->testreport->legendReport      = '报表';
$lang->testreport->legendComment     = '总结';
$lang->testreport->legendMore        = '更多功能';

$lang->testreport->bugSeverityGroups   = 'Bug严重级别分布';
$lang->testreport->bugTypeGroups       = 'Bug类型分布';
$lang->testreport->bugStatusGroups     = 'Bug状态分布';
$lang->testreport->bugOpenedByGroups   = 'Bug创建者分布';
$lang->testreport->bugResolvedByGroups = 'Bug解决者分布';
$lang->testreport->bugResolutionGroups = 'Bug解决方案分布';
$lang->testreport->bugModuleGroups     = 'Bug模块分布';
$lang->testreport->legacyBugs          = '遗留的Bug';
$lang->testreport->bugConfirmedRate    = '有效Bug率 (方案为已解决或延期 / 状态为已解决或已关闭)';
$lang->testreport->bugCreateByCaseRate = '用例发现Bug率 (用例创建的Bug / 时间区间中新增的Bug)';

$lang->testreport->caseSummary    = '共有<strong>%s</strong>个用例，共执行<strong>%s</strong>个用例，产生了<strong>%s</strong>个结果，失败的用例有<strong>%s</strong>个。';
$lang->testreport->buildSummary   = '共测试了<strong>%s</strong>个版本。';
$lang->testreport->confirmDelete  = '是否删除该报告？';
$lang->testreport->moreNotice     = '更多功能可以参考禅道扩展机制进行扩展，也可以联系我们进行定制。';
$lang->testreport->exportNotice   = "由<a href='https://www.zentao.net' target='_blank' style='color:grey'>禅道项目管理软件</a>导出";
$lang->testreport->noReport       = "报表还没有生成。";
$lang->testreport->foundBugTip    = "影响版本在测试轮次内，并且创建时间在测试时间范围内产生的Bug数。";
$lang->testreport->legacyBugTip   = "Bug状态是激活，或Bug的解决时间在测试结束时间之后。";
$lang->testreport->fromCaseBugTip = "测试时间范围内，用例执行失败后创建的Bug。";
$lang->testreport->errorTrunk     = "主干版本不能创建测试报告，请修改关联版本！";
$lang->testreport->noTestTask     = "该{$lang->productCommon}下还没有关联非Trunk的测试单，不能创建报告。请先创建测试单，再创建。";
$lang->testreport->noObjectID     = "没有选定测试单或{$lang->projectCommon}，无法创建测试报告！";
$lang->testreport->moreProduct    = "只能对同一个{$lang->productCommon}生成测试报告。";
$lang->testreport->hiddenCase     = "隐藏 %s 个用例";

$lang->testreport->bugSummary = <<<EOD
共发现<strong>%s</strong>个Bug <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->foundBugTip}'><i class='icon-help'></i></a>，
遗留<strong>%s</strong>个Bug <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->legacyBugTip}'><i class='icon-help'></i></a>。
用例执行产生<strong>%s</strong>个Bug <a data-toggle='tooltip' class='text-warning' title='{$lang->testreport->fromCaseBugTip}'><i class='icon-help'></i></a>。
有效Bug率（方案为已解决或延期 / 状态为已解决或已关闭）：<strong>%s</strong>，用例发现Bug率（用例创建的Bug / 发现Bug数）：<strong>%s</strong>
EOD;
