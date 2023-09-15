<?php
declare(strict_types=1);
/**
 * The browse view file of testtask module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     testtask
 * @link        https://www.zentao.net
 */
namespace zin;
$scopeAndStatus = explode(',', $type);
$scope          = !empty($scopeAndStatus[0]) ? $scopeAndStatus[0] : '';
$status         = !empty($scopeAndStatus[1]) ? $scopeAndStatus[1] : '';
$viewName       = $scope == 'local'? \zget($products, $productID) : $lang->testtask->all;
jsVar('condition', "productID=$productID&branch=$branch&type=$type&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}&pageID=1");

$productDropdown = productMenu
(
    set::title($viewName),
    set::items(array
    (
        array('text' => $lang->testtask->all, 'url' => helper::createLink('testtask', 'browse', "productID=$productID&branch=0&type=all,$status")),
        array('text' => \zget($products, $productID), 'url' => helper::createLink('testtask', 'browse', "productID=$productID&branch=$branch&type=local,$status"))
    )),
    set::link(createLink('qa', 'index')),
);
featureBar
(
    set::current($status),
    set::linkParams("productID={$productID}&branch={$branch}&type={$scope},{key}"),
    to::before($productDropdown),
    inputGroup
    (
        set::className('ml-4'),
        $lang->testtask->beginAndEnd,
        input
        (
            set::name('begin'),
            set::type('date'),
            set::value($beginTime),
        ),
        $lang->testtask->to,
        input
        (
            set::name('end'),
            set::type('date'),
            set::value($endTime),
        )
    )
);

if($product->shadow) unset($config->testtask->dtable->fieldList['product']);

$tasks = initTableData($tasks, $config->testtask->dtable->fieldList, $this->testtask);
$cols  = array_values($config->testtask->dtable->fieldList);
$data  = array_values($tasks);
toolbar
(
    common::canModify('product', $product) && common::hasPriv('testtask', 'create') ? btn
    (
        setClass('btn primary'),
        set::icon('plus'),
        set::url(helper::createLink('testtask', 'create', "product=$productID")),
        $lang->testtask->create
    ) : null
);

$footerHTML = strtolower($status) == 'totalstatus' ? $allSummary : $pageSummary;
dtable
(
    set::cols($cols),
    set::data($data),
    set::userMap($users),
    set::fixedLeftWidth('20%'),
    set::footer(array(array('html' => $footerHTML), 'flex', 'pager')),
    set::footPager(usePager()),
);

render();
