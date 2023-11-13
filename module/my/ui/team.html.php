<?php
declare(strict_types=1);
/**
 * The team view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

toolbar
(
    btngroup
    (
        btn
        (
            setClass('btn primary'),
            set::icon('plus'),
            set::url(helper::createLink('user', 'create', "deptID={$deptID}")),
            $lang->user->create
        )
    )
);

dtable
(
    set::cols($this->config->my->team->dtable->fieldList),
    set::data($users),
    set::checkable(false),
    set::orderBy($orderBy),
    set::sortLink(inlink('team', "orderBy={name}_{sortType}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
    set::fixedLeftWidth('0.2'),
    set::footPager(usePager())
);

render();

