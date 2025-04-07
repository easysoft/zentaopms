<?php
/**
 * The story module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: zh-cn.php 5141 2013-07-15 05:57:15Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
global $config;
$lang->story->create            = "提{$lang->SRCommon}";

$lang->story->requirement       = zget($lang, 'URCommon', "需求");
$lang->story->story             = zget($lang, 'SRCommon', "故事");
$lang->story->createStory       = '添加' . $lang->story->story;
$lang->story->createRequirement = '添加' . $lang->story->requirement;
$lang->story->affectedStories   = "影响的{$lang->story->story}";

$lang->story->batchCreate        = "批量创建";
$lang->story->change             = "变更";
$lang->story->changed            = "{$lang->SRCommon}变更";
$lang->story->assignTo           = '指派';
$lang->story->review             = '评审';
$lang->story->submitReview       = "提交评审";
$lang->story->recall             = '撤销评审';
$lang->story->recallChange       = '撤销变更';
$lang->story->recallAction       = '撤销';
$lang->story->relation           = '查看关联需求';
$lang->story->needReview         = '需要评审';
$lang->story->batchReview        = '批量评审';
$lang->story->edit               = "编辑";
$lang->story->editDraft          = "编辑草稿";
$lang->story->batchEdit          = "批量编辑";
$lang->story->subdivide          = '拆分';
$lang->story->subdivideSR        = $lang->SRCommon . '拆分';
$lang->story->link               = '关联';
$lang->story->unlink             = '移除';
$lang->story->track              = '跟踪矩阵';
$lang->story->trackAB            = '矩阵';
$lang->story->processStoryChange = '确认父需求变更';
$lang->story->storyChange        = '需求变更';
$lang->story->upstreamDemand     = '上游需求';
$lang->story->split              = '拆分';
$lang->story->close              = '关闭';
$lang->story->batchClose         = '批量关闭';
$lang->story->activate           = '激活';
$lang->story->delete             = "删除";
$lang->story->view               = "{$lang->SRCommon}详情";
$lang->story->setting            = "设置";
$lang->story->tasks              = "相关任务";
$lang->story->bugs               = "相关Bug";
$lang->story->cases              = "相关用例";
$lang->story->taskCount          = '任务数';
$lang->story->bugCount           = 'Bug数';
$lang->story->caseCount          = '用例数';
$lang->story->taskCountAB        = 'T';
$lang->story->bugCountAB         = 'B';
$lang->story->caseCountAB        = 'C';
$lang->story->linkStory          = "关联需求";
$lang->story->unlinkStory        = "您确认移除相关需求吗？";
$lang->story->linkStoriesAB      = "关联相关{$lang->SRCommon}";
$lang->story->linkRequirementsAB = "关联相关{$lang->URCommon}";
$lang->story->export             = "导出数据";
$lang->story->zeroCase           = "零用例{$lang->SRCommon}";
$lang->story->zeroTask           = "只列零任务{$lang->SRCommon}";
$lang->story->reportChart        = "统计报表";
$lang->story->copyTitle          = "同{$lang->SRCommon}名称";
$lang->story->batchChangePlan    = "批量修改计划";
$lang->story->batchChangeBranch  = "批量修改分支";
$lang->story->batchChangeStage   = "批量修改阶段";
$lang->story->batchAssignTo      = "批量指派";
$lang->story->batchChangeModule  = "批量修改模块";
$lang->story->batchChangeParent  = "批量修改父需求";
$lang->story->batchChangeGrade   = "批量修改层级";
$lang->story->changeParent       = "修改父需求";
$lang->story->viewAll            = '查看全部';
$lang->story->toTask             = '转任务';
$lang->story->batchToTask        = '批量转任务';
$lang->story->convertRelations   = '换算关系';
$lang->story->undetermined       = '待定';
$lang->story->order              = '排序';
$lang->story->saveDraft          = '存为草稿';
$lang->story->doNotSubmit        = '保存暂不提交';
$lang->story->currentBranch      = '当前%s';
$lang->story->twins              = '孪生需求';
$lang->story->relieved           = '解除';
$lang->story->relievedTwins      = '解除孪生需求';
$lang->story->loadAllStories     = '所有';
$lang->story->hasDividedTask     = '已经分解任务';
$lang->story->hasDividedCase     = '已经创建用例';
$lang->story->viewAllGrades      = '查看所有层级';
$lang->story->codeBranch         = '代码分支';
$lang->story->unlinkBranch       = '解除关联代码分支';
$lang->story->branchName         = '分支名称';
$lang->story->branchFrom         = '创建自';
$lang->story->codeRepo           = '仓库名称';
$lang->story->viewByType         = "按照%s查看";

$lang->story->editAction      = "编辑{$lang->SRCommon}";
$lang->story->changeAction    = "变更{$lang->SRCommon}";
$lang->story->assignAction    = "指派{$lang->SRCommon}";
$lang->story->reviewAction    = "评审{$lang->SRCommon}";
$lang->story->subdivideAction = "拆分{$lang->SRCommon}";
$lang->story->closeAction     = "关闭{$lang->SRCommon}";
$lang->story->activateAction  = "激活{$lang->SRCommon}";
$lang->story->deleteAction    = "删除{$lang->SRCommon}";
$lang->story->exportAction    = "导出{$lang->SRCommon}";
$lang->story->reportAction    = "统计报表";

$lang->story->closedStory      = '需求：%s 已关闭，将不会被关闭。';
$lang->story->batchToTaskTips  = "只有激活状态的{$lang->SRCommon}才能转为任务。";
$lang->story->successToTask    = '批量转任务成功';
$lang->story->storyRound       = '第 %s 轮估算';
$lang->story->float            = "『%s』应当是正数，可以是小数。";
$lang->story->saveDraftSuccess = '存为草稿成功';

$lang->story->changeSyncTip    = "该需求的修改会同步到如下的孪生需求";
$lang->story->syncTip          = "孪生需求间除{$lang->productCommon}、分支 、模块、计划、阶段外均同步，孪生关系解除后不再同步";
$lang->story->relievedTip      = "孪生关系解除后无法恢复，需求的内容不再同步，是否解除？";
$lang->story->assignSyncTip    = "孪生需求均同步修改指派人";
$lang->story->closeSyncTip     = "孪生需求均同步关闭";
$lang->story->activateSyncTip  = "孪生需求均同步激活";
$lang->story->relievedTwinsTip = "{$lang->productCommon}调整后，本需求自动解除孪生关系，需求不再同步，是否保存？";
$lang->story->batchEditTip     = "{$lang->SRCommon} %s为孪生需求，本次操作已被过滤。";
$lang->story->planTip          = "{$lang->SRCommon}只能单选计划，其他需求可多选计划。";
$lang->story->batchEditError   = "所选需求皆不可编辑，本次操作已被过滤。";

$lang->story->id               = '编号';
$lang->story->parent           = '父需求';
$lang->story->isParent         = '是父需求';
$lang->story->grade            = '需求层级';
$lang->story->gradeName        = '层级名称';
$lang->story->path             = '路径';
$lang->story->product          = "所属{$lang->productCommon}";
$lang->story->project          = "所属{$lang->projectCommon}";
$lang->story->execution        = "所属执行";
$lang->story->branch           = "平台/分支";
$lang->story->module           = '所属模块';
$lang->story->moduleAB         = '模块';
$lang->story->roadmap          = '所属路标';
$lang->story->source           = "来源";
$lang->story->sourceNote       = '来源备注';
$lang->story->fromBug          = '来源Bug';
$lang->story->title            = "{$lang->SRCommon}名称";
$lang->story->name             = "需求名称";
$lang->story->type             = "需求类型";
$lang->story->category         = "类别";
$lang->story->color            = '标题颜色';
$lang->story->toBug            = '转Bug';
$lang->story->spec             = "描述";
$lang->story->assign           = '指派给';
$lang->story->verify           = '验收标准';
$lang->story->pri              = '优先级';
$lang->story->estimate         = "预计{$lang->hourCommon}";
$lang->story->estimateAB       = '预计';
$lang->story->hour             = $lang->hourCommon;
$lang->story->consumed         = '耗时';
$lang->story->status           = '当前状态';
$lang->story->statusAB         = '状态';
$lang->story->subStatus        = '子状态';
$lang->story->stage            = '所处阶段';
$lang->story->stageAB          = '阶段';
$lang->story->stagedBy         = '设置阶段者';
$lang->story->mailto           = '抄送给';
$lang->story->openedBy         = '由谁创建';
$lang->story->openedByAB       = '创建者';
$lang->story->openedDate       = '创建日期';
$lang->story->assignedTo       = '指派给';
$lang->story->assignedToAB     = '指派';
$lang->story->assignedDate     = '指派日期';
$lang->story->lastEditedBy     = '最后修改';
$lang->story->lastEditedByAB   = '最后修改者';
$lang->story->lastEditedDate   = '最后修改日期';
$lang->story->closedBy         = '由谁关闭';
$lang->story->closedDate       = '关闭日期';
$lang->story->closedReason     = '关闭原因';
$lang->story->rejectedReason   = '拒绝原因';
$lang->story->changedBy        = '由谁变更';
$lang->story->changedDate      = '变更时间';
$lang->story->reviewedBy       = '已评审人';
$lang->story->reviewer         = '评审人';
$lang->story->reviewers        = '评审人员';
$lang->story->reviewedDate     = '评审时间';
$lang->story->activatedDate    = '激活日期';
$lang->story->version          = '版本号';
$lang->story->feedbackBy       = '反馈者';
$lang->story->notifyEmail      = '通知邮箱';
$lang->story->plan             = "所属计划";
$lang->story->planAB           = '计划';
$lang->story->comment          = '备注';
$lang->story->children         = "子需求";
$lang->story->childItem        = "子项";
$lang->story->childrenAB       = "子";
$lang->story->linkStories      = "相关需求";
$lang->story->linkRequirements = "相关{$lang->URCommon}";
$lang->story->childStories     = "拆分{$lang->SRCommon}";
$lang->story->duplicateStory   = "重复需求";
$lang->story->reviewResult     = '评审意见';
$lang->story->reviewResultAB   = '评审结果';
$lang->story->preVersion       = '之前版本';
$lang->story->keywords         = '关键词';
$lang->story->newStory         = "继续添加{$lang->SRCommon}";
$lang->story->colorTag         = '颜色标签';
$lang->story->files            = '附件';
$lang->story->copy             = "复制需求";
$lang->story->total            = "总{$lang->SRCommon}";
$lang->story->draft            = '草稿';
$lang->story->unclosed         = '未关闭';
$lang->story->deleted          = '已删除';
$lang->story->released         = "已发布{$lang->SRCommon}数";
$lang->story->URChanged        = '用需变更';
$lang->story->design           = '相关设计';
$lang->story->case             = '相关用例';
$lang->story->bug              = '相关Bug';
$lang->story->repoCommit       = '相关提交';
$lang->story->one              = '一个';
$lang->story->field            = '同步的字段';
$lang->story->completeRate     = '完成率';
$lang->story->reviewed         = '已评审';
$lang->story->toBeReviewed     = '待评审';
$lang->story->linkMR           = '相关合并请求';
$lang->story->linkPR           = '相关推送请求';
$lang->story->linkCommit       = '相关代码版本';
$lang->story->URS              = '用户需求';
$lang->story->estimateUnit     = "（单位：{$lang->story->hour}）";
$lang->story->verifiedDate     = '验收时间';

$lang->story->ditto       = '同上';
$lang->story->dittoNotice = "该{$lang->SRCommon}与上一{$lang->SRCommon}不属于同一{$lang->productCommon}！";

$lang->story->viewTypeList['tiled'] = '平铺';
$lang->story->viewTypeList['tree']  = '树状';

if($config->enableER) $lang->story->typeList['epic']        = $lang->ERCommon;
if($config->URAndSR)  $lang->story->typeList['requirement'] = $lang->URCommon;
$lang->story->typeList['story'] = $lang->SRCommon;

$lang->story->needNotReviewList[0] = '需要评审';
$lang->story->needNotReviewList[1] = '不需要评审';

$lang->story->useList[0] = '不使用';
$lang->story->useList[1] = '使用';

$lang->story->statusList['']          = '';
$lang->story->statusList['draft']     = '草稿';
$lang->story->statusList['reviewing'] = '评审中';
$lang->story->statusList['active']    = '激活';
$lang->story->statusList['changing']  = '变更中';
$lang->story->statusList['closed']    = '已关闭';

$lang->story->stageList['']           = '';
$lang->story->stageList['wait']       = '未开始';
$lang->story->stageList['planned']    = "已计划";
$lang->story->stageList['projected']  = '研发立项';
$lang->story->stageList['designing']  = '设计中';
$lang->story->stageList['designed']   = '设计完毕';
$lang->story->stageList['developing'] = '研发中';
$lang->story->stageList['developed']  = '研发完毕';
$lang->story->stageList['testing']    = '测试中';
$lang->story->stageList['tested']     = '测试完毕';
$lang->story->stageList['verified']   = '已验收';
$lang->story->stageList['rejected']   = '验收失败';
$lang->story->stageList['delivering'] = '交付中';
$lang->story->stageList['delivered']  = '已交付';
$lang->story->stageList['released']   = '已发布';
$lang->story->stageList['closed']     = '已关闭';

$lang->story->reasonList['']           = '';
$lang->story->reasonList['done']       = '已完成';
$lang->story->reasonList['subdivided'] = '已拆分';
$lang->story->reasonList['duplicate']  = '重复';
$lang->story->reasonList['postponed']  = '延期';
$lang->story->reasonList['willnotdo']  = '不做';
$lang->story->reasonList['cancel']     = '已取消';
$lang->story->reasonList['bydesign']   = '设计如此';
//$lang->story->reasonList['isbug']      = '是个Bug';

$lang->story->reviewResultList['']        = '';
$lang->story->reviewResultList['pass']    = '确认通过';
$lang->story->reviewResultList['revert']  = '撤销变更';
$lang->story->reviewResultList['clarify'] = '有待明确';
$lang->story->reviewResultList['reject']  = '拒绝';

$lang->story->reviewList[0] = '否';
$lang->story->reviewList[1] = '是';

$lang->story->sourceList['']           = '';
$lang->story->sourceList['customer']   = '客户';
$lang->story->sourceList['user']       = '用户';
$lang->story->sourceList['po']         = $lang->productCommon . '经理';
$lang->story->sourceList['market']     = '市场';
$lang->story->sourceList['service']    = '客服';
$lang->story->sourceList['operation']  = '运营';
$lang->story->sourceList['support']    = '技术支持';
$lang->story->sourceList['competitor'] = '竞争对手';
$lang->story->sourceList['partner']    = '合作伙伴';
$lang->story->sourceList['dev']        = '开发人员';
$lang->story->sourceList['tester']     = '测试人员';
$lang->story->sourceList['bug']        = 'Bug';
$lang->story->sourceList['forum']      = '论坛';
$lang->story->sourceList['other']      = '其他';

$lang->story->priList[0] = '';
$lang->story->priList[1] = '1';
$lang->story->priList[2] = '2';
$lang->story->priList[3] = '3';
$lang->story->priList[4] = '4';

$lang->story->changeList = array();
$lang->story->changeList['no']  = '不变更';
$lang->story->changeList['yes'] = '变更';

$lang->story->legendBasicInfo      = '基本信息';
$lang->story->legendLifeTime       = "需求的一生";
$lang->story->legendRelated        = '相关信息';
$lang->story->legendMailto         = '抄送给';
$lang->story->legendAttach         = '附件';
$lang->story->legendProjectAndTask = $lang->executionCommon . '任务';
$lang->story->legendBugs           = '相关Bug';
$lang->story->legendFromBug        = '来源Bug';
$lang->story->legendCases          = '相关用例';
$lang->story->legendBuilds         = '相关构建';
$lang->story->legendReleases       = '相关发布';
$lang->story->legendLinkStories    = "相关{$lang->SRCommon}";
$lang->story->legendChildStories   = "拆分{$lang->SRCommon}";
$lang->story->legendSpec           = "需求描述";
$lang->story->legendVerify         = '验收标准';
$lang->story->legendMisc           = '其他相关';
$lang->story->legendInformation    = '需求信息';

$lang->story->lblChange            = "变更{$lang->SRCommon}";
$lang->story->lblReview            = "评审{$lang->SRCommon}";
$lang->story->lblActivate          = "激活{$lang->SRCommon}";
$lang->story->lblClose             = "关闭{$lang->SRCommon}";
$lang->story->lblTBC               = '任务Bug用例';

$lang->story->checkAffection       = '影响范围';
$lang->story->affectedProjects     = "影响的{$lang->project->common}或{$lang->execution->common}";
$lang->story->affectedBugs         = '影响的Bug';
$lang->story->affectedCases        = '影响的用例';
$lang->story->affectedTwins        = '影响的孪生需求';

$lang->story->specTemplate           = "建议参考的模板：作为一名<某种类型的用户>，我希望<达成某些目的>，这样可以<开发的价值>。";
$lang->story->needNotReview          = '不需要评审';
$lang->story->childStoryTitle        = '包含%s个子需求，其中%s个已完成';
$lang->story->childTaskTitle         = '包含%s个子任务，其中%s个已完成';
$lang->story->successSaved           = "{$lang->SRCommon}成功添加，";
$lang->story->confirmDelete          = "您确认删除该{$lang->SRCommon}吗?";
$lang->story->confirmRecall          = "您确认撤销该{$lang->SRCommon}吗?";
$lang->story->errorEmptyChildStory   = "『拆分{$lang->SRCommon}』不能为空。";
$lang->story->errorNotSubdivide      = "状态在评审中、已关闭的{$lang->SRCommon}，或者是子需求，不能拆分。";
$lang->story->errorMaxGradeSubdivide = "该需求的层级已经达到系统设置的最大层级，不能拆分同类型需求。";
$lang->story->errorStepwiseSubdivide = "该需求类型不允许跨层级拆分，后台可以更改此设置。";
$lang->story->errorCannotSplit       = "该需求已拆分此类型的子需求，不能拆分为其他类型需求。";
$lang->story->errorParentSplitTask   = "父需求不能转任务，本次操作已过滤。";
$lang->story->errorERURSplitTask     = "父需求、{$lang->ERCommon}、{$lang->URCommon}不能转任务，本次操作已过滤。";
$lang->story->errorEmptyReviewedBy   = "『{$lang->story->reviewers}』不能为空。";
$lang->story->errorEmptyStory        = "已有相同标题的需求或标题为空，请检查输入。";
$lang->story->mustChooseResult       = '必须选择评审意见';
$lang->story->mustChoosePreVersion   = '必须选择回溯的版本';
$lang->story->noEpic                 = "暂时没有{$lang->ERCommon}。";
$lang->story->noStory                = "暂时没有{$lang->SRCommon}。";
$lang->story->noRequirement          = "暂时没有{$lang->URCommon}。";
$lang->story->ignoreChangeStage      = "{$lang->SRCommon} %s 状态为草稿或已关闭，本次操作已被过滤。";
$lang->story->cannotDeleteParent     = "不能删除父{$lang->SRCommon}";
$lang->story->moveChildrenTips       = "确认修改所属产品吗，修改后，需求的所有子级需求也会跟随变更。";
$lang->story->changeTips             = '该软件需求关联的用户需求有变更，点击“不变更”忽略此条变更，点击“变更”来进行该软件需求的变更。';
$lang->story->estimateMustBeNumber   = '估算值必须是数字';
$lang->story->estimateMustBePlus     = '估算值不能是负数';
$lang->story->confirmChangeBranch    = $lang->SRCommon . '%s已关联在之前所属分支的计划中，调整分支后，' . $lang->SRCommon . '将从之前所属分支的计划中移除，请确认是否继续修改上述' . $lang->SRCommon . '的分支。';
$lang->story->confirmChangePlan      = $lang->SRCommon . '%s已关联在之前计划的所属分支中，调整分支后，' . $lang->SRCommon . '将会从计划中移除，请确认是否继续修改计划的所属分支。';
$lang->story->errorDuplicateStory    = $lang->SRCommon . '%s不存在';
$lang->story->confirmRecallChange    = "撤销变更后，需求内容会回退至变更前的版本，您确定要撤销吗？";
$lang->story->confirmRecallReview    = "您确定要撤回评审吗？";
$lang->story->noStoryToTask          = "状态不是激活的{$lang->SRCommon}和父{$lang->SRCommon}无法转为任务！";
$lang->story->ignoreClosedStory      = "{$lang->SRCommon} %s 状态为已关闭，本次操作已被过滤。";
$lang->story->changeProductTips      = "确认修改所属产品吗，修改后，需求的所有子级需求也会跟随变更。";
$lang->story->gradeOverflow          = "系统检测该需求下子需求的最大层级为%s，同步修改后为%s，超出系统设置的层级范围，无法修改。";
$lang->story->batchGradeOverflow     = "%s需求的父需求修改后，其子需求的层级会超出系统设置的层级范围，本次修改已将其忽略。";
$lang->story->batchGradeSameRoot     = '%s需求存在父子关系，将不会被修改需求层级。';
$lang->story->batchGradeGtParent     = '%s需求的层级不能高于其父需求，本次修改已将其忽略。';
$lang->story->batchParentError       = "%s需求的父需求不能为其本身或其子需求，本次修改已将其忽略。";
$lang->story->errorNoGradeSplit      = "没有可拆分的需求层级";

$lang->story->form = new stdclass();
$lang->story->form->area     = "该{$lang->SRCommon}所属范围";
$lang->story->form->desc     = "描述及标准，什么{$lang->SRCommon}？如何验收？";
$lang->story->form->resource = '资源分配，有谁完成？需要多少时间？';
$lang->story->form->file     = "附件，如果该{$lang->SRCommon}有相关文件，请点此上传。";

$lang->story->action = new stdclass();
$lang->story->action->reviewed              = array('main' => '$date, 由 <strong>$actor</strong> 记录评审意见，评审意见为 <strong>$extra</strong>。', 'extra' => 'reviewResultList');
$lang->story->action->rejectreviewed        = array('main' => '$date, 由 <strong>$actor</strong> 记录评审意见，评审意见为 <strong>$extra</strong>，原因为 <strong>$reason</strong>。', 'extra' => 'reviewResultList', 'reason' => 'reasonList');
$lang->story->action->recalled              = array('main' => '$date, 由 <strong>$actor</strong> 撤销评审。');
$lang->story->action->closed                = array('main' => '$date, 由 <strong>$actor</strong> 关闭，原因为 <strong>$extra</strong> $appendLink。', 'extra' => 'reasonList');
$lang->story->action->closedbysystem        = array('main' => '$date, 系统判定由于关闭了所有子需求，自动关闭父需求。');
$lang->story->action->closedbyparent        = array('main' => '$date, 系统判定由于关闭了父需求，自动关闭子需求。');
$lang->story->action->reviewpassed          = array('main' => '$date, 由 <strong>系统</strong> 判定，结果为 <strong>确认通过</strong>。');
$lang->story->action->reviewrejected        = array('main' => '$date, 由 <strong>系统</strong> 关闭，原因为 <strong>拒绝</strong>。');
$lang->story->action->reviewclarified       = array('main' => '$date, 由 <strong>系统</strong> 判定，结果为 <strong>有待明确</strong>，请编辑后重新发起评审。');
$lang->story->action->reviewreverted        = array('main' => '$date, 由 <strong>系统</strong> 判定，结果为 <strong>撤销变更</strong>。');
$lang->story->action->linked2plan           = array('main' => '$date, 由 <strong>$actor</strong> 关联到计划 <strong>$extra</strong>。');
$lang->story->action->unlinkedfromplan      = array('main' => '$date, 由 <strong>$actor</strong> 从计划 <strong>$extra</strong> 移除。');
$lang->story->action->linked2execution      = array('main' => '$date, 由 <strong>$actor</strong> 关联到' . $lang->executionCommon . ' <strong>$extra</strong>。');
$lang->story->action->unlinkedfromexecution = array('main' => '$date, 由 <strong>$actor</strong> 从' . $lang->executionCommon . ' <strong>$extra</strong> 移除。');
$lang->story->action->linked2kanban         = array('main' => '$date, 由 <strong>$actor</strong> 关联到看板 <strong>$extra</strong>。');
$lang->story->action->linked2project        = array('main' => '$date, 由 <strong>$actor</strong> ' . "关联到{$lang->projectCommon}" . ' <strong>$extra</strong>。');
$lang->story->action->unlinkedfromproject   = array('main' => '$date, 由 <strong>$actor</strong> ' . "从{$lang->projectCommon}" . '<strong>$extra</strong> 移除。');
$lang->story->action->linked2build          = array('main' => '$date, 由 <strong>$actor</strong> 关联到构建 <strong>$extra</strong>。');
$lang->story->action->unlinkedfrombuild     = array('main' => '$date, 由 <strong>$actor</strong> 从构建 <strong>$extra</strong> 移除。');
$lang->story->action->linked2release        = array('main' => '$date, 由 <strong>$actor</strong> 关联到发布 <strong>$extra</strong>。');
$lang->story->action->unlinkedfromrelease   = array('main' => '$date, 由 <strong>$actor</strong> 从发布 <strong>$extra</strong> 移除。');
$lang->story->action->linked2revision       = array('main' => '$date, 由 <strong>$actor</strong> 关联到代码提交 <strong>$extra</strong>');
$lang->story->action->unlinkedfromrevision  = array('main' => '$date, 由 <strong>$actor</strong> 取消关联到代码提交 <strong>$extra</strong>');
$lang->story->action->linkrelatedstory      = array('main' => "\$date, 由 <strong>\$actor</strong> 关联相关需求 <strong>\$extra</strong>。");
$lang->story->action->subdividestory        = array('main' => "\$date, 由 <strong>\$actor</strong> 拆分为{$lang->SRCommon}   <strong>\$extra</strong>。");
$lang->story->action->unlinkrelatedstory    = array('main' => "\$date, 由 <strong>\$actor</strong> 移除相关需求 <strong>\$extra</strong>。");
$lang->story->action->unlinkchildstory      = array('main' => "\$date, 由 <strong>\$actor</strong> 移除拆分{$lang->SRCommon} <strong>\$extra</strong>。");
$lang->story->action->recalledchange        = array('main' => "\$date, 由 <strong>\$actor</strong> 撤销变更。");
$lang->story->action->synctwins             = array('main' => "\$date, 系统判断由于孪生需求 <strong>\$extra</strong> \$operate，本需求同步调整。", 'operate' => 'operateList');
$lang->story->action->syncgrade             = array('main' => "\$date, 系统判断由于父需求层级变动，本需求层级同步修改为 <strong>\$extra</strong>。");
$lang->story->action->linked2roadmap        = array('main' => '$date, 由 <strong>$actor</strong> 关联到路标 <strong>$extra</strong>。');
$lang->story->action->unlinkedfromroadmap   = array('main' => '$date, 由 <strong>$actor</strong> 从路标 <strong>$extra</strong> 移除。');
$lang->story->action->changedbycharter      = array('main' => '$date, 由 <strong>$actor</strong> 通过立项申请 <strong>$extra</strong> ，需求阶段同步调整为Charter立项。');

/* 统计报表。*/
$lang->story->report = new stdclass();
$lang->story->report->common = '报表';
$lang->story->report->select = '请选择报表类型';
$lang->story->report->create = '生成报表';
$lang->story->report->value  = "需求数";

