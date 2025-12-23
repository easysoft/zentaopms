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
$lang->doclib->name         = '库名称';
$lang->doclib->control      = '访问控制';
$lang->doclib->group        = '分组';
$lang->doclib->user         = '用户';
$lang->doclib->files        = '附件库';
$lang->doclib->all          = '所有文档库';
$lang->doclib->select       = '选择文档库';
$lang->doclib->execution    = $lang->executionCommon . '库';
$lang->doclib->product      = $lang->productCommon . '库';
$lang->doclib->apiLibName   = '库名称';
$lang->doclib->defaultSpace = '默认空间';
$lang->doclib->defaultMyLib = '我的库';
$lang->doclib->spaceName    = '空间名称';
$lang->doclib->createSpace  = '新建空间';
$lang->doclib->editSpace    = '编辑空间';
$lang->doclib->privateACL   = "私有 （仅创建者和有%s权限的白名单用户可访问）";
$lang->doclib->defaultOrder = '文档默认排序';
$lang->doclib->migratedWiki = '已迁移的Wiki';

$lang->doclib->tip = new stdclass();
$lang->doclib->tip->selectExecution = "执行为空时，创建的库为{$lang->projectCommon}库";

$lang->doclib->type['wiki'] = '文档库';
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

$lang->doclib->idOrder = array();
$lang->doclib->idOrder['id_asc']  = 'ID 正序';
$lang->doclib->idOrder['id_desc'] = 'ID 倒序';

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

$lang->docTemplate = new stdclass();
$lang->docTemplate->id                           = '编号';
$lang->docTemplate->title                        = '模板标题';
$lang->docTemplate->frequency                    = '频率';
$lang->docTemplate->type                         = '分类';
$lang->docTemplate->addedBy                      = '创建者';
$lang->docTemplate->addedDate                    = '创建日期';
$lang->docTemplate->editedBy                     = '修改者';
$lang->docTemplate->editedDate                   = '修改日期';
$lang->docTemplate->views                        = '阅读次数';
$lang->docTemplate->confirmDelete                = '您确定删除该文档模板吗？';
$lang->docTemplate->scope                        = '所属范围';
$lang->docTemplate->lib                          = $lang->docTemplate->scope;
$lang->docTemplate->module                       = '模板分类';
$lang->docTemplate->desc                         = '描述';
$lang->docTemplate->deliverable                  = '是否为交付物';
$lang->docTemplate->parentModule                 = '上级分类';
$lang->docTemplate->typeName                     = '分类名称';
$lang->docTemplate->parent                       = '所属层级';
$lang->docTemplate->addTemplateType              = '添加模板分类';
$lang->docTemplate->editTemplateType             = '编辑模板分类';
$lang->docTemplate->docTitlePlaceholder          = '请输入文档模板标题';
$lang->docTemplate->docTitleRequired             = '文档模板标题不能为空。';
$lang->docTemplate->errorDeleteType              = '当前分类存在文档模板，不可删除';
$lang->docTemplate->convertToNewDocConfirm       = '全新文档格式使用现代化块级编辑器，带来全新的文档功能体验。确定要将此文档模板转换为新文档格式吗？存为草稿或者发布后，不能再切换回旧编辑器。';
$lang->docTemplate->oldDocEditingTip             = '此文档模板为旧版本编辑器创建，已启用新版编辑器编辑，保存后将转换为新版文档模板';
$lang->docTemplate->leaveEditingConfirm          = '文档模板编辑中，确定离开吗？';
$lang->docTemplate->searchScopePlaceholder       = '搜索范围';
$lang->docTemplate->searchTypePlaceholder        = '搜索分类';
$lang->docTemplate->moveDocTemplate              = '移动文档模板';
$lang->docTemplate->moveSubTemplate              = '移动子文档模板';
$lang->docTemplate->createTypeFirst              = '请先创建文档模板分类。';
$lang->docTemplate->editedList                   = '模板编辑者';
$lang->docTemplate->content                      = '模板内容';
$lang->docTemplate->templateDesc                 = '模板描述';
$lang->docTemplate->status                       = '模板状态';
$lang->docTemplate->emptyTip                     = '此参数与筛选条件下，暂无符合条件系统数据。';
$lang->docTemplate->emptyDataTip                 = '此筛选条件下，暂无符合条件系统数据。';
$lang->docTemplate->previewTip                   = '配置参数后，此区块会根据筛选器的配置展示相应的列表数据。';
$lang->docTemplate->confirmDeleteChapterWithSub  = "删除章节后，章节下层级内容将一并隐藏，确定要删除该章节吗？";
$lang->docTemplate->confirmDeleteTemplateWithSub = "删除文档模板后，文档模板下层级内容将一并隐藏，确定要删除该文档模板吗？";
$lang->docTemplate->scopeHasTemplateTips         = '该范围下有文档模板，请移除后再删除范围。';
$lang->docTemplate->scopeHasModuleTips           = '该范围下有模板分类数据，请移除后再删除范围。';
$lang->docTemplate->needEditable                 = '您没有当前文档模板的编辑权限。';

