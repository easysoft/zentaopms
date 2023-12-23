<?php
/**
 * The build module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id: zh-cn.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
$lang->build->common           = "版本";
$lang->build->create           = "创建版本";
$lang->build->edit             = "编辑版本";
$lang->build->linkStory        = "关联{$lang->SRCommon}";
$lang->build->linkBug          = "关联Bug";
$lang->build->delete           = "删除版本";
$lang->build->deleted          = "已删除";
$lang->build->view             = "版本详情";
$lang->build->batchUnlink      = '批量移除';
$lang->build->batchUnlinkStory = "批量移除{$lang->SRCommon}";
$lang->build->batchUnlinkBug   = '批量移除Bug';
$lang->build->viewBug          = '查看Bug';
$lang->build->bugList          = 'Bug列表';
$lang->build->linkArtifactRepo = '关联制品库';

$lang->build->confirmDelete      = "您确认删除该版本吗？";
$lang->build->confirmUnlinkStory = "您确认移除该{$lang->SRCommon}吗？";
$lang->build->confirmUnlinkBug   = "您确认移除该Bug吗？";

$lang->build->basicInfo = '基本信息';

$lang->build->id             = 'ID';
$lang->build->product        = '所属' . $lang->productCommon;
$lang->build->project        = '所属' . $lang->projectCommon;
$lang->build->branch         = '平台/分支';
$lang->build->branchAll      = '所有关联%s';
$lang->build->branchName     = '所属%s';
$lang->build->execution      = '所属' . $lang->executionCommon;
$lang->build->executionAB    = '所属执行';
$lang->build->integrated     = '集成版本';
$lang->build->singled        = '单一版本';
$lang->build->builds         = '包含版本';
$lang->build->released       = '发布';
$lang->build->name           = '名称编号';
$lang->build->nameAB         = '名称';
$lang->build->date           = '打包日期';
$lang->build->builder        = '构建者';
$lang->build->url            = '地址';
$lang->build->scmPath        = '源代码地址';
$lang->build->filePath       = '下载地址';
$lang->build->desc           = '描述';
$lang->build->mailto         = 'Mailto';
$lang->build->files          = '上传发行包';
$lang->build->last           = '上个版本';
$lang->build->packageType    = '包类型';
$lang->build->unlinkStory    = "移除{$lang->SRCommon}";
$lang->build->unlinkBug      = '移除Bug';
$lang->build->stories        = "完成的{$lang->SRCommon}";
$lang->build->bugs           = '解决的Bug';
$lang->build->generatedBugs  = '产生的Bug';
$lang->build->noProduct      = " <span id='noProduct' style='color:red'>该{$lang->executionCommon}没有关联{$lang->productCommon}，无法创建版本，请先<a data-url='%s' data-app='%s' data-toggle='modal' class='cursor-pointer'>关联{$lang->productCommon}</a></span>";
$lang->build->noBuild        = '暂时没有版本。';
$lang->build->emptyExecution = $lang->executionCommon . '不能为空。';
$lang->build->linkedBuild    = '关联版本';
$lang->build->createTest     = '提交测试';

$lang->build->notice = new stdclass();
$lang->build->notice->changeProduct   = "已经关联{$lang->SRCommon}、Bug或提交测试单的版本，不能修改其所属{$lang->productCommon}";
$lang->build->notice->changeExecution = "提交测试单的版本，不能修改其所属{$lang->executionCommon}";
$lang->build->notice->changeBuilds    = "提交测试单的版本，不能修改关联版本";
$lang->build->notice->autoRelation    = "相关版本下完成的需求、解决的Bug、产生的Bug将会自动关联到{$lang->projectCommon}版本中";
$lang->build->notice->createTest      = "该版本所属执行已删除，不能提交测试";

$lang->build->confirmChangeBuild = "%s『%s』解除关联后，%s下 %s个{$lang->SRCommon}和%s个Bug将同步从版本移除，是否解除？";
$lang->build->confirmRemoveStory = "%s『%s』解除关联后，%s下 %s个{$lang->SRCommon}将同步从计划中移除，是否解除？";
$lang->build->confirmRemoveBug   = "%s『%s』解除关联后，%s下 %s个Bug将同步从计划中移除，是否解除？";
$lang->build->confirmRemoveTips  = "确认删除%s『%s』吗？";

$lang->build->finishStories = " 本次共完成 %s 个{$lang->SRCommon}";
$lang->build->resolvedBugs  = ' 本次共解决 %s 个Bug';
$lang->build->createdBugs   = ' 本次共产生 %s 个Bug';

$lang->build->placeholder = new stdclass();
$lang->build->placeholder->scmPath        = ' 软件源代码库，如Subversion、Git库地址';
$lang->build->placeholder->filePath       = ' 该版本软件包下载存储地址';
$lang->build->placeholder->multipleSelect = "版本支持多选";

$lang->build->action = new stdclass();
$lang->build->action->buildopened = '$date, 由 <strong>$actor</strong> 创建版本 <strong>$extra</strong>。' . "\n";

$lang->backhome = '返回';

$lang->build->isIntegrated = array();
$lang->build->isIntegrated['no']  = '否';
$lang->build->isIntegrated['yes'] = '是';