$lang->story->report->charts['storiesPerProduct']      = $lang->productCommon . "{$lang->SRCommon}数量";
$lang->story->report->charts['storiesPerModule']       = "模块{$lang->SRCommon}数量";
$lang->story->report->charts['storiesPerSource']       = "按{$lang->SRCommon}来源统计";
$lang->story->report->charts['storiesPerPlan']         = "按计划进行统计";
$lang->story->report->charts['storiesPerStatus']       = '按状态进行统计';
$lang->story->report->charts['storiesPerStage']        = '按所处阶段进行统计';
$lang->story->report->charts['storiesPerPri']          = '按优先级进行统计';
$lang->story->report->charts['storiesPerEstimate']     = "按预计{$lang->hourCommon}进行统计";
$lang->story->report->charts['storiesPerOpenedBy']     = '按由谁创建来进行统计';
$lang->story->report->charts['storiesPerAssignedTo']   = '按当前指派来进行统计';
$lang->story->report->charts['storiesPerClosedReason'] = '按关闭原因来进行统计';
$lang->story->report->charts['storiesPerChange']       = '按变更次数来进行统计';
$lang->story->report->charts['storiesPerGrade']        = '按层级来进行统计';

$lang->story->report->options = new stdclass();
$lang->story->report->options->graph  = new stdclass();
$lang->story->report->options->type   = 'pie';
$lang->story->report->options->width  = 500;
$lang->story->report->options->height = 140;

