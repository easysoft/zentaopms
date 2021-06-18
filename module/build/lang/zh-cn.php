<?php
/**
 * The build module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     build
 * @version     $Id: zh-cn.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
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

$lang->build->confirmDelete      = "您确认删除该版本吗？";
$lang->build->confirmUnlinkStory = "您确认移除该{$lang->SRCommon}吗？";
$lang->build->confirmUnlinkBug   = "您确认移除该Bug吗？";

$lang->build->basicInfo = '基本信息';

$lang->build->id             = 'ID';
$lang->build->product        = $lang->productCommon;
$lang->build->branch         = '平台/分支';
$lang->build->execution      = '所属' . $lang->executionCommon;
$lang->build->name           = '名称编号';
$lang->build->date           = '打包日期';
$lang->build->builder        = '构建者';
$lang->build->scmPath        = '源代码地址';
$lang->build->filePath       = '下载地址';
$lang->build->desc           = '描述';
$lang->build->files          = '上传发行包';
$lang->build->last           = '上个版本';
$lang->build->packageType    = '包类型';
$lang->build->unlinkStory    = "移除{$lang->SRCommon}";
$lang->build->unlinkBug      = '移除Bug';
$lang->build->stories        = "完成的{$lang->SRCommon}";
$lang->build->bugs           = '解决的Bug';
$lang->build->generatedBugs  = '产生的Bug';
$lang->build->noProduct      = " <span id='noProduct' style='color:red'>该{$lang->executionCommon}没有关联{$lang->productCommon}，无法创建版本，请先<a href='%s' data-app='%s' data-toggle='modal' data-type='iframe'>关联{$lang->productCommon}</a></span>";
$lang->build->noBuild        = '暂时没有版本。';
$lang->build->emptyExecution =  $lang->executionCommon . '不能为空。';

$lang->build->notice = new stdclass();
$lang->build->notice->changeProduct   = "已经关联{$lang->SRCommon}、Bug或提交测试单的版本，不能修改其所属{$lang->productCommon}";
$lang->build->notice->changeExecution = "提交测试单的版本，不能修改其所属{$lang->executionCommon}";

$lang->build->finishStories = " 本次共完成 %s 个{$lang->SRCommon}";
$lang->build->resolvedBugs  = ' 本次共解决 %s 个Bug';
$lang->build->createdBugs   = ' 本次共产生 %s 个Bug';

$lang->build->placeholder = new stdclass();
$lang->build->placeholder->scmPath  = ' 软件源代码库，如Subversion、Git库地址';
$lang->build->placeholder->filePath = ' 该版本软件包下载存储地址';

$lang->build->action = new stdclass();
$lang->build->action->buildopened = '$date, 由 <strong>$actor</strong> 创建版本 <strong>$extra</strong>。' . "\n";

$lang->backhome = '返回';
