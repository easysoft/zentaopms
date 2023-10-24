<?php
declare(strict_types=1);
/**
 * The browsebackup view file of system module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     system
 * @link        https://www.zentao.net
 */
namespace zin;

$backups = initTableData($backups, $config->system->dtable->backup->fieldList, $this->system);

panel
(
    set::size('lg'),
    set::title($lang->backup->history),
    to::headingActions
    (
        a
        (
            setClass('btn primary'),
            icon('copy'),
            set::href($this->createLink('system', 'backupPlatform')),
            set::target('hiddenwin'),
            $lang->backup->backup,
            set('data-toggle', 'modal'),
        ),
    ),
    div
    (
        setStyle('width', '66.6%'),
        dtable
        (
            set::cols($config->system->dtable->backup->fieldList),
            set::data($backups),
        ),
    ),
);

render();