$lang->docTemplate->more       = '更多';
$lang->docTemplate->scopeLabel = '范围';
$lang->docTemplate->noTemplate = '没有文档模板';
$lang->docTemplate->noDesc     = '暂时没有描述';
$lang->docTemplate->of         = '的';
$lang->docTemplate->overdue    = '已过期';

$lang->docTemplate->create = '创建模板';
$lang->docTemplate->edit   = '编辑文档模板';
$lang->docTemplate->delete = '删除文档模板';

$lang->docTemplate->addModule         = '添加分类';
$lang->docTemplate->addSameModule     = '添加同级分类';
$lang->docTemplate->addSubModule      = '添加子分类';
$lang->docTemplate->editModule        = '编辑分类';
$lang->docTemplate->deleteModule      = '删除分类';
$lang->docTemplate->noModules         = '没有文档模板分类';
$lang->docTemplate->addSubDocTemplate = '添加子文档模板';

$lang->docTemplate->filterTypes = array();
$lang->docTemplate->filterTypes[] = array('all', '全部');
$lang->docTemplate->filterTypes[] = array('draft', '草稿');
$lang->docTemplate->filterTypes[] = array('released', '已发布');
$lang->docTemplate->filterTypes[] = array('createdByMe', '我创建的');

$lang->docTemplate->deliverableList['1'] = '是';
$lang->docTemplate->deliverableList['0'] = '否';

/* 字段列表。*/
$lang->doc->common       = '文档';
$lang->doc->id           = 'ID';
$lang->doc->product      = '所属' . $lang->productCommon;
$lang->doc->project      = "所属{$lang->projectCommon}";
$lang->doc->execution    = '所属' . $lang->execution->common;
$lang->doc->plan         = '所属计划';
$lang->doc->lib          = '所属库';
$lang->doc->module       = '所属父级';
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
$lang->doc->addedBy      = '创建者';
$lang->doc->addedDate    = '创建日期';
$lang->doc->editedBy     = '修改者';
$lang->doc->editedDate   = '修改日期';
$lang->doc->editingDate  = '正在修改者和时间';
$lang->doc->lastEditedBy = '最后更新者';
$lang->doc->updateInfo   = '更新信息';
$lang->doc->version      = '版本号';
$lang->doc->basicInfo    = '基本信息';
$lang->doc->deleted      = '已删除';
$lang->doc->fileObject   = '所属对象';
$lang->doc->whiteList    = '白名单';
$lang->doc->readonly     = '只读';
$lang->doc->editable     = '可编辑';
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
$lang->doc->template     = '模板';
$lang->doc->position     = '所在位置';
$lang->doc->person       = '个人';
$lang->doc->team         = '团队';
$lang->doc->manage       = '文档管理';
$lang->doc->release      = '发布';
$lang->doc->story        = '需求';
$lang->doc->convertdoc   = '转换为文档';
$lang->doc->needEditable = '您没有当前文档的编辑权限。';
$lang->doc->needReadable = '您没有当前文档的阅读权限。';
$lang->doc->groupLabel   = '分组';
$lang->doc->userLabel    = '用户';

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
$lang->doc->myCollection  = '我收藏的';
$lang->doc->myCreation    = '我创建的';
$lang->doc->myEdited      = '我编辑的';
$lang->doc->myLib         = '我的个人库';
$lang->doc->tableContents = '目录';
$lang->doc->addCatalog    = '添加目录';
$lang->doc->editCatalog   = '编辑目录';
$lang->doc->deleteCatalog = '删除目录';
$lang->doc->sortCatalog   = '目录排序';
$lang->doc->sortDoclib    = '库排序';
$lang->doc->sortDoc       = '文档排序';
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
$lang->doc->createOrUpload   = '创建文档';
$lang->doc->edit             = '编辑文档';
$lang->doc->effort           = '日志';
$lang->doc->delete           = '删除文档';
$lang->doc->createBook       = '创建手册';
$lang->doc->browse           = '文档列表';
$lang->doc->view             = '文档详情';
$lang->doc->diff             = '对比';
$lang->doc->confirm          = '确定';
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
$lang->doc->collectSuccess   = '收藏成功';
$lang->doc->cancelCollection = '取消收藏';
$lang->doc->deleteFile       = '删除附件';
$lang->doc->menuTitle        = '目录';
$lang->doc->api              = '接口';
$lang->doc->displaySetting   = '显示设置';
$lang->doc->collectAction    = '收藏文档';

