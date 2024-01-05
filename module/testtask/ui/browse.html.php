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
$viewName       = $scope == 'local'? \zget($products, $product->id) : $lang->testtask->all;
jsVar('condition', "productID=$product->id&branch=$branch&type=$type&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}&pageID=1");

$testMenuLink = createLink($this->app->rawModule, $this->app->rawMethod, array('productID' => $product->id, 'branch' => '{branch}', 'type' => '{type}'));

$productDropdown = dropdown
(
    to('trigger', btn($viewName, setClass('ghost'))),
    set::items(array
    (
        array('text' => $lang->testtask->all, 'url' => createLink($this->app->rawModule, $this->app->rawMethod, array('productID' => $product->id, 'branch' => '0', 'type' => "all,{$status}"))),
        array('text' => \zget($products, $product->id), 'url' => createLink($this->app->rawModule, $this->app->rawMethod, array('productID' => $product->id, 'branch' => $branch, 'type' => "local,{$status}")))
    ))
);

featureBar
(
    set::current($status),
    set::linkParams("productID={$product->id}&branch={$branch}&type={$scope},{key}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"),
    to::before($productDropdown),
    inputGroup
    (
        set::className('ml-4'),
        $lang->testtask->beginAndEnd,
        datePicker
        (
            setClass('w-28'),
            set::name('begin'),
            set::value($beginTime),
            on::change('changeBrowseDate')
        ),
        $lang->testtask->to,
        datePicker
        (
            setClass('w-28'),
            set::name('end'),
            set::value($endTime),
            on::change('changeBrowseDate')
        )
    )
);

if($product->shadow) unset($config->testtask->dtable->fieldList['product']);

$canCreate = common::canModify('product', $product) && common::hasPriv('testtask', 'create');

toolbar
(
    $canCreate ? btn
    (
        setClass('btn primary'),
        set::icon('plus'),
        set::url(inlink('create', "product=$product->id")),
        $lang->testtask->create
    ) : null
);

$tasks      = initTableData($tasks, $config->testtask->dtable->fieldList, $this->testtask);
$cols       = array_values($config->testtask->dtable->fieldList);
$data       = array_values($tasks);
$footerHTML = strtolower($status) == 'totalstatus' ? $allSummary : $pageSummary;
dtable
(
    set::cols($cols),
    set::data($data),
    set::userMap($users),
    set::fixedLeftWidth('20%'),
    set::orderBy($orderBy),
    set::sortLink(createLink('testtask', 'browse', "productID={$product->id}&branch={$branch}&type={$type}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&beginTime={$beginTime}&endTime={$endTime}")),
    set::footer(array(array('html' => $footerHTML), 'flex', 'pager')),
    set::footPager(usePager()),
    set::emptyTip($lang->testtask->noTesttask),
    set::createTip($lang->testtask->create),
    set::createLink($canCreate ? inlink('create', "product={$product->id}") : '')
);

render();
