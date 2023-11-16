<?php
declare(strict_types=1);
/**
 * The browse view file of testsuite module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     testsuite
 * @link        https://www.zentao.net
 */
namespace zin;

featureBar
(
    detailHeader
    (
        to::title
        (
            entityLabel(set(array('entityID' => $suite->id, 'level' => 3, 'text' => $suite->name))),
            icon('angle-right'),
            $lang->testsuite->linkCase,
            li(searchToggle(set::open(true)))
        )
    )
);

$footToolbar = array('items' => array
(
    array('text' => $lang->save, 'className' => 'batch-btn not-open-url ajax-btn', 'data-url' => helper::createLink('testsuite', 'linkCase', "suiteID=$suiteID&param=$param"))
), 'btnProps' => array('size' => 'sm', 'btnType' => 'secondary'));

div
(
    setClass('mb-2'),
    icon('unlink'),
    span
    (
        setClass('font-semibold ml-2'),
        $lang->testsuite->unlinkedCases . "({$pager->recTotal})"
    )
);
$cases = initTableData($cases, $config->testsuite->linkcase->dtable->fieldList, $this->testcase);
$data  = array_values($cases);
dtable
(
    set::userMap($users),
    set::data($data),
    set::cols($config->testsuite->linkcase->dtable->fieldList),
    set::fixedLeftWidth('33%'),
    set::checkable(true),
    set::footToolbar($footToolbar),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footPager(usePager())
);

render();

