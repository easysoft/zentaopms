<?php
/**
 * The doc module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     doc
 * @version     $Id: zh-cn.php 824 2010-05-02 15:32:06Z wwccss $
 * @link        https://www.zentao.net
 */
$lang->doclib = new stdclass();
$lang->doclib->name       = '库名称';
$lang->doclib->control    = '访问控制';
$lang->doclib->group      = '分组';
$lang->doclib->user       = '用户';
$lang->doclib->files      = '附件库';
$lang->doclib->all        = '所有文档库';
$lang->doclib->select     = '选择文档库';
$lang->doclib->execution  = $lang->executionCommon . '库';
$lang->doclib->product    = $lang->productCommon . '库';
$lang->doclib->apiLibName = '库名称';
$lang->doclib->privateACL = "私有 （仅创建者和有%s权限的白名单用户可访问）";

$lang->doclib->tip = new stdclass();
$lang->doclib->tip->selectExecution = "执行为空时，创建的库为{$lang->projectCommon}库";

$lang->doclib->type['wiki'] = 'Wiki文档库';
$lang->doclib->type['api']  = '接口库';

$lang->doclib->aclListA = array();
$lang->doclib->aclListA['default'] = '默认';
$lang->doclib->aclListA['custom']  = '自定义';

$lang->doclib->aclListB['open']    = '公开';
$lang->doclib->aclListB['custom']  = '自定义';
$lang->doclib->aclListB['private'] = '私有';

$lang->doclib->mySpaceAclList['private'] = "私有（仅创建者可访问）";

$lang->doclib->aclList = array();
$lang->doclib->aclList['open']    = "公开 （有文档视图权限即可访问）";
$lang->doclib->aclList['default'] = "默认 （有所选%s访问权限用户可以访问）";
$lang->doclib->aclList['private'] = "私有 （仅创建者和白名单用户可访问）";

$lang->doclib->create['product']   = '创建' . $lang->productCommon . '文档库';
$lang->doclib->create['execution'] = '创建' . $lang->executionCommon . '文档库';
$lang->doclib->create['custom']    = '创建自定义文档库';

$lang->doclib->main['product']   = $lang->productCommon . '主库';
$lang->doclib->main['project']   = "{$lang->projectCommon}主库";
$lang->doclib->main['execution'] = $lang->executionCommon . '主库';

$lang->doclib->tabList['product']   = $lang->productCommon;
$lang->doclib->tabList['execution'] = $lang->executionCommon;
$lang->doclib->tabList['custom']    = '自定义';

$lang->doclib->nameList['custom'] = '自定义文档库名称';

$lang->doclib->apiNameUnique = array();
$lang->doclib->apiNameUnique['product'] = '同一' . $lang->productCommon . '下的接口库中';
$lang->doclib->apiNameUnique['project'] = '同一' . $lang->projectCommon . '下的接口库中';
$lang->doclib->apiNameUnique['nolink']  = '独立接口库中';

