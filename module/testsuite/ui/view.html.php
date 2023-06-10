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
            set::entityID(17),
            set::level(1),
            set::text($suite->name)
        )
    ),
    to::suffix
    (
        btngroup
        (
            common::hasPriv('testsuite', 'linkCase') ? a
            (
                setClass('ghost btn btn-default'),
                set::href(createLink('testsuite', 'linkCase', "suiteID={$suite->id}")),
                icon('link'),
                $lang->testsuite->linkCase,
            ) : '',
            common::hasPriv('testsuite', 'edit') ? a
            (
                setClass('ghost btn btn-default'),
                set::href(createLink('testsuite', 'edit', "suiteID={$suite->id}")),
                icon('edit'),
            ) : '',
            common::hasPriv('testsuite', 'delete') ? a
            (
                setClass('ghost btn btn-default'),
                set::href(createLink('testsuite', 'delete', "suiteID={$suite->id}")),
                icon('trash'),
            ) : '',
        ),
    ),
);

$canBatchEdit   = common::hasPriv('testcase', 'batchEdit');
$canBatchUnlink = common::hasPriv('testsuite', 'batchUnlinkCases');
$canBatchRun    = common::hasPriv('testtask', 'batchRun');
$hasCheckbox    = ($canBeChanged && $canBatchEdit && $canBatchUnlink && $canBatchRun);

$footToolbar = array('items' => array
(
    array('type' => 'btn-group', 'items' => array
    (
        array('text' => $lang->edit, 'className' => 'batch-btn', 'data-url' => createLink('testcase', 'batchEdit', "productID={$productID}")),
        array('caret' => 'up', 'btnType' => 'primary', 'url' => '#navActions', 'data-toggle' => 'dropdown', 'data-placement' => 'top-start'),
    )),
));

menu
(
    set::id('navActions'),
    set::class('menu dropdown-menu'),
    set::items(array
    (
        $canBatchUnlink ? array('text' => $lang->testsuite->unlinkCase, 'className' => 'batch-btn', 'data-url' => helper::createLink('testsuite', 'batchUnlinkCases', "suiteID={$suite->id}")) : '',
        $canBatchRun ? array('text' => $lang->testtask->runCase, 'className' => 'batch-btn', 'data-url' => helper::createLink('testtask', 'batchRun', "productID={$productID}&&orderBy={$orderBy}")) : '',
    ))
);


$tableData = initTableData($cases, $config->testsuite->testcase->dtable->fieldList, $this->testcase);
detailBody
(
    sectionList
    (
        dtable
        (
            set::cols(array_values($config->testsuite->testcase->dtable->fieldList)),
            set::data(array_values($tableData)),
            set::checkable($hasCheckbox),
            set::footToolbar($footToolbar),
            set::footPager(usePager()),
        ),
    ),
    detailSide
    (
        sectionList
        (
            section
            (
                set::title($lang->testsuite->legendDesc),
                set::content($suite->desc),
                set::useHtml(true)
            ),
        ),
        history(),
    ),
);

render();