$lang->story->report->storiesPerProduct      = new stdclass();
$lang->story->report->storiesPerModule       = new stdclass();
$lang->story->report->storiesPerSource       = new stdclass();
$lang->story->report->storiesPerPlan         = new stdclass();
$lang->story->report->storiesPerStatus       = new stdclass();
$lang->story->report->storiesPerStage        = new stdclass();
$lang->story->report->storiesPerPri          = new stdclass();
$lang->story->report->storiesPerOpenedBy     = new stdclass();
$lang->story->report->storiesPerAssignedTo   = new stdclass();
$lang->story->report->storiesPerClosedReason = new stdclass();
$lang->story->report->storiesPerEstimate     = new stdclass();
$lang->story->report->storiesPerChange       = new stdclass();
$lang->story->report->storiesPerGrade        = new stdclass();

$lang->story->report->storiesPerProduct->item      = $lang->productCommon;
$lang->story->report->storiesPerModule->item       = '模块';
$lang->story->report->storiesPerSource->item       = '来源';
$lang->story->report->storiesPerPlan->item         = '计划';
$lang->story->report->storiesPerStatus->item       = '状态';
$lang->story->report->storiesPerStage->item        = '阶段';
$lang->story->report->storiesPerPri->item          = '优先级';
$lang->story->report->storiesPerOpenedBy->item     = '由谁创建';
$lang->story->report->storiesPerAssignedTo->item   = '指派给';
$lang->story->report->storiesPerClosedReason->item = '原因';
$lang->story->report->storiesPerEstimate->item     = "预计{$lang->hourCommon}";
$lang->story->report->storiesPerChange->item       = '变更次数';
$lang->story->report->storiesPerGrade->item        = '层级';

