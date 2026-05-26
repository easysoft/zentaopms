<?php
declare(strict_types=1);
/**
 * The flow view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gunagming Sun <sungunagming@chandao.com>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

include 'header.html.php';

$dataList = initTableData($dataList, $cols, $this->flow);

foreach($dataList as $id => $data)
{
    if(empty($data->actions)) continue;
    foreach($data->actions as $actionID => $action)
    {
        if(!is_array($action)) continue;
        if($action['name'] == 'approvalsubmit' && !in_array($data->reviewStatus, array('', 'wait', 'reject', 'reverting'))) $dataList[$id]->actions[$actionID]['disabled'] = true;
        if($action['name'] == 'approvalcancel' && !$this->approval->canCancel($data)) $dataList[$id]->actions[$actionID]['disabled'] = true;
        if($action['name'] == 'approvalreview' && !isset($pendingReviews[$data->id])) $dataList[$id]->actions[$actionID]['disabled'] = true;
    }
}

$workLinkParams = "mode={$flowModule}&type={$type}&param={$param}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";
$sortLink       = createLink('my', 'work', "{$workLinkParams}&orderBy={name}_{sortType}");

$processToolbarItems = function(array $toolbarItems) use ($lang): array
{
    $items = array();

    if(isset($toolbarItems['reportItem'])) $items[] = item(set($toolbarItems['reportItem']));

    if(isset($toolbarItems['exportItems']))
    {
        $items[] = dropdown
        (
            btn(setClass('btn ghost'), set::icon('export'), $lang->export),
            set::items($toolbarItems['exportItems']),
            set::placement('bottom-end')
        );
    }

    if(isset($toolbarItems['importItems']))
    {
        $items[] = dropdown
        (
            btn(setClass('btn ghost'), set::icon('import'), $lang->import),
            set::items($toolbarItems['importItems']),
            set::placement('bottom-end')
        );
    }

    $createItem      = zget($toolbarItems, 'createItem', null);
    $batchCreateItem = zget($toolbarItems, 'batchCreateItem', null);
    if($createItem && $batchCreateItem) $items[] = btngroup
    (
        btn
        (
            setClass('btn primary '),
            set::icon('plus'),
            set::url($createItem['url']), $createItem['text'],
            !empty($createItem['data-app']) ? set('data-app', $createItem['data-app']) : null
        ),
        dropdown
        (
            btn(setClass('btn primary dropdown-toggle'), setStyle(array('padding' => '6px', 'border-radius' => '0 2px 2px 0'))),
            set::items(array($createItem, $batchCreateItem)),
            set::placement('bottom-end')
        )
    );
    elseif($createItem && !$batchCreateItem) $items[] = item(set(array('class' => 'btn primary', 'icon' => 'plus') + $createItem));
    elseif($batchCreateItem && !$createItem) $items[] = item(set(array('class' => 'btn primary', 'icon' => 'plus') + $batchCreateItem));

    return $items;
};

featureBar
(
    set::items($featureBarItems),
    !empty($canSearch) ? li(searchToggle(set::module($flow->module), set::open($browseMode == 'bysearch'))) : null
);

toolbar
(
    setID('actionBar'),
    $processToolbarItems($toolbarItems),
);

if($categories)
{
    $categoryItems = array();
    foreach($categories as $value => $category)
    {
        $key = substr($value, strripos($value, '_') + 1);
        $categoryItems[] = array(
            'text' => $category->name,
            'url'  => createLink('my', 'work', "mode={$flowModule}&type={$type}&param={$param}&orderBy={$orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}&category={$key}=0"),
            'data-app' => $app->tab
        );
    }

    sidebar
    (
        moduleMenu
        (
            to::header(dropdown
            (
                btn
                (
                    setClass('ghost category-dropdown'),
                    $currentCategory->name
                ),
                set::items($categoryItems),
            )),
            set(array
            (
                'modules'     => $currentCategory->treeMenu,
                'activeKey'   => $categoryValue,
                'settingLink' => createLink('tree', 'browse', "rootID=0&type={$currentCategory->type}&currentModuleID=0&branch=&from={$flow->module}"),
                'showDisplay' => false,
                'settingApp'  => $app->tab
            ))
        )
    );
}

dtable
(
    setID('dataList'),
    set::cols($cols),
    set::data(array_values($dataList)),
    set::customCols(true),
    set::moduleName($flow->module),
    set::checkable(!empty($footToolbar)),
    set::orderBy($orderBy),
    set::sortLink($sortLink),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
);