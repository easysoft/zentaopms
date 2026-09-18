<?php
declare(strict_types=1);
/**
 * GitFox 版空间应用列表的视图：复用应用模块的列配置与筛选、操作菜单。
 * The application list view of a space in the GitFox vision.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @package     space
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('tab', $app->tab);

/* 列与操作复用应用模块的配置；编辑走 space 模块，保证一级导航保持“空间”。*/
$fields = $config->product->dtable->fieldList;
if(isset($fields['actions']['list']['edit'])) $fields['actions']['list']['edit']['url'] = helper::createLink('space', 'edit', "spaceID={$spaceID}&productID={id}");

$appList = initTableData($appList, $fields, $app->control->loadModel('product'));

/* 链接参数顺序需与 apps() 的形参顺序一致。*/
$searchParams = array('spaceID' => $spaceID, 'keyword' => '__KEYWORD__',          'browseType' => $browseType,        'orderBy' => $orderBy,               'recPerPage' => $pager->recPerPage, 'pageID' => $pager->pageID);
$filterParams = array('spaceID' => $spaceID, 'keyword' => $keyword,              'browseType' => '{key}',            'orderBy' => $orderBy,               'recPerPage' => $pager->recPerPage, 'pageID' => 1);
$sortParams   = array('spaceID' => $spaceID, 'keyword' => $keyword,              'browseType' => $browseType,        'orderBy' => '{name}_{sortType}',    'recPerPage' => $pager->recPerPage, 'pageID' => $pager->pageID);

/* 普通搜索：按应用名称模糊查询，与应用模块的列表一致，关键字占位符由前端替换。*/
jsVar('+spaceAppSearchLink', createLink('space', 'apps', $searchParams));

$searchBox = div
(
    setClass('input-group space-app-search'),
    input
    (
        setID('spaceAppKeyword'),
        set::name('keyword'),
        set::type('search'),
        set::value($keyword),
        set::placeholder($lang->product->searchByAppName),
        set::autocomplete('off')
    ),
    btn
    (
        setID('spaceAppSearchBtn'),
        set::className('secondary'),
        set::icon('search'),
        set::title($lang->searchAB)
    )
);

/* 全部 / 未关闭 / 已关闭，与应用模块列表的筛选一致。*/
featureBar
(
    set::module('product'),
    set::method('all'),
    set::link(createLink('space', 'apps', $filterParams)),
    li(setClass('ml-3'), $searchBox)
);

dtable
(
    set::id('products'),
    set::cols($fields),
    set::data($appList),
    set::userMap($users),
    set::footPager(usePager()),
    set::sortLink(createLink('space', 'apps', $sortParams)),
    set::emptyTip($lang->product->noProduct)
);