$lang->story->report->storiesPerProduct->graph      = new stdclass();
$lang->story->report->storiesPerModule->graph       = new stdclass();
$lang->story->report->storiesPerSource->graph       = new stdclass();
$lang->story->report->storiesPerPlan->graph         = new stdclass();
$lang->story->report->storiesPerStatus->graph       = new stdclass();
$lang->story->report->storiesPerStage->graph        = new stdclass();
$lang->story->report->storiesPerPri->graph          = new stdclass();
$lang->story->report->storiesPerOpenedBy->graph     = new stdclass();
$lang->story->report->storiesPerAssignedTo->graph   = new stdclass();
$lang->story->report->storiesPerClosedReason->graph = new stdclass();
$lang->story->report->storiesPerEstimate->graph     = new stdclass();
$lang->story->report->storiesPerChange->graph       = new stdclass();
$lang->story->report->storiesPerGrade->graph        = new stdclass();

$lang->story->report->storiesPerProduct->graph->xAxisName      = $lang->productCommon;
$lang->story->report->storiesPerModule->graph->xAxisName       = '模块';
$lang->story->report->storiesPerSource->graph->xAxisName       = '来源';
$lang->story->report->storiesPerPlan->graph->xAxisName         = '计划';
$lang->story->report->storiesPerStatus->graph->xAxisName       = '状态';
$lang->story->report->storiesPerStage->graph->xAxisName        = '所处阶段';
$lang->story->report->storiesPerPri->graph->xAxisName          = '优先级';
$lang->story->report->storiesPerOpenedBy->graph->xAxisName     = '由谁创建';
$lang->story->report->storiesPerAssignedTo->graph->xAxisName   = '当前指派';
$lang->story->report->storiesPerClosedReason->graph->xAxisName = '关闭原因';
$lang->story->report->storiesPerEstimate->graph->xAxisName     = '预计时间';
$lang->story->report->storiesPerChange->graph->xAxisName       = '变更次数';
$lang->story->report->storiesPerGrade->graph->xAxisName        = '层级';

