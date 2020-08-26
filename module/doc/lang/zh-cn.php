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
$lang->doc->editedBy       = '由谁更新';
$lang->doc->editedDate     = '更新时间';
$lang->doc->version        = '版本号';
$lang->doc->basicInfo      = '基本信息';
$lang->doc->deleted        = '已删除';
$lang->doc->fileObject     = '所属对象';
$lang->doc->whiteList      = '白名单';
$lang->doc->contentType    = '文档格式';
$lang->doc->separator      = "<i class='icon-angle-right'></i>";
$lang->doc->fileTitle      = '附件名';
$lang->doc->filePath       = '地址';
$lang->doc->extension      = '类型';
$lang->doc->size           = '大小';
$lang->doc->download       = '下载';
$lang->doc->acl            = '权限';
$lang->doc->fileName       = '附件';
$lang->doc->groups         = '分组';
$lang->doc->users          = '用户';
$lang->doc->item           = '项';
$lang->doc->num            = '文档数量';
$lang->doc->searchResult   = '搜索结果';

$lang->doc->moduleDoc      = '按模块浏览';
$lang->doc->searchDoc      = '搜索';
$lang->doc->fast           = '快速访问';
$lang->doc->allDoc         = '所有文档';
$lang->doc->openedByMe     = '由我创建';
$lang->doc->orderByOpen    = '最近添加';
$lang->doc->orderByEdit    = '最近更新';
$lang->doc->orderByVisit   = '最近访问';
$lang->doc->todayEdited    = '今日更新';
$lang->doc->pastEdited     = '往日更新';
$lang->doc->myDoc          = '我的文档';
$lang->doc->myCollection   = '我的收藏';

/* 方法列表。*/
$lang->doc->index            = '文档主页';
$lang->doc->create           = '创建文档';
$lang->doc->edit             = '编辑文档';
$lang->doc->delete           = '删除文档';
$lang->doc->browse           = '文档列表';
$lang->doc->view             = '文档详情';
$lang->doc->diff             = '对比';
$lang->doc->diffAction       = '对比文档';
$lang->doc->sort             = '文档排序';
$lang->doc->manageType       = '维护分类';
$lang->doc->editType         = '编辑分类';
$lang->doc->deleteType       = '删除分类';
$lang->doc->addType          = '增加分类';
$lang->doc->childType        = '子分类';
$lang->doc->collect          = '收藏';
$lang->doc->cancelCollection = '取消收藏';
$lang->doc->deleteFile       = '删除附件';

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

$lang->doc->libIconList['product'] = 'icon-cube';
$lang->doc->libIconList['project'] = 'icon-stack';
$lang->doc->libIconList['custom']  = 'icon-folder-o';

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

$lang->doc->typeList['html']     = '富文本';
$lang->doc->typeList['markdown'] = 'Markdown';
$lang->doc->typeList['url']      = '链接';
$lang->doc->typeList['word']     = 'Word';
$lang->doc->typeList['ppt']      = 'PPT';
$lang->doc->typeList['excel']    = 'Excel';

$lang->doc->types['text'] = '文档';
$lang->doc->types['url']  = '链接';

$lang->doc->contentTypeList['html']     = 'HTML';
$lang->doc->contentTypeList['markdown'] = 'MarkDown';

$lang->doc->browseType             = '浏览方式';
$lang->doc->browseTypeList['list'] = '列表';
$lang->doc->browseTypeList['grid'] = '目录';

$lang->doc->fastMenuList['byediteddate']  = '最近更新';
//$lang->doc->fastMenuList['visiteddate']   = '最近访问';
$lang->doc->fastMenuList['openedbyme']    = '我的文档';
$lang->doc->fastMenuList['collectedbyme'] = '我的收藏';

$lang->doc->fastMenuIconList['byediteddate']  = 'icon-folder-upload';
//$lang->doc->fastMenuIconList['visiteddate']   = 'icon-folder-move';
$lang->doc->fastMenuIconList['openedbyme']    = 'icon-folder-account';
$lang->doc->fastMenuIconList['collectedbyme'] = 'icon-folder-star';

