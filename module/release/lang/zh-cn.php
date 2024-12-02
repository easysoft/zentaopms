<?php
/**
 * The release module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     release
 * @version     $Id: zh-cn.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
$lang->release->create           = '创建发布';
$lang->release->edit             = '编辑发布';
$lang->release->linkStory        = "关联{$lang->SRCommon}";
$lang->release->linkBug          = '关联Bug';
$lang->release->delete           = '删除发布';
$lang->release->deleted          = '已删除';
$lang->release->view             = '发布详情';
$lang->release->browse           = '发布列表';
$lang->release->publish          = '发布';
$lang->release->changeStatus     = '修改状态';
$lang->release->batchUnlink      = '批量移除';
$lang->release->batchUnlinkStory = "批量移除{$lang->SRCommon}";
$lang->release->batchUnlinkBug   = '批量移除Bug';
$lang->release->manageSystem     = '管理' . $lang->product->system;
$lang->release->addSystem        = '新建' . $lang->product->system;

$lang->release->confirmDelete      = '您确认删除该发布吗？';
$lang->release->syncFromBuilds     = "将构建中完成的{$lang->SRCommon}和已解决的Bug关联到发布下";
$lang->release->confirmUnlinkStory = "您确认移除该{$lang->SRCommon}吗？";
$lang->release->confirmUnlinkBug   = '您确认移除该Bug吗？';
$lang->release->existBuild         = '『构建』已经有『%s』这条记录了。您可以更改『发布名称』或者选择一个『构建』。';
$lang->release->noRelease          = '暂时没有发布。';
$lang->release->errorDate          = '发布日期不能大于今天。';
$lang->release->confirmActivate    = '您确认激活该发布吗？';
$lang->release->confirmTerminate   = '您确认停止维护该发布吗？';
$lang->release->confirmPublish     = '您确认发布该发布吗？';

$lang->release->basicInfo = '基本信息';

$lang->release->id             = 'ID';
$lang->release->product        = "所属{$lang->productCommon}";
$lang->release->branch         = '平台/分支';
$lang->release->project        = '所属' . $lang->projectCommon;
$lang->release->build          = '构建';
$lang->release->includedBuild  = '包含构建';
$lang->release->includedSystem = '包含' . $lang->product->system;
$lang->release->includedApp    = '被包含' . $lang->product->system;
$lang->release->relatedProject = '对应' . $lang->projectCommon;
$lang->release->system         = $lang->product->system;
$lang->release->selectSystem   = '选择' . $lang->product->system;
$lang->release->name           = $lang->product->system . '版本号';
$lang->release->marker         = '里程碑';
$lang->release->date           = '计划发布日期';
$lang->release->releasedDate   = '实际发布日期';
$lang->release->desc           = '描述';
$lang->release->files          = '附件';
$lang->release->status         = '发布状态';
$lang->release->subStatus      = '子状态';
$lang->release->last           = '最新版本号';
$lang->release->unlinkStory    = "移除{$lang->SRCommon}";
$lang->release->unlinkBug      = '移除Bug';
$lang->release->stories        = "完成的{$lang->SRCommon}";
$lang->release->bugs           = '解决的Bug';
$lang->release->leftBugs       = '遗留的Bug';
$lang->release->generatedBugs  = '遗留的Bug';
$lang->release->createdBy      = '由谁创建';
$lang->release->createdDate    = '创建时间';
$lang->release->finishStories  = "本次共完成 %s 个{$lang->SRCommon}";
$lang->release->resolvedBugs   = '本次共解决 %s 个Bug';
$lang->release->createdBugs    = '本次共遗留 %s 个Bug';
$lang->release->export         = '导出HTML';
$lang->release->yesterday      = '昨日发布';
$lang->release->all            = '所有';
$lang->release->allProject     = '所有项目';
$lang->release->notify         = '发送通知';
$lang->release->notifyUsers    = '通知人员';
$lang->release->mailto         = '抄送给';
$lang->release->mailContent    = '<p>尊敬的用户，您好！</p><p style="margin-left: 30px;">您反馈的如下需求和Bug已经在 %s版本中发布，请联系客户经理查看最新版本。</p>';
$lang->release->storyList      = '<p style="margin-left: 30px;">需求列表：%s。</p>';
$lang->release->bugList        = '<p style="margin-left: 30px;">Bug列表：%s。</p>';
$lang->release->pageAllSummary = '本页共 <strong>%s</strong> 个发布，已发布 <strong>%s</strong>，停止维护 <strong>%s</strong>。';
$lang->release->pageSummary    = "本页共 <strong>%s</strong> 个发布。";
$lang->release->fileName       = '文件名';
$lang->release->exportRange    = '要导出的数据';

$lang->release->storyTitle = '需求名称';
$lang->release->bugTitle   = 'Bug名称';

$lang->release->filePath = '下载地址：';
$lang->release->scmPath  = '版本库地址：';

$lang->release->exportTypeList['all']     = '所有';
$lang->release->exportTypeList['story']   = $lang->release->stories;
$lang->release->exportTypeList['bug']     = $lang->release->bugs;
$lang->release->exportTypeList['leftbug'] = $lang->release->leftBugs;

$lang->release->resultList['normal'] = '发布成功';
$lang->release->resultList['fail']   = '发布失败';

$lang->release->statusList['wait']      = '未开始';
$lang->release->statusList['normal']    = '已发布';
$lang->release->statusList['fail']      = '发布失败';
$lang->release->statusList['terminate'] = '停止维护';

$lang->release->changeStatusList['wait']      = '发布';
$lang->release->changeStatusList['normal']    = '激活';
$lang->release->changeStatusList['terminate'] = '停止维护';
$lang->release->changeStatusList['publish']   = '发布';
$lang->release->changeStatusList['active']    = '激活';
$lang->release->changeStatusList['pause']     = '停止维护';

$lang->release->action = new stdclass();
$lang->release->action->changestatus = array('main' => '$date, 由 <strong>$actor</strong> $extra。', 'extra' => 'changeStatusList');
$lang->release->action->notified     = array('main' => '$date, 由 <strong>$actor</strong> 发送通知。');
$lang->release->action->published    = array('main' => '$date, 由 <strong>$actor</strong> 发布，结果为<strong>$extra</strong>。', 'extra' => 'resultList');

$lang->release->notifyList['FB'] = "反馈者";
$lang->release->notifyList['PO'] = "{$lang->productCommon}负责人";
$lang->release->notifyList['QD'] = '测试负责人';
$lang->release->notifyList['SC'] = '需求提交人';
$lang->release->notifyList['ET'] = "所在{$lang->execution->common}团队成员";
$lang->release->notifyList['PT'] = "所在{$lang->projectCommon}团队成员";
$lang->release->notifyList['CT'] = "抄送给";

$lang->release->featureBar['browse']['all']       = '全部';
$lang->release->featureBar['browse']['wait']      = $lang->release->statusList['wait'];
$lang->release->featureBar['browse']['normal']    = $lang->release->statusList['normal'];
$lang->release->featureBar['browse']['fail']      = $lang->release->statusList['fail'];
$lang->release->featureBar['browse']['terminate'] = $lang->release->statusList['terminate'];

$lang->release->markerList[1] = '是';
$lang->release->markerList[0] = '否';

$lang->release->failTips = '部署/上线失败';
