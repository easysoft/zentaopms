<?php
/**
 * The doc module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     doc
 * @version     $Id: zh-cn.php 824 2010-05-02 15:32:06Z wwccss $
 * @link        http://www.zentao.net
 */
/* 字段列表。*/
$lang->doc->common         = '文档';
$lang->doc->id             = '文档编号';
$lang->doc->product        = '所属' . $lang->productCommon;
$lang->doc->project        = '所属' . $lang->projectCommon;
$lang->doc->lib            = '所属文档库';
$lang->doc->module         = '所属分类';
$lang->doc->title          = '文档标题';
$lang->doc->digest         = '文档摘要';
$lang->doc->comment        = '文档备注';
$lang->doc->type           = '文档类型';
$lang->doc->content        = '文档正文';
$lang->doc->keywords       = '关键字';
$lang->doc->url            = '文档URL';
$lang->doc->files          = '附件';
$lang->doc->addedBy        = '由谁添加';
$lang->doc->addedDate      = '添加时间';
$lang->doc->editedBy       = '由谁编辑';
$lang->doc->editedDate     = '编辑时间';
$lang->doc->version        = '版本号';
$lang->doc->basicInfo      = '基本信息';
$lang->doc->deleted        = '已删除';
$lang->doc->fileObject     = '所属对象';
$lang->doc->whiteList      = '白名单';
$lang->doc->contentType    = '文档格式';
$lang->doc->separator      = "<i class='icon-angle-right'></i>";
$lang->doc->fileTitle      = '附件名';

$lang->doc->moduleDoc      = '按模块浏览';
$lang->doc->searchDoc      = '搜索';
$lang->doc->allDoc         = '所有文档';
$lang->doc->openedByMe     = '由我创建';
$lang->doc->orderByOpen    = '最近添加';
$lang->doc->orderByEdit    = '最近修改';

/* 方法列表。*/
$lang->doc->index          = '首页';
$lang->doc->create         = '创建文档';
$lang->doc->edit           = '编辑文档';
$lang->doc->delete         = '删除文档';
$lang->doc->browse         = '文档列表';
$lang->doc->view           = '文档详情';
$lang->doc->diff           = '对比';
$lang->doc->sort           = '排序';
$lang->doc->manageType     = '维护分类';
$lang->doc->editType       = '编辑分类';
$lang->doc->deleteType     = '删除分类';
$lang->doc->addType        = '增加分类';

$lang->doc->libName        = '文档库名称';
$lang->doc->libType        = '文档库类型';
$lang->doc->custom         = '自定义文档库';
$lang->doc->customAB       = '自定义库';
$lang->doc->createLib      = '创建文档库';
$lang->doc->allLibs        = '文档库列表';
$lang->doc->objectLibs     = "{$lang->productCommon}/{$lang->projectCommon}库列表";
$lang->doc->showFiles      = '附件库';
$lang->doc->editLib        = '编辑文档库';
$lang->doc->deleteLib      = '删除文档库';
$lang->doc->fixedMenu      = '固定到菜单栏';
$lang->doc->removeMenu     = '从菜单栏移除';
$lang->doc->search         = '搜索';

/* 查询条件列表 */
$lang->doc->allProduct     = '所有' . $lang->productCommon;
$lang->doc->allProject     = '所有' . $lang->projectCommon;

$lang->doc->libTypeList['product'] = $lang->productCommon . '文档库';
$lang->doc->libTypeList['project'] = $lang->projectCommon . '文档库';
$lang->doc->libTypeList['custom']  = '自定义文档库';

$lang->doc->systemLibs['product'] = $lang->productCommon;
$lang->doc->systemLibs['project'] = $lang->projectCommon;

global $config;
if($config->global->flow == 'onlyStory' or $config->global->flow == 'onlyTest') unset($lang->doc->systemLibs['project']);
if($config->global->flow == 'onlyStory' or $config->global->flow == 'onlyTest') unset($lang->doc->libTypeList['project']);
if($config->global->flow == 'onlyTask')  unset($lang->doc->systemLibs['product']);
if($config->global->flow == 'onlyTask')  unset($lang->doc->libTypeList['product']);

$lang->doc->aclList['open']    = '公开';
$lang->doc->aclList['custom']  = '自定义';
$lang->doc->aclList['private'] = '私有';

$lang->doc->types['text'] = '文档';
$lang->doc->types['url']  = '链接';

$lang->doc->contentTypeList['html']     = 'HTML';
$lang->doc->contentTypeList['markdown'] = 'MarkDown';

$lang->doc->browseType             = '浏览方式';
$lang->doc->browseTypeList['list'] = '列表';
$lang->doc->browseTypeList['menu'] = '目录';
$lang->doc->browseTypeList['tree'] = '树状图';

$lang->doc->confirmDelete      = "您确定删除该文档吗？";
$lang->doc->confirmDeleteLib   = "您确定删除该文档库吗？";
$lang->doc->errorEditSystemDoc = "系统文档库无需修改。";
$lang->doc->errorEmptyProduct  = "没有{$lang->productCommon}，无法创建文档";
$lang->doc->errorEmptyProject  = "没有{$lang->projectCommon}，无法创建文档";
$lang->doc->errorMainSysLib    = "该系统文档库不能删除！";
$lang->doc->accessDenied       = "您没有权限访问！";
$lang->doc->versionNotFount    = '该版本文档不存在';

$lang->doc->placeholder = new stdclass();
$lang->doc->placeholder->url = '相应的链接地址';

$lang->doclib = new stdclass();
$lang->doclib->name    = '文档库名称';
$lang->doclib->control = '访问控制';
$lang->doclib->group   = '分组';
$lang->doclib->user    = '用户';
$lang->doclib->files   = '附件库';
$lang->doclib->all     = '所有文档库';
$lang->doclib->select  = '选择文档库';
$lang->doclib->project = $lang->projectCommon . '库';
$lang->doclib->product = $lang->productCommon . '库';

$lang->doclib->main['product'] = $lang->productCommon . '主库';
$lang->doclib->main['project'] = $lang->projectCommon . '主库';