/* 字段列表。*/
$lang->doc->common       = '文档';
$lang->doc->id           = 'ID';
$lang->doc->product      = '所属' . $lang->productCommon;
$lang->doc->project      = "所属{$lang->projectCommon}";
$lang->doc->execution    = '所属' . $lang->execution->common;
$lang->doc->lib          = '所属库';
$lang->doc->module       = '所属目录';
$lang->doc->libAndModule = '所属库&目录';
$lang->doc->object       = '所属对象';
$lang->doc->title        = '文档标题';
$lang->doc->digest       = '文档摘要';
$lang->doc->comment      = '文档备注';
$lang->doc->type         = '文档类型';
$lang->doc->content      = '文档正文';
$lang->doc->keywords     = '关键字';
$lang->doc->status       = '文档状态';
$lang->doc->url          = '文档URL';
$lang->doc->files        = '附件';
$lang->doc->addedBy      = '由谁添加';
$lang->doc->addedByAB    = '创建者';
$lang->doc->addedDate    = '创建日期';
$lang->doc->editedBy     = '修改者';
$lang->doc->editedDate   = '修改日期';
$lang->doc->editingDate  = '正在修改者和时间';
$lang->doc->lastEditedBy = '最后更新者';
$lang->doc->version      = '版本号';
$lang->doc->basicInfo    = '基本信息';
$lang->doc->deleted      = '已删除';
$lang->doc->fileObject   = '所属对象';
$lang->doc->whiteList    = '白名单';
$lang->doc->contentType  = '文档格式';
$lang->doc->separator    = "<i class='icon-angle-right'></i>";
$lang->doc->fileTitle    = '附件名称';
$lang->doc->filePath     = '地址';
$lang->doc->extension    = '类型';
$lang->doc->size         = '附件大小';
$lang->doc->source       = '来源';
$lang->doc->download     = '下载';
$lang->doc->acl          = '权限';
$lang->doc->fileName     = '附件';
$lang->doc->groups       = '分组';
$lang->doc->users        = '用户';
$lang->doc->item         = '项';
$lang->doc->num          = '文档数量';
$lang->doc->searchResult = '搜索结果';
$lang->doc->mailto       = '抄送给';
$lang->doc->noModule     = '文档库下没有目录和文档，请维护目录或者创建文档';
$lang->doc->noChapter    = '手册下没有章节和文章，请维护手册';
$lang->doc->views        = '浏览次数';
$lang->doc->draft        = '草稿';
$lang->doc->collector    = '收藏者';
$lang->doc->main         = '文档主库';
$lang->doc->order        = '排序';
$lang->doc->doc          = '文档';
$lang->doc->updateOrder  = '更新排序';
$lang->doc->update       = '更新';
$lang->doc->nextStep     = '下一步';
$lang->doc->closed       = '已关闭';
$lang->doc->saveDraft    = '存为草稿';
$lang->doc->position     = '所在位置';
$lang->doc->person       = '个人';
$lang->doc->team         = '团队';
$lang->doc->manage       = '文档管理';
$lang->doc->release      = '发布';

$lang->doc->moduleDoc     = '按模块浏览';
$lang->doc->searchDoc     = '搜索';
$lang->doc->fast          = '快速访问';
$lang->doc->allDoc        = '全部文档';
$lang->doc->allVersion    = '全部版本';
$lang->doc->openedByMe    = '我的创建';
$lang->doc->editedByMe    = '我的编辑';
$lang->doc->orderByOpen   = '最近添加';
$lang->doc->orderByEdit   = '最近更新';
$lang->doc->orderByVisit  = '最近访问';
$lang->doc->todayEdited   = '今日更新';
$lang->doc->pastEdited    = '往日更新';
$lang->doc->myDoc         = '我的文档';
$lang->doc->myView        = '最近浏览';
$lang->doc->myCollection  = '我的收藏';
$lang->doc->myCreation    = '我创建的';
$lang->doc->myEdited      = '我编辑的';
$lang->doc->myLib         = '我的个人库';
$lang->doc->tableContents = '目录';
$lang->doc->addCatalog    = '添加目录';
$lang->doc->editCatalog   = '编辑目录';
$lang->doc->deleteCatalog = '删除目录';
$lang->doc->sortCatalog   = '目录排序';
$lang->doc->docStatistic  = '文档统计';
$lang->doc->docCreated    = '创建的文档';
$lang->doc->docEdited     = '编辑的文档';
$lang->doc->docViews      = '被浏览量';
$lang->doc->docCollects   = '被收藏量';
$lang->doc->todayUpdated  = '今天更新';
$lang->doc->daysUpdated   = '%s天前更新';
$lang->doc->monthsUpdated = '%s月前更新';
$lang->doc->yearsUpdated  = '%s年前更新';
$lang->doc->viewCount     = '%s次浏览';
$lang->doc->collectCount  = '%s次收藏';

