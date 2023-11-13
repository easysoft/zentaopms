<?php
declare(strict_types=1);
/**
 * The log view file of entry module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     admin
 * @link        https://www.zentao.net
 */

namespace zin;

formPanel
(
    set::title($title),
    set::formClass('border-0'),
    set::submitBtnText($lang->save),
    formGroup
    (
        set::label($lang->admin->days),
        input
        (
            set::name('days'),
            set::value($config->admin->log->saveDays)
        )
    ),
    formGroup
    (
        set::label(''),
        $lang->admin->info->log
    )
);

render();
