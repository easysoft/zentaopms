<?php
declare(strict_types=1);
/**
 * The accessible view file of personnel module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     personnel
 * @link        https://www.zentao.net
 */
namespace zin;

$config->personnel->accessible->dtable->fieldList['dept']['map'] = $deptList;

$cols = array_values($config->personnel->accessible->dtable->fieldList);
$data = array_values($personnelList);

dropmenu();

featureBar
(
    li(searchToggle(set::module('accessible')))
);

$closeLink = $this->createLink('personnel', 'accessible', "programID=$programID&deptID=0");
sidebar
(
    moduleMenu
    (
        set::modules($deptTree),
        set::activeKey($deptID),
        set::closeLink($closeLink),
        set::moduleSetting(false),
        set::displaySetting(false),
    ),
);

dtable
(
    set::cols($cols),
    set::data($data),
    set::fixedLeftWidth('0.33'),
    set::onRenderCell(jsRaw('window.renderCell')),
    set::footPager(usePager()),
);

render();