$lang->doc->libName            = '库名称';
$lang->doc->libType            = '库类型';
$lang->doc->custom             = '自定义文档库';
$lang->doc->customAB           = '自定义库';
$lang->doc->createLib          = '创建库';
$lang->doc->createLibAction    = '创建库';
$lang->doc->createSpace        = '创建空间';
$lang->doc->allLibs            = '库列表';
$lang->doc->objectLibs         = "库文档详情";
$lang->doc->showFiles          = '附件库';
$lang->doc->editLib            = '编辑库';
$lang->doc->editSpaceAction    = '编辑空间';
$lang->doc->editLibAction      = '编辑库';
$lang->doc->deleteSpaceAction  = '删除空间';
$lang->doc->deleteLibAction    = '删除库';
$lang->doc->moveLibAction      = '移动库';
$lang->doc->moveDocAction      = '移动文档';
$lang->doc->batchMove          = '批量移动';
$lang->doc->batchMoveDocAction = '批量移动文档';
$lang->doc->fixedMenu          = '固定到菜单栏';
$lang->doc->removeMenu         = '从菜单栏移除';
$lang->doc->search             = '搜索';
$lang->doc->allCollections     = '查看全部收藏文档';
$lang->doc->keywordsTips       = '多个关键字请用逗号分隔。';
$lang->doc->sortLibs           = '文档库排序';
$lang->doc->titlePlaceholder   = '在此输入标题';
$lang->doc->confirm            = '确认';
$lang->doc->docSummary         = '本页共 <strong>%s</strong> 个文档。';
$lang->doc->docCheckedSummary  = '共选中 <strong>%total%</strong> 个文档。';
$lang->doc->showDoc            = '是否显示文档';
$lang->doc->uploadFile         = '上传文件';
$lang->doc->uploadDoc          = '导入';
$lang->doc->uploadFormat       = '上传格式';
$lang->doc->editedList         = '文档编辑者';
$lang->doc->moveTo             = '移动至';
$lang->doc->notSupportExport   = '（此文档暂不支持导出）';
$lang->doc->downloadTemplate   = '下载模板';
$lang->doc->addFile            = '提交文件';
$lang->doc->frozenTips         = '文档打基线后不允许%s';

$lang->doc->preview         = '预览';
$lang->doc->insertTitle     = '插入%s列表';
$lang->doc->previewTip      = '通过筛选配置可以修改插入内容的展示数据，插入的数据为静态的数据快照。';
$lang->doc->insertTip       = '请预览后至少选择一条数据。';
$lang->doc->insertText      = '插入';
$lang->doc->searchCondition = '搜索条件';
$lang->doc->list            = '列表';
$lang->doc->detail          = '详情';
$lang->doc->zentaoData      = '禅道数据';
$lang->doc->emptyError      = '不能为空';
$lang->doc->caselib         = '用例库';
$lang->doc->customSearch    = '自定义搜索';

$lang->doc->addChapter     = '添加章节';
$lang->doc->editChapter    = '编辑章节';
$lang->doc->sortChapter    = '章节排序';
$lang->doc->deleteChapter  = '删除章节';
$lang->doc->addSubChapter  = '添加子章节';
$lang->doc->addSameChapter = '添加同级章节';
$lang->doc->addSubDoc      = '添加子文档';
$lang->doc->chapterName    = '章节名称';

$lang->doc->tips = new stdclass();
$lang->doc->tips->noProduct   = '暂时没有产品，请先创建';
$lang->doc->tips->noProject   = '暂时没有项目，请先创建';
$lang->doc->tips->noExecution = '暂时没有执行，请先创建';
$lang->doc->tips->noCaselib   = '暂时没有用例库，请先创建';

$lang->doc->zentaoList = array();
$lang->doc->zentaoList['story']          = $lang->SRCommon;
$lang->doc->zentaoList['productStory']   = $lang->productCommon . $lang->SRCommon;
$lang->doc->zentaoList['projectStory']   = $lang->projectCommon . $lang->SRCommon;
$lang->doc->zentaoList['executionStory'] = $lang->execution->common . $lang->SRCommon;
$lang->doc->zentaoList['planStory']      = $lang->productplan->shortCommon . $lang->SRCommon;

$lang->doc->zentaoList['case']        = $lang->testcase->common;
$lang->doc->zentaoList['productCase'] = $lang->productCommon . $lang->testcase->common;
$lang->doc->zentaoList['projectCase'] = $lang->projectCommon . $lang->testcase->common;
$lang->doc->zentaoList['caselib']     = '用例库' . $lang->testcase->common;

$lang->doc->zentaoList['task']       = $lang->task->common;
$lang->doc->zentaoList['bug']        = $lang->bug->common;
$lang->doc->zentaoList['projectBug'] = $lang->projectCommon . $lang->bug->common;
$lang->doc->zentaoList['productBug'] = '产品Bug';
$lang->doc->zentaoList['planBug']    = '计划Bug';