$lang->story->placeholder = new stdclass();
$lang->story->placeholder->estimate = $lang->story->hour;

$lang->story->chosen = new stdClass();
$lang->story->chosen->reviewedBy = '选择评审人...';

$lang->story->notice = new stdClass();
$lang->story->notice->closed           = "您选择的{$lang->SRCommon}已经被关闭了！";
$lang->story->notice->reviewerNotEmpty = "该{$lang->SRCommon}需要评审，评审人员不能为空。";
$lang->story->notice->changePlan       = '所属计划只能改为一条，修改后才能保存成功。';
$lang->story->notice->notDeleted       = '已经评审过的人员不能删除。';

$lang->story->convertToTask = new stdClass();
$lang->story->convertToTask->fieldList = array();
$lang->story->convertToTask->fieldList['module']     = '所属模块';
$lang->story->convertToTask->fieldList['spec']       = "{$lang->SRCommon}描述";
$lang->story->convertToTask->fieldList['pri']        = '优先级';
$lang->story->convertToTask->fieldList['mailto']     = '抄送给';
$lang->story->convertToTask->fieldList['assignedTo'] = '指派给';

$lang->story->categoryList['feature']     = '功能';
$lang->story->categoryList['interface']   = '接口';
$lang->story->categoryList['performance'] = '性能';
$lang->story->categoryList['safe']        = '安全';
$lang->story->categoryList['experience']  = '体验';
$lang->story->categoryList['improve']     = '改进';
$lang->story->categoryList['other']       = '其他';

