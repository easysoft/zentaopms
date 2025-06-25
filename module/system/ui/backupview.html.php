<?php
declare(strict_types=1);
/**
 * The backupview view file of system module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Li Yang<liyang@chandao.com>
 * @package     system
 * @link        https://www.zentao.net
 */
namespace zin;
$backupDetail = zget($backup, 'backup_details', array());

detailHeader
(
    to::prefix(''),
    to::title
    (
        entityLabel
        (
            set::level(1),
            set::text($title)
        )
    )
);

div
(
    !empty($backup->message) ? sectionList(div(setClass('w-full text-center pt-10'), sprintf($lang->system->backup->error->backupFailNotice, $backup->message))) : sectionList
    (
    )
);
