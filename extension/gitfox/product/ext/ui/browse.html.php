<?php
declare(strict_types=1);
/**
 * GitFox 的应用列表页：复写 product-browse 的 UI。
 * The application list UI of product module for the GitFox vision.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @package     product
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('orderBy', $orderBy);

/* 字段配置同步 product-all 的 dtable 配置。 */
$products = initTableData($products, $config->product->dtable->fieldList, $this->product);

/* 普通搜索：按应用名称模糊查询，关键词随 URL 传递并保存到 cookie。 */
$keyword = empty($keyword) ? '' : trim((string)$keyword);

$browseType = empty($browseType) ? 'noclosed' : $browseType;
$linkParams = array
(
    'productID'  => 0,
    'branch'     => 'all',
    'browseType' => '{key}',
    'param'      => $param,
    'storyType'  => 'story',
    'orderBy'    => $orderBy,
    'recTotal'   => 0,
    'recPerPage' => $recPerPage,
    'pageID'     => $pageID,
    'projectID'  => 0,
    'from'       => 'product',
    'blockID'    => 0
);

$featureBarLink = createLink('product', 'browse', $linkParams);

$linkParams['browseType'] = $browseType;

$queryLink = createLink('product', 'browse', $linkParams);
jsVar('+productSearchLink', $queryLink);

$searchBox = div
(
    setClass('input-group product-gitfox-search'),
    input
    (
        setID('productKeyword'),
        set::name('keyword'),
        set::type('search'),
        set::value($keyword),
        set::placeholder($lang->product->searchByAppName),
        set::autocomplete('off')
    ),
    btn
    (
        setID('productSearchBtn'),
        set::className('secondary'),
        set::icon('search'),
        set::title($lang->searchAB)
    )
);

featureBar
(
    set::module('product'),
    set::method('all'),
    set::link($featureBarLink),
    li(setClass('ml-3'), $searchBox)
);

$canCreate = hasPriv('product', 'create');
toolbar
(
    $canCreate ? btn
    (
        set::text($lang->product->create),
        set::icon('plus'),
        set::type('primary'),
        set::url(createLink('product', 'create')),
        set::className('create-product-btn')
    ) : null
);

$linkParams['orderBy'] = '{name}_{sortType}';
$sortLink = createLink('product', 'browse', $linkParams);
dtable
(
    set::id('products'),
    set::cols($config->product->dtable->fieldList),
    set::data($products),
    set::userMap($users),
    set::footPager(usePager()),
    set::sortLink($sortLink),
    set::emptyTip($lang->product->noProduct),
    set::createTip($lang->product->create),
    set::createLink($canCreate ? createLink('product', 'create') : '')
);