$lang->doc->customObjectLibs['files']       = '显示附件库';
$lang->doc->customObjectLibs['customFiles'] = '显示自定义文档库';

$lang->doc->orderLib                       = '文档库排序';
$lang->doc->customShowLibs                 = '显示设置';
$lang->doc->customShowLibsList['zero']     = '显示空文档的库';
$lang->doc->customShowLibsList['children'] = '显示子分类的文档';
$lang->doc->customShowLibsList['unclosed'] = '只显示未关闭的项目';

$lang->doc->confirmDelete      = "您确定删除该文档吗？";
$lang->doc->confirmDeleteLib   = "您确定删除该文档库吗？";
$lang->doc->errorEditSystemDoc = "系统文档库无需修改。";
$lang->doc->errorEmptyProduct  = "没有{$lang->productCommon}，无法创建文档";
$lang->doc->errorEmptyProject  = "没有{$lang->projectCommon}，无法创建文档";
$lang->doc->errorMainSysLib    = "该系统文档库不能删除！";
$lang->doc->accessDenied       = "您没有权限访问！";
$lang->doc->versionNotFount    = '该版本文档不存在';
$lang->doc->noDoc              = '暂时没有文档。';
$lang->doc->cannotCreateOffice = '<p>对不起，企业版才能创建%s文档。<p><p>试用企业版，请联系我们：4006-8899-23 &nbsp; 0532-86893032。</p>';
$lang->doc->notSetOffice       = "<p>创建%s文档，需要配置<a href='%s' target='_parent'>Office转换设置</a>。<p>";
$lang->doc->noSearchedDoc      = '没有搜索到任何文档。';
$lang->doc->noEditedDoc        = '您还没有编辑任何文档。';
$lang->doc->noOpenedDoc        = '您还没有创建任何文档。';
$lang->doc->noCollectedDoc     = '您还没有收藏任何文档。';

$lang->doc->noticeAcl['lib']['product']['default'] = '有所选产品访问权限的用户可以访问。';
$lang->doc->noticeAcl['lib']['product']['custom']  = '有所选产品访问权限或白名单里的用户可以访问。';
$lang->doc->noticeAcl['lib']['project']['default'] = "有所选{$lang->projectCommon}访问权限的用户可以访问。";
$lang->doc->noticeAcl['lib']['project']['custom']  = "有所选{$lang->projectCommon}访问权限或白名单里的用户可以访问。";
$lang->doc->noticeAcl['lib']['custom']['open']     = '所有人都可以访问。';
$lang->doc->noticeAcl['lib']['custom']['custom']   = '白名单的用户可以访问。';
$lang->doc->noticeAcl['lib']['custom']['private']  = '只有创建者自己可以访问。';

$lang->doc->noticeAcl['doc']['open']    = '有文档所属文档库访问权限的，都可以访问。';
$lang->doc->noticeAcl['doc']['custom']  = '白名单的用户可以访问。';
$lang->doc->noticeAcl['doc']['private'] = '只有创建者自己可以访问。';

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

$lang->doclib->aclListA['default'] = '默认';
$lang->doclib->aclListA['custom']  = '自定义';

$lang->doclib->aclListB['open']    = '公开';
$lang->doclib->aclListB['custom']  = '自定义';
$lang->doclib->aclListB['private'] = '私有';

$lang->doclib->create['product'] = '创建' . $lang->productCommon . '文档库';
$lang->doclib->create['project'] = '创建' . $lang->projectCommon . '文档库';
$lang->doclib->create['custom']  = '创建自定义文档库';

$lang->doclib->main['product'] = $lang->productCommon . '主库';
$lang->doclib->main['project'] = $lang->projectCommon . '主库';

$lang->doclib->tabList['product'] = $lang->productCommon;
$lang->doclib->tabList['project'] = $lang->projectCommon;
$lang->doclib->tabList['custom']  = '自定义';

$lang->doclib->nameList['custom'] = '自定义文档库名称';
