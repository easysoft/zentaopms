<?php
declare(strict_types=1);
/**
 * The domainview view file of system module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     system
 * @link        https://www.zentao.net
 */
namespace zin;

$expiredDate = !empty($cert) ? zget($cert, 'expiredDate', '') : '';

panel
(
    set::size('lg'),
    set::title($lang->system->domain->common),
    to::headingActions
    (
        btn
        (
            setClass('primary'),
            $lang->system->domain->editDomain,
            set::url($this->createLink('system', 'editDomain')),
        ),
    ),
    tableData
    (
        item
        (
            set::name($lang->system->domain->currentDomain),
            zget($domainSettings, 'customDomain', ''),
        ),
        item
        (
            set::name($lang->system->domain->expiredDate),
            $expiredDate
        ),
    ),
);

render();
