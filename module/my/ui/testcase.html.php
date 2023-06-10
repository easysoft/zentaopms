<?php
declare(strict_types=1);
/**
 * The story view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

featureBar
(
    set::current($type),
    set::linkParams("mode=testcase&type={key}&param={$param}"),
    li(searchToggle())
);

$footToolbar = array('items' => array
(
    array('text' => $lang->edit, 'className' => 'batch-btn ' . (common::hasPriv('testcase', 'batchEdit') ? '' : 'hidden'), 'data-url' => helper::createLink('testcase', 'batchEdit', 'productID=0&branch=all&type=case&tab=my'))
), 'btnProps' => array('size' => 'sm', 'btnType' => 'secondary'));

if($type == 'openedbyme')
{
    unset($config->my->testcase->dtable->fieldList['testtask']);
    unset($config->my->testcase->dtable->fieldList['openedBy']);
}

$cases = initTableData($cases, $config->my->testcase->dtable->fieldList, $this->testcase);
$cols  = array_values($config->my->testcase->dtable->fieldList);
$data  = array_values($cases);
dtable
(
    set::cols($cols),
    set::data($data),
    set::userMap($users),
    set::fixedLeftWidth('44%'),
    set::checkable(true),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
);

render();