/* 方法列表。*/
$lang->doc->index            = '仪表盘';
$lang->doc->createAB         = '创建';
$lang->doc->create           = '创建文档';
$lang->doc->createOrUpload   = '创建/上传文档';
$lang->doc->edit             = '编辑文档';
$lang->doc->delete           = '删除文档';
$lang->doc->createBook       = '创建手册';
$lang->doc->browse           = '文档列表';
$lang->doc->view             = '文档详情';
$lang->doc->diff             = '对比';
$lang->doc->cancelDiff       = '取消对比';
$lang->doc->diffAction       = '对比文档';
$lang->doc->sort             = '文档排序';
$lang->doc->manageType       = '维护目录';
$lang->doc->editType         = '编辑目录';
$lang->doc->editChildType    = '维护子目录';
$lang->doc->deleteType       = '删除目录';
$lang->doc->addType          = '增加目录';
$lang->doc->childType        = '子目录';
$lang->doc->catalogName      = '目录名称';
$lang->doc->collect          = '收藏';
$lang->doc->cancelCollection = '取消收藏';
$lang->doc->deleteFile       = '删除附件';
$lang->doc->menuTitle        = '目录';
$lang->doc->api              = '接口';
$lang->doc->displaySetting   = '显示设置';
$lang->doc->collectAction    = '收藏文档';

$lang->doc->libName           = '库名称';
$lang->doc->libType           = '库类型';
$lang->doc->custom            = '自定义文档库';
$lang->doc->customAB          = '自定义库';
$lang->doc->createLib         = '创建库';
$lang->doc->allLibs           = '库列表';
$lang->doc->objectLibs        = "库文档详情";
$lang->doc->showFiles         = '附件库';
$lang->doc->editLib           = '编辑库';
$lang->doc->deleteLib         = '删除库';
$lang->doc->fixedMenu         = '固定到菜单栏';
$lang->doc->removeMenu        = '从菜单栏移除';
$lang->doc->search            = '搜索';
$lang->doc->allCollections    = '查看全部收藏文档';
$lang->doc->keywordsTips      = '多个关键字请用逗号分隔。';
$lang->doc->sortLibs          = '文档库排序';
$lang->doc->titlePlaceholder  = '请输入标题';
$lang->doc->confirm           = '确认';
$lang->doc->docSummary        = '本页共 <strong>%s</strong> 个文档。';
$lang->doc->docCheckedSummary = '共选中 <strong>%total%</strong> 个文档。';
$lang->doc->showDoc           = '是否显示文档';
$lang->doc->uploadFile        = '上传文件';
$lang->doc->uploadDoc         = '上传文档';
$lang->doc->uploadFormat      = '上传格式';
$lang->doc->editedList        = '文档编辑者';

$lang->doc->uploadFormatList = array();
$lang->doc->uploadFormatList['separateDocs'] = '每个文件存为不同文档';
$lang->doc->uploadFormatList['combinedDocs'] = '所有文件存为一个文档';

$lang->doc->fileType = new stdclass();
$lang->doc->fileType->stepResult = '测试结果';

global $config;
/* 查询条件列表 */
$lang->doc->allProduct    = '所有' . $lang->productCommon;
$lang->doc->allExecutions = '所有' . $lang->execution->common;
$lang->doc->allProjects   = '所有' . $lang->projectCommon;

$lang->doc->libTypeList['product']   = $lang->productCommon . '文档库';
$lang->doc->libTypeList['project']   = "{$lang->projectCommon}文档库";
$lang->doc->libTypeList['execution'] = $lang->execution->common . '文档库';
$lang->doc->libTypeList['api']       = '接口库';
$lang->doc->libTypeList['custom']    = '自定义文档库';

$lang->doc->libGlobalList['api'] = '接口文档库';

$lang->doc->libIconList['product']   = 'icon-product';
$lang->doc->libIconList['execution'] = 'icon-stack';
$lang->doc->libIconList['custom']    = 'icon-folder-o';