$lang->doc->zentaoList['more']               = '更多';
$lang->doc->zentaoList['productPlan']        = $lang->productCommon . '下计划';
$lang->doc->zentaoList['productPlanContent'] = $lang->productCommon . '计划下的内容';
$lang->doc->zentaoList['productRelease']     = $lang->productCommon . $lang->release->common;
$lang->doc->zentaoList['projectRelease']     = $lang->projectCommon . $lang->release->common;
$lang->doc->zentaoList['ER']                 = $lang->defaultERName;
$lang->doc->zentaoList['UR']                 = $lang->URCommon;
$lang->doc->zentaoList['feedback']           = '反馈';
$lang->doc->zentaoList['ticket']             = '工单';
$lang->doc->zentaoList['gantt']              = '甘特图';

$lang->doc->zentaoList['HLDS'] = '概要设计';
$lang->doc->zentaoList['DDS']  = '详细设计';
$lang->doc->zentaoList['DBDS'] = '数据库设计';
$lang->doc->zentaoList['ADS']  = '接口设计';

$lang->doc->zentaoAction = array();
$lang->doc->zentaoAction['set']       = '设置';
$lang->doc->zentaoAction['delete']    = '删除';
$lang->doc->zentaoAction['setParams'] = '配置参数';

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

$lang->doc->aclList['open']    = "公开（所有人都可查看和编辑）";
$lang->doc->aclList['private'] = "私有（仅特定人员可查看和编辑）";

$lang->doc->aclListA['open']    = "公开（所有人均可访问，有编辑文档模板权限可访问并维护）";
$lang->doc->aclListA['private'] = "私有（仅创建者自己可以编辑、使用）";

$lang->doc->selectSpace = '选择空间';
$lang->doc->space       = '所属空间';
$lang->doc->spaceList['mine']    = '我的空间';
$lang->doc->spaceList['custom']  = '团队空间';
$lang->doc->spaceList['product'] = $lang->productCommon . '空间';
$lang->doc->spaceList['project'] = $lang->projectCommon . '空间';
$lang->doc->spaceList['api']     = '接口空间';

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

$lang->doc->types['doc'] = '文档';
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
$lang->doc->mail->releasedDoc = new stdclass();
$lang->doc->mail->edit        = new stdclass();
$lang->doc->mail->releasedDoc->title = "%s发布了文档 #%s:%s";
$lang->doc->mail->edit->title        = "%s编辑了文档 #%s:%s";

$lang->doc->confirmDelete               = "您确定删除该文档吗？";
$lang->doc->confirmDeleteWithSub        = "删除文档后，将同步删除文档下的所有内容，确认要删除吗？";
$lang->doc->confirmDeleteLib            = "您确定删除该文档库吗？";
$lang->doc->confirmDeleteSpace          = "删除空间后，同步删除空间下的库、目录和文档，确认要删除吗？";
$lang->doc->confirmDeleteBook           = "您确定删除该手册吗？";
$lang->doc->confirmDeleteChapter        = "您确定删除该章节吗？";
$lang->doc->confirmDeleteChapterWithSub = "删除章节后，将同步删除章节下的子章节和文档，确认要删除吗？";
$lang->doc->confirmDeleteModule         = "您确定删除该目录吗？";
$lang->doc->confirmDeleteModuleWithSub  = "删除目录后，同步删除目录下的子目录、章节和文档，确认要删除吗？";
$lang->doc->confirmOtherEditing         = "该文档正在编辑中，如果继续编辑将覆盖他人编辑内容，是否继续？";
$lang->doc->errorEditSystemDoc          = "系统文档库无需修改。";
$lang->doc->errorEmptyProduct           = "没有{$lang->productCommon}，无法创建文档";
$lang->doc->errorEmptyProject           = "没有{$lang->executionCommon}，无法创建文档";
$lang->doc->errorEmptySpaceLib          = "该空间下没有文档库，无法创建文档，请先创建文档库";
$lang->doc->errorMainSysLib             = "该系统文档库不能删除！";
$lang->doc->accessDenied                = "您没有权限访问！";
$lang->doc->cannotView                  = "无查看权限，请联系创建者“%s”！";
$lang->doc->versionNotFount             = '该版本文档不存在';
$lang->doc->noDoc                       = '暂时没有文档。';
$lang->doc->noArticle                   = '暂时没有文章。';
$lang->doc->noLib                       = '暂时没有库。';
$lang->doc->noBook                      = 'Wiki库还未创建手册，请新建 ：）';
$lang->doc->cannotCreateOffice          = '<p>对不起，企业版才能创建%s文档。</p><p>试用企业版，请联系我们：4006-8899-23 &nbsp; 0532-86893032。</p>';
$lang->doc->notSetOffice                = "创建 %s 文档，需要配置 <a href='%s'>Collabora Online</a>。";
$lang->doc->requestTypeError            = "当前禅道 requestType 配置不是 PATH_INFO，无法使用 Collabora Online 在线编辑功能，请联系管理员修改 requestType 配置。";
$lang->doc->notSetCollabora             = "没有设置 Collabora Online，无法创建%s文档，请配置 <a href='%s'>Collabora Online</a>。";
$lang->doc->noSearchedDoc               = '没有搜索到任何文档。';
$lang->doc->noEditedDoc                 = '您还没有编辑任何文档。';
$lang->doc->noOpenedDoc                 = '您还没有创建任何文档。';
$lang->doc->noCollectedDoc              = '您还没有收藏任何文档。';
$lang->doc->errorEmptyLib               = '文档库暂无数据。';
$lang->doc->confirmUpdateContent        = '检查到您有未保存的文档内容，是否继续编辑？';
$lang->doc->selectLibType               = '请选择文档库类型';
$lang->doc->selectDoc                   = '请选择文档';
$lang->doc->noLibreOffice               = '您还没有office转换设置访问权限!';
$lang->doc->errorParentChapter          = '父章节不能是自身章节及子章节！';
$lang->doc->errorOthersCreated          = '该库下其他人创建的文档暂不支持移动，是否确认移动？';
$lang->doc->confirmLeaveOnEdit          = '检查到您有未保存的文档内容，是否继续跳转？';
$lang->doc->errorOccurred               = '操作失败，请稍后再试！';
$lang->doc->selectLibFirst              = '请先选择文档库。';
$lang->doc->createLibFirst              = '请先创建文档库。';
$lang->doc->nopriv                      = '您暂无 %s 的访问权限，无法查看该文档，如需调整权限可联系相关人员处理。';
$lang->doc->docConvertComment           = "文档已经转换为新编辑器格式，切换版本 %s 来查看转换前的文档。";
$lang->doc->previewNotAvailable         = '预览功能暂不可用，请访问禅道查看文档 %s。';
$lang->doc->hocuspocusConnect           = '协作编辑服务已连接。';
$lang->doc->hocuspocusDisconnect        = '协作编辑服务已断开，编辑内容将在重新连接后同步。';
$lang->doc->docTemplateConvertComment   = "文档模板已经转换为新编辑器格式，切换版本 %s 来查看转换前的文档模板。";
$lang->doc->noSupportList               = "当前{$lang->projectCommon}暂不支持“ %s”";

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
$lang->doc->libDropdown['editSpace']     = '编辑空间';
$lang->doc->libDropdown['deleteSpace']   = '删除空间';
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

