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
            common::hasPriv('testsuite', 'linkCase') ? a
            (
                setClass('ghost btn btn-default'),
                set::href(createLink('testsuite', 'linkCase', "suiteID={$suite->id}")),
                icon('link', setClass('text-primary')),
                $lang->testsuite->linkCase,
            ) : '',
            div(setClass('toolbar-divider')),
            common::hasPriv('testsuite', 'edit') ? a
            (
                setClass('ghost btn btn-default'),
                set::href(createLink('testsuite', 'edit', "suiteID={$suite->id}")),
                icon('edit', setClass('text-primary')),
                $lang->edit,
            ) : '',
            common::hasPriv('testsuite', 'delete') ? a
            (
                setClass('ghost btn btn-default'),
                setData('confirm', $lang->testsuite->confirmDelete),
                set::href(createLink('testsuite', 'delete', "suiteID={$suite->id}")),
                icon('trash', setClass('text-primary')),
                $lang->delete,
            ) : ''
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

render();