$lang->doc->systemLibs['product']   = $lang->productCommon;
$lang->doc->systemLibs['execution'] = $lang->executionCommon;

$lang->doc->statusList['']       = "";
$lang->doc->statusList['normal'] = "已发布";
$lang->doc->statusList['draft']  = "草稿";

$lang->doc->aclList['open']    = "公开（有所属库权限即可访问）";
$lang->doc->aclList['private'] = "私有（仅创建者和白名单用户可访问）";

$lang->doc->space = '所属空间';
$lang->doc->spaceList['mine']    = '我的空间';
$lang->doc->spaceList['product'] = $lang->productCommon . '空间';
$lang->doc->spaceList['project'] = $lang->projectCommon . '空间';
$lang->doc->spaceList['api']     = '接口空间';
$lang->doc->spaceList['custom']  = '团队空间';

$lang->doc->apiType = '接口类型';
$lang->doc->apiTypeList['product'] = $lang->productCommon . '接口';
$lang->doc->apiTypeList['project'] = $lang->projectCommon . '接口';
$lang->doc->apiTypeList['nolink']  = '独立接口';

$lang->doc->typeList['html']     = '富文本';
$lang->doc->typeList['markdown'] = 'Markdown';
$lang->doc->typeList['url']      = '链接';
$lang->doc->typeList['word']     = 'Word';
$lang->doc->typeList['ppt']      = 'PPT';
$lang->doc->typeList['excel']    = 'Excel';

$lang->doc->createList['template']   = 'Wiki文档';
$lang->doc->createList['word']       = 'Word';
$lang->doc->createList['ppt']        = 'PPT';
$lang->doc->createList['excel']      = 'Excel';
$lang->doc->createList['attachment'] = $lang->doc->uploadDoc;

$lang->doc->types['doc'] = 'Wiki文档';
$lang->doc->types['api'] = '接口文档';

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
$lang->doc->customShowLibsList['unclosed'] = '只显示未关闭的' . $lang->executionCommon;

$lang->doc->mail = new stdclass();
$lang->doc->mail->create = new stdclass();
$lang->doc->mail->edit   = new stdclass();
$lang->doc->mail->create->title = "%s创建了文档 #%s:%s";
$lang->doc->mail->edit->title   = "%s编辑了文档 #%s:%s";

$lang->doc->confirmDelete        = "您确定删除该文档吗？";
$lang->doc->confirmDeleteLib     = "您确定删除该文档库吗？";
$lang->doc->confirmDeleteBook    = "您确定删除该手册吗？";
$lang->doc->confirmDeleteChapter = "您确定删除该章节吗？";
$lang->doc->confirmDeleteModule  = "您确定删除该目录吗？";
$lang->doc->confirmOtherEditing  = "该文档正在编辑中，如果继续编辑将覆盖他人编辑内容，是否继续？";
$lang->doc->errorEditSystemDoc   = "系统文档库无需修改。";
$lang->doc->errorEmptyProduct    = "没有{$lang->productCommon}，无法创建文档";
$lang->doc->errorEmptyProject    = "没有{$lang->executionCommon}，无法创建文档";
$lang->doc->errorMainSysLib      = "该系统文档库不能删除！";
$lang->doc->accessDenied         = "您没有权限访问！";
$lang->doc->versionNotFount      = '该版本文档不存在';
$lang->doc->noDoc                = '暂时没有文档。';
$lang->doc->noArticle            = '暂时没有文章。';
$lang->doc->noLib                = '暂时没有库。';
$lang->doc->noBook               = 'Wiki库还未创建手册，请新建 ：）';
$lang->doc->cannotCreateOffice   = '<p>对不起，企业版才能创建%s文档。</p><p>试用企业版，请联系我们：4006-8899-23 &nbsp; 0532-86893032。</p>';
$lang->doc->notSetOffice         = "创建%s文档，需要配置<a href='%s'>Office转换设置</a>。";
$lang->doc->noSearchedDoc        = '没有搜索到任何文档。';
$lang->doc->noEditedDoc          = '您还没有编辑任何文档。';
$lang->doc->noOpenedDoc          = '您还没有创建任何文档。';
$lang->doc->noCollectedDoc       = '您还没有收藏任何文档。';
$lang->doc->errorEmptyLib        = '文档库暂无数据。';
$lang->doc->confirmUpdateContent = '检查到您有未保存的文档内容，是否继续编辑？';
$lang->doc->selectLibType        = '请选择文档库类型';
$lang->doc->noLibreOffice        = '您还没有office转换设置访问权限!';
$lang->doc->errorParentChapter   = '父章节不能是自身章节及子章节！';