$lang->doc->whitelistDeny['product']   = "<i class='icon pr-1 text-important icon-exclamation'></i>用户<span class='px-1 text-important'>%s</span>暂无产品访问权限，因此无法访问文档。如需访问，请维护产品访问控制权限。";
$lang->doc->whitelistDeny['project']   = "<i class='icon pr-1 text-important icon-exclamation'></i>用户<span class='px-1 text-important'>%s</span>暂无项目访问权限，因此无法访问文档。如需访问，请维护项目访问控制权限。";
$lang->doc->whitelistDeny['execution'] = "<i class='icon pr-1 text-important icon-exclamation'></i>用户<span class='px-1 text-important'>%s</span>暂无执行访问权限，因此无法访问文档。如需访问，请维护执行访问控制权限。";
$lang->doc->whitelistDeny['doc']       = "<i class='icon pr-1 text-important icon-exclamation'></i>用户<span class='px-1 text-important'>%s</span>暂无所在库访问权限，因此无法访问文档。如需访问，请维护所在库的访问控制权限。";

$lang->doc->filterTypes[] = array('all', '全部');
$lang->doc->filterTypes[] = array('draft', '草稿');
$lang->doc->filterTypes[] = array('collect', '我收藏的');
$lang->doc->filterTypes[] = array('createdByMe', '我创建的');
$lang->doc->filterTypes[] = array('editedByMe', '我编辑的');

$lang->doc->fileFilterTypes[] = array('all', '全部');
$lang->doc->fileFilterTypes[] = array('addedByMe', '我添加');

$lang->doc->productFilterTypes[] = array('all',  '全部');
$lang->doc->productFilterTypes[] = array('mine', '我负责的');

$lang->doc->projectFilterTypes[] = array('all', '全部');
$lang->doc->projectFilterTypes[] = array('mine', '我参与的');

$lang->doc->spaceFilterTypes[] = array('all', '全部');

$lang->doc->manageScope        = '维护范围';
$lang->doc->browseTemplate     = '模板广场';
$lang->doc->createTemplate     = '创建文档模板';
$lang->doc->editTemplate       = '编辑文档模板';
$lang->doc->moveTemplate       = '移动文档模板';
$lang->doc->deleteTemplate     = '删除文档模板';
$lang->doc->viewTemplate       = '文档模板详情';
$lang->doc->addTemplateType    = '添加模板分类';
$lang->doc->editTemplateType   = '编辑模板分类';
$lang->doc->deleteTemplateType = '删除模板分类';
$lang->doc->sortTemplate       = '排序';

