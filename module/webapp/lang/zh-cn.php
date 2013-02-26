<?php
/**
 * The webapp module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     webapp
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->webapp->common = 'WEB应用';
$lang->webapp->index  = '已加应用';
$lang->webapp->obtain = '获得应用';

$lang->webapp->install    = '安装应用';
$lang->webapp->uninstall  = '删除';
$lang->webapp->useapp     = '运行';
$lang->webapp->view       = '详情';
$lang->webapp->preview    = '预览';
$lang->webapp->installed  = '已添加';
$lang->webapp->edit       = '编辑应用';
$lang->webapp->create     = '创建应用';
$lang->webapp->manageTree = '维护分类';

$lang->webapp->id          = '编号';
$lang->webapp->name        = '名称';
$lang->webapp->url         = '网址';
$lang->webapp->icon        = '图标';
$lang->webapp->module      = '分类';
$lang->webapp->author      = '作者';
$lang->webapp->abstract    = '简介';
$lang->webapp->desc        = '描述';
$lang->webapp->target      = '打开方式';
$lang->webapp->size        = '大小';
$lang->webapp->height      = '高度';
$lang->webapp->addedTime   = '添加时间';
$lang->webapp->updatedTime = '更新时间';
$lang->webapp->downloads   = '下载量';
$lang->webapp->grade       = '评分';
$lang->webapp->addType     = '添加类型';
$lang->webapp->addedBy     = '安装人';
$lang->webapp->addedDate   = '安装日期';
$lang->webapp->views       = '浏览次数';
$lang->webapp->packup      = '收起';

$lang->webapp->byDownloads   = '最多下载';
$lang->webapp->byAddedTime   = '最新添加';
$lang->webapp->byUpdatedTime = '最近更新';
$lang->webapp->bySearch      = '搜索';
$lang->webapp->byCategory    = '分类浏览';

$lang->webapp->selectModule = '选择分类：';
$lang->webapp->allModule    = '所有';
$lang->webapp->noModule     = '所有';

$lang->webapp->targetList['']       = '';
$lang->webapp->targetList['popup']  = '弹窗';
$lang->webapp->targetList['iframe'] = '内嵌';
$lang->webapp->targetList['blank']  = '新窗口';

$lang->webapp->sizeList['']         = "";
$lang->webapp->sizeList['1024x600'] = "1024 x 600";
$lang->webapp->sizeList['900x600']  = "900 x 600";
$lang->webapp->sizeList['700x600']  = "700 x 600";
$lang->webapp->sizeList['600x500']  = "600 x 500";

$lang->webapp->addTypeList['system'] = '系统应用';
$lang->webapp->addTypeList['custom'] = '自定义应用';

$lang->webapp->errorOccurs        = '错误：';
$lang->webapp->errorGetModules    = '从www.zentao.net获得插件分类失败。可能是因为网络方面的原因，请检查后重新刷新页面。';
$lang->webapp->errorGetExtensions = '从www.zentao.net获得插件失败。可能是因为网络方面的原因，你可以到 “<a href="' . inlink('create') . '">创建应用</a>” 中自行创建。';
$lang->webapp->successInstall     = '成功安装应用！';
$lang->webapp->confirmDelete      = '是否删除该应用？';
$lang->webapp->noticeAbstract     = '用一句话介绍应用，不多于30个字';