$lang->story->changeTip = "只有激活状态的需求，才能进行变更";

$lang->story->reviewTip = array();
$lang->story->reviewTip['active']      = "该需求已是激活状态，无需评审";
$lang->story->reviewTip['notReviewer'] = "您不是该需求的评审人员，无法进行评审操作";
$lang->story->reviewTip['reviewed']    = '您已评审';

$lang->story->recallTip = array();
$lang->story->recallTip['actived'] = "该需求未发起评审流程，无需撤销操作";

$lang->story->subDivideTip = array();
$lang->story->subDivideTip['notWait']    = "该需求%s，无法进行拆分操作";
$lang->story->subDivideTip['notActive']  = "评审中和已关闭的需求，无法进行拆分操作";
$lang->story->subDivideTip['twinsSplit'] = '孪生需求不可拆分';

$lang->story->featureBar['browse']['all']       = '全部';
$lang->story->featureBar['browse']['unclosed']  = $lang->story->unclosed;
$lang->story->featureBar['browse']['draft']     = $lang->story->statusList['draft'];
$lang->story->featureBar['browse']['reviewing'] = $lang->story->statusList['reviewing'];

$lang->story->operateList = array();
$lang->story->operateList['assigned']       = '指派';
$lang->story->operateList['closed']         = '关闭';
$lang->story->operateList['activated']      = '激活';
$lang->story->operateList['changed']        = '变更';
$lang->story->operateList['reviewed']       = '评审';
$lang->story->operateList['edited']         = '编辑';
$lang->story->operateList['submitreview']   = '提交评审';
$lang->story->operateList['recalledchange'] = '撤销变更';
$lang->story->operateList['recalled']       = '撤销评审';