$lang->doc->docLang = new stdClass();
$lang->doc->docLang->cancel                      = $lang->cancel;
$lang->doc->docLang->export                      = $lang->export;
$lang->doc->docLang->exportWord                  = "导出 Word";
$lang->doc->docLang->exportPdf                   = "导出 PDF";
$lang->doc->docLang->exportImage                 = "导出图片";
$lang->doc->docLang->exportHtml                  = "导出 HTML";
$lang->doc->docLang->exportMarkdown              = "导出 Markdown";
$lang->doc->docLang->exportJSON                  = "导出备份(.json)";
$lang->doc->docLang->importMarkdown              = "导入 Markdown";
$lang->doc->docLang->importConfluence            = "导入 Confluence 存储格式";
$lang->doc->docLang->importJSON                  = "导入备份(.json)";
$lang->doc->docLang->importConfirm               = "导入将覆盖当前文档内容，确定导入吗？";
$lang->doc->docLang->settings                    = $lang->settings;
$lang->doc->docLang->save                        = $lang->save;
$lang->doc->docLang->createSpace                 = $lang->doc->createSpace;
$lang->doc->docLang->createLib                   = $lang->doc->createLib;
$lang->doc->docLang->actions                     = $lang->doc->libDropdown;
$lang->doc->docLang->moveTo                      = $lang->doc->moveTo;
$lang->doc->docLang->create                      = $lang->doc->createAB;
$lang->doc->docLang->createDoc                   = $lang->doc->create;
$lang->doc->docLang->editDoc                     = $lang->doc->edit;
$lang->doc->docLang->effort                      = $lang->doc->effort;
$lang->doc->docLang->deleteDoc                   = $lang->doc->delete;
$lang->doc->docLang->uploadDoc                   = $lang->doc->uploadFile;
$lang->doc->docLang->createList                  = $lang->doc->createList;
$lang->doc->docLang->confirmDelete               = $lang->doc->confirmDelete;
$lang->doc->docLang->confirmDeleteWithSub        = $lang->doc->confirmDeleteWithSub;
$lang->doc->docLang->confirmDeleteLib            = $lang->doc->confirmDeleteLib;
$lang->doc->docLang->confirmDeleteSpace          = $lang->doc->confirmDeleteSpace;
$lang->doc->docLang->confirmDeleteModule         = $lang->doc->confirmDeleteModule;
$lang->doc->docLang->confirmDeleteModuleWithSub  = $lang->doc->confirmDeleteModuleWithSub;
$lang->doc->docLang->confirmDeleteChapter        = $lang->doc->confirmDeleteChapter;
$lang->doc->docLang->confirmDeleteChapterWithSub = $lang->doc->confirmDeleteChapterWithSub;
$lang->doc->docLang->collect                     = $lang->doc->collect;
$lang->doc->docLang->edit                        = $lang->doc->edit;
$lang->doc->docLang->delete                      = $lang->doc->delete;
$lang->doc->docLang->cancelCollection            = $lang->doc->cancelCollection;
$lang->doc->docLang->moveDoc                     = $lang->doc->moveDocAction;
$lang->doc->docLang->moveTo                      = $lang->doc->moveTo;
$lang->doc->docLang->moveLib                     = $lang->doc->moveLibAction;
$lang->doc->docLang->moduleName                  = $lang->doc->catalogName;
$lang->doc->docLang->saveDraft                   = $lang->doc->saveDraft;
$lang->doc->docLang->template                    = $lang->doc->template;
$lang->doc->docLang->release                     = $lang->doc->release;
$lang->doc->docLang->batchMove                   = $lang->doc->batchMove;
$lang->doc->docLang->filterTypes                 = $lang->doc->filterTypes;
$lang->doc->docLang->fileFilterTypes             = $lang->doc->fileFilterTypes;
$lang->doc->docLang->productFilterTypes          = $lang->doc->productFilterTypes;
$lang->doc->docLang->projectFilterTypes          = $lang->doc->projectFilterTypes;
$lang->doc->docLang->spaceFilterTypes            = $lang->doc->spaceFilterTypes;
$lang->doc->docLang->sortCatalog                 = $lang->doc->sortCatalog;
$lang->doc->docLang->sortDoclib                  = $lang->doc->sortDoclib;
$lang->doc->docLang->sortDoc                     = $lang->doc->sortDoc;
$lang->doc->docLang->errorOccurred               = $lang->doc->errorOccurred;
$lang->doc->docLang->selectLibFirst              = $lang->doc->selectLibFirst;
$lang->doc->docLang->createLibFirst              = $lang->doc->createLibFirst;
$lang->doc->docLang->space                       = '空间';
$lang->doc->docLang->spaceTypeNames              = array();
$lang->doc->docLang->spaceTypeNames['mine']      = $lang->doc->docLang->space;
$lang->doc->docLang->spaceTypeNames['product']   = $lang->productCommon . $lang->doc->docLang->space;
$lang->doc->docLang->spaceTypeNames['project']   = $lang->projectCommon . $lang->doc->docLang->space;
$lang->doc->docLang->spaceTypeNames['execution'] = $lang->executionCommon . $lang->doc->docLang->space;
$lang->doc->docLang->spaceTypeNames['api']       = $lang->doc->docLang->space;
$lang->doc->docLang->spaceTypeNames['custom']    = $lang->doc->docLang->space;
$lang->doc->docLang->enterSpace                  = '进入空间';
$lang->doc->docLang->noDocs                      = '没有文档';
$lang->doc->docLang->noFiles                     = '没有文件';
$lang->doc->docLang->noLibs                      = '没有文档库';
$lang->doc->docLang->noModules                   = '没有目录';
$lang->doc->docLang->docsTotalInfo               = '共 {0} 个文档';
$lang->doc->docLang->createSpace                 = $lang->doc->createSpace;
$lang->doc->docLang->createModule                = $lang->doc->addCatalog;
$lang->doc->docLang->leaveEditingConfirm         = '文档编辑中，确定要离开吗？';
$lang->doc->docLang->saveDocFailed               = '文档保存失败，请稍后重试';
$lang->doc->docLang->loadingDocsData             = '正在加载文档数据...';
$lang->doc->docLang->loadDataFailed              = '加载数据失败';
$lang->doc->docLang->noSpaceTip                  = '这里什么也没有，先创建一个空间再使用吧！';
$lang->doc->docLang->searchModulePlaceholder     = '搜索目录';
$lang->doc->docLang->searchDocPlaceholder        = '搜索文档';
$lang->doc->docLang->searchChapterPlaceholder    = '搜索章节';
$lang->doc->docLang->searchSpacePlaceholder      = '搜索空间';
$lang->doc->docLang->searchLibPlaceholder        = '搜索库';
$lang->doc->docLang->searchPlaceholder           = '搜索';
$lang->doc->docLang->newDocLabel                 = '新文档';
$lang->doc->docLang->editingDocLabel             = '编辑中';
$lang->doc->docLang->filesLib                    = $lang->doclib->files;
$lang->doc->docLang->currentDocVersionHint       = '当前版本，点击切换';
$lang->doc->docLang->viewsCount                  = $lang->doc->views;
$lang->doc->docLang->keywords                    = $lang->doc->keywords;
$lang->doc->docLang->keywordsPlaceholder         = $lang->doc->keywordsTips;
$lang->doc->docLang->loadingDocTip               = '正在加载文档...';
$lang->doc->docLang->loadingEditorTip            = '正在加载编辑器...';
$lang->doc->docLang->pasteImageTip               = $lang->noticePasteImg;
$lang->doc->docLang->downloadFile                = '下载文件';
$lang->doc->docLang->loadingFilesTip             = '正在加载文件...';
$lang->doc->docLang->recTotalFormat              = $lang->pager->totalCountAB;
$lang->doc->docLang->recPerPageFormat            = $lang->pager->pageSizeAB;
$lang->doc->docLang->firstPage                   = $lang->pager->firstPage;
$lang->doc->docLang->prevPage                    = $lang->pager->previousPage;
$lang->doc->docLang->nextPage                    = $lang->pager->nextPage;
$lang->doc->docLang->lastPage                    = $lang->pager->lastPage;
$lang->doc->docLang->docOutline                  = '文档大纲';
$lang->doc->docLang->noOutline                   = '没有大纲';
$lang->doc->docLang->loading                     = $lang->loading;
$lang->doc->docLang->libNamePrefix               = '库';
$lang->doc->docLang->colon                       = $lang->colon;
$lang->doc->docLang->createdByUserAt             = '由 {name} 创建于 {time}';
$lang->doc->docLang->editedByUserAt              = '由 {name} 编辑于 {time}';
$lang->doc->docLang->docInfo                     = '文档信息';
$lang->doc->docLang->docStatus                   = $lang->doc->status;
$lang->doc->docLang->creator                     = $lang->doc->addedBy;
$lang->doc->docLang->createDate                  = $lang->doc->addedDate;
$lang->doc->docLang->modifier                    = $lang->doc->editedBy;
$lang->doc->docLang->editDate                    = $lang->doc->editedDate;
$lang->doc->docLang->collectCount                = $lang->doc->docCollects;
$lang->doc->docLang->collected                   = '已收藏';
$lang->doc->docLang->history                     = $lang->history;
$lang->doc->docLang->updateHistory               = $lang->doc->updateInfo;
$lang->doc->docLang->updateInfoFormat            = '{name} {time} 更新';
$lang->doc->docLang->noUpdateInfo                = '暂无更新记录';
$lang->doc->docLang->enterFullscreen             = '进入全屏';
$lang->doc->docLang->exitFullscreen              = '退出全屏';
$lang->doc->docLang->collapse                    = '收起';
$lang->doc->docLang->draft                       = $lang->doc->statusList['draft'];
$lang->doc->docLang->released                    = $lang->doc->statusList['normal'];
$lang->doc->docLang->attachment                  = $lang->doc->files;
$lang->doc->docLang->docTitleRequired            = '文档标题不能为空。';
$lang->doc->docLang->docTitlePlaceholder         = '请输入文档标题';
$lang->doc->docLang->noDataYet                   = '暂无数据';
$lang->doc->docLang->position                    = $lang->doc->position;
$lang->doc->docLang->relateObject                = '关联对象';
$lang->doc->docLang->showHasDocsOnlyProduct      = '仅显示有文档的产品';
$lang->doc->docLang->showHasDocsOnlyProject      = '仅显示有文档的项目';
$lang->doc->docLang->showClosedProduct           = '显示已关闭的产品';
$lang->doc->docLang->showClosedProject           = '显示已关闭的项目';
$lang->doc->docLang->noProducts                  = '没有产品';
$lang->doc->docLang->noProjects                  = '没有项目';
$lang->doc->docLang->productMine                 = '我负责的';
$lang->doc->docLang->projectMine                 = '我参与的';
$lang->doc->docLang->productOther                = '其他';
$lang->doc->docLang->projectOther                = '其他';
$lang->doc->docLang->accessDenied                = $lang->doc->accessDenied;
$lang->doc->docLang->convertToNewDoc             = '转换文档';
$lang->doc->docLang->convertToNewDocConfirm      = '全新文档格式使用现代化块级编辑器，带来全新的文档功能体验。发布后，不能在切换回旧编辑器，确定要将此文档转换为新文档格式吗？';
$lang->doc->docLang->created                     = '创建';
$lang->doc->docLang->edited                      = '修改';
$lang->doc->docLang->notSaved                    = '未保存';
$lang->doc->docLang->oldDocEditingTip            = '此文档为旧版本编辑器创建，已启用新版编辑器编辑，保存后将转换为新版文档';
$lang->doc->docLang->switchToOldEditor           = '切换回旧编辑器';
$lang->doc->docLang->zentaoList                  = $lang->doc->zentaoList;
$lang->doc->docLang->list                        = $lang->doc->list;
$lang->doc->docLang->loadingFile                 = '正在下载图片...';
$lang->doc->docLang->needEditable                = $lang->doc->needEditable;
$lang->doc->docLang->addChapter                  = $lang->doc->addChapter;
$lang->doc->docLang->editChapter                 = $lang->doc->editChapter;
$lang->doc->docLang->sortChapter                 = $lang->doc->sortChapter;
$lang->doc->docLang->deleteChapter               = $lang->doc->deleteChapter;
$lang->doc->docLang->addSubChapter               = $lang->doc->addSubChapter;
$lang->doc->docLang->addSameChapter              = $lang->doc->addSameChapter;
$lang->doc->docLang->addSubDoc                   = $lang->doc->addSubDoc;
$lang->doc->docLang->chapterName                 = $lang->doc->chapterName;
$lang->doc->docLang->autoSaveHint                = '已自动保存';
$lang->doc->docLang->editing                     = '正在编辑';
$lang->doc->docLang->restoreVersionHint          = '恢复到版本';
$lang->doc->docLang->restoreVersion              = '恢复';
$lang->doc->docLang->restoreVersionConfirm       = '这将使用文档版本 {version} 的内容创建一个新的版本，确定要继续吗？';
$lang->doc->docLang->frozenTips                  = $lang->doc->frozenTips;

