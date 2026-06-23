<?php
declare(strict_types=1);
/**
 * The team view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

toolbar
(
    hasPriv('user', 'create') ? btn
    (
        setClass('btn primary'),
        set::icon('plus'),
        set::url(helper::createLink('user', 'create', "deptID={$deptID}")),
        $lang->user->create
    ) : null
);

$cols = $this->loadModel('datatable')->getSetting('my', 'team');
if(isset($cols['dept']))     $cols['dept']['map']     = $this->loadModel('dept')->getOptionMenu();
if(isset($cols['superior'])) $cols['superior']['map'] = $userPairs;

$tableData = initTableData($users, $cols, $this->loadModel('user'));
dtable
(
    set::customCols(true),
    set::cols($cols),
    set::data($tableData),
    set::checkable(false),
    set::orderBy($orderBy),
    set::userMap($userPairs),
    set::sortLink(inlink('team', "orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::footPager(usePager())
);

render();