$lang->story->addBranch      = '添加%s';
$lang->story->deleteBranch   = '删除%s';
$lang->story->notice->branch = "每个分支会建立一个需求，需求间互为孪生关系。孪生需求间除{$lang->productCommon}、分支、模块、计划、阶段字段外均保持同步，后期您可以手动解除孪生关系。";

$lang->story->relievedTwinsRelation     = '解除孪生关系';
$lang->story->relievedTwinsRelationTips = '孪生关系解除后无法恢复，需求的关闭将不再同步。';
$lang->story->changeRelievedTwinsTips   = '孪生关系解除后无法恢复，孪生需求间内容不再同步。';
$lang->story->cannotRejectTips          = '"%s"为已变更的需求，无法评审为拒绝，本次操作已被过滤。';

$lang->story->trackOrderByList['id']       = '按ID';
$lang->story->trackOrderByList['pri']      = '按优先级';
$lang->story->trackOrderByList['status']   = '按状态';
$lang->story->trackOrderByList['stage']    = '按阶段';
$lang->story->trackOrderByList['category'] = '按类别';

$lang->story->trackSortList['asc']  = '升序';
$lang->story->trackSortList['desc'] = '降序';

$lang->story->error = new stdclass();
$lang->story->error->length = "长度超过了%d个字符，无法保存，请修改后再试";
