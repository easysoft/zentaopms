<?php
declare(strict_types=1);
/**
 * The backup view file of system module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     system
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('waitting', $lang->system->backup->waitting);

modalHeader(set::title($lang->system->backup->backup));
formPanel
(
    on::click('#startBackup', 'startBackup'),
    set::id('backupForm'),
    formGroup
    (
        set::label($lang->comment),
        set::name('comment'),
        set::control('textarea'),
        set::rows('6'),
    ),
    set::actions(array
    (
        array('text' => $lang->confirm, 'id' => 'startBackup', 'class' => 'primary'),
    )),
);

render();