$lang->docTemplate->types = array();
$lang->docTemplate->types['plan']   = '计划';
$lang->docTemplate->types['story']  = '需求';
$lang->docTemplate->types['design'] = '设计';
$lang->docTemplate->types['dev']    = '开发';
$lang->docTemplate->types['test']   = '测试';
$lang->docTemplate->types['desc']   = '说明';
$lang->docTemplate->types['other']  = '其他';

$lang->docTemplate->builtInScopes = array();
$lang->docTemplate->builtInScopes['rnd']  = array();
$lang->docTemplate->builtInScopes['or']   = array();
$lang->docTemplate->builtInScopes['lite'] = array();
$lang->docTemplate->builtInScopes['rnd']['product']   = '产品';
$lang->docTemplate->builtInScopes['rnd']['project']   = '项目';
$lang->docTemplate->builtInScopes['rnd']['execution'] = '执行';
$lang->docTemplate->builtInScopes['rnd']['personal']  = '个人';
$lang->docTemplate->builtInScopes['or']['market']     = '市场';
$lang->docTemplate->builtInScopes['or']['product']    = '产品';
$lang->docTemplate->builtInScopes['or']['personal']   = '个人';
$lang->docTemplate->builtInScopes['lite']['project']  = '项目';
$lang->docTemplate->builtInScopes['lite']['personal'] = '个人';
