<?php
declare(strict_types=1);
/**
 * The view view file of testsuite module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     testsuite
 * @link        https://www.zentao.net
 */
namespace zin;

data('testsuite', $suite);
$config->testsuite->actionList['edit']['text'] = $config->testsuite->actionList['edit']['hint'] = $lang->edit;
$config->testsuite->actionList['delete']['text'] = $config->testsuite->actionList['delete']['hint'] = $lang->delete;
$actions = $this->loadModel('common')->buildOperateMenu($suite);
foreach($actions as $actionType => $typeActions)
{
    foreach($typeActions as $key => $action)
    {
        $actions[$actionType][$key]['className'] = isset($action['className']) ? $action['className'] . ' ghost' : 'ghost';
        $actions[$actionType][$key]['iconClass'] = isset($action['iconClass']) ? $action['iconClass'] . ' text-primary' : 'text-primary';
        $actions[$actionType][$key]['url']       = str_replace('{id}', (string)$suite->id, $action['url']);
    }
}

detailHeader
(
    to::title
    (
        entityLabel
        (
            set::entityID($suite->id),
            set::level(1),
            set::text($suite->name)
        ),
        $suite->deleted ? span(setClass('label danger'), $lang->testsuite->deleted) : null
    ),
    to::suffix
    (
        toolbar
        (
            set::items($actions['mainActions'])
        )
    )
);

$canBatchEdit   = common::hasPriv('testcase', 'batchEdit');
$canBatchUnlink = common::hasPriv('testsuite', 'batchUnlinkCases');
$canBatchRun    = common::hasPriv('testtask', 'batchRun');
$hasCheckbox    = ($canBeChanged && $canBatchEdit && $canBatchUnlink && $canBatchRun);

$batchItems = array(
    $canBatchUnlink ? array('text' => $lang->testsuite->unlinkCase, 'innerClass' => 'batch-btn ajax-btn not-open-url', 'data-url' => helper::createLink('testsuite', 'batchUnlinkCases', "suiteID={$suite->id}"))              : null,
    $canBatchRun    ? array('text' => $lang->testtask->runCase,     'innerClass' => 'batch-btn not-open-url',          'data-url' => helper::createLink('testtask', 'batchRun', "productID={$productID}&&orderBy={$orderBy}")) : null,
);

$footToolbar = array('items' => array(
    array('type' => 'btn-group', 'items' => array
    (
        array('text' => $lang->edit, 'className' => 'batch-btn', 'data-url' => createLink('testcase', 'batchEdit', "productID={$productID}")),
        array('caret' => 'up',       'className' => 'btn btn-caret size-sm not-open-url', 'data-placement' => 'top-start', 'items' => $batchItems),
    )),
), 'btnProps' => array('size' => 'sm', 'btnType' => 'secondary'));

$config->testsuite->testcase->dtable->fieldList['module']['map'] = $modules;

$tableData = initTableData($cases, $config->testsuite->testcase->dtable->fieldList);
detailBody
(
    sectionList
    (
        dtable
        (
            set::cols(array_values($config->testsuite->testcase->dtable->fieldList)),
            set::data(array_values($tableData)),
            set::fixedLeftWidth('0.5'),
            set::checkable($hasCheckbox),
            set::orderBy($orderBy),
            set::sortLink(createLink('testsuite', 'view', "suiteID={$suite->id}&orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
            set::footToolbar($footToolbar),
            set::footPager(usePager('pager'))
        )
    ),
    history(
        set::hasComment(false),
        set::commentBtn(false),
        set::methodName('view_1'),
        set::objectID($suite->id)
    ),
    detailSide
    (
        section
        (
            setClass('py-4'),
            set::title($lang->testsuite->legendDesc),
            set::content(!empty($suite->desc) ? $suite->desc : "<span class='text-gray'>{$lang->noDesc}</span>"),
            set::useHtml(true)
        )
    )
);