$lang->doc->noticeAcl['lib']['product']['default']   = "有所选{$lang->productCommon}访问权限的用户可以访问。";
$lang->doc->noticeAcl['lib']['product']['custom']    = "有所选{$lang->productCommon}访问权限或白名单里的用户可以访问。";
$lang->doc->noticeAcl['lib']['project']['default']   = "有所选{$lang->projectCommon}访问权限的用户可以访问。";
$lang->doc->noticeAcl['lib']['project']['open']      = "有所选{$lang->projectCommon}访问权限的用户可以访问。";
$lang->doc->noticeAcl['lib']['project']['private']   = "有所选{$lang->projectCommon}访问权限或白名单里的用户可以访问。";
$lang->doc->noticeAcl['lib']['project']['custom']    = "白名单的用户可以访问。";
$lang->doc->noticeAcl['lib']['execution']['default'] = "有所选{$lang->execution->common}访问权限的用户可以访问。";
$lang->doc->noticeAcl['lib']['execution']['custom']  = "有所选{$lang->execution->common}访问权限或白名单里的用户可以访问。";
$lang->doc->noticeAcl['lib']['api']['open']          = '所有人都可以访问。';
$lang->doc->noticeAcl['lib']['api']['custom']        = '白名单的用户可以访问。';
$lang->doc->noticeAcl['lib']['api']['private']       = '只有创建者自己可以访问。';
$lang->doc->noticeAcl['lib']['custom']['open']       = '所有人都可以访问。';
$lang->doc->noticeAcl['lib']['custom']['custom']     = '白名单的用户可以访问。';
$lang->doc->noticeAcl['lib']['custom']['private']    = '只有创建者自己可以访问。';

$lang->doc->noticeAcl['doc']['open']    = '有文档所属文档库访问权限的，都可以访问。';
$lang->doc->noticeAcl['doc']['custom']  = '白名单的用户可以访问。';
$lang->doc->noticeAcl['doc']['private'] = '只有创建者自己可以访问。';

$lang->doc->placeholder = new stdclass();
$lang->doc->placeholder->url       = '相应的链接地址';
$lang->doc->placeholder->execution = '执行为空时，创建文档在项目库下';

$lang->doc->summary = "本页共 <strong>%s</strong> 个附件，共计 <strong>%s</strong>，其中<strong>%s</strong>。";
$lang->doc->ge      = '个';
$lang->doc->point   = '、';

$lang->doc->libDropdown['editLib']       = '编辑库';
$lang->doc->libDropdown['deleteLib']     = '删除库';
$lang->doc->libDropdown['addModule']     = '添加目录';
$lang->doc->libDropdown['addSameModule'] = '添加同级目录';
$lang->doc->libDropdown['addSubModule']  = '添加子目录';
$lang->doc->libDropdown['editModule']    = '编辑目录';
$lang->doc->libDropdown['delModule']     = '删除目录';

$lang->doc->featureBar['tableContents']['all']   = '全部';
$lang->doc->featureBar['tableContents']['draft'] = '草稿';

$lang->doc->featureBar['myspace']['all']   = '全部';
$lang->doc->featureBar['myspace']['draft'] = '草稿';

$lang->doc->showDocList[1] = '是';
$lang->doc->showDocList[0] = '否';
