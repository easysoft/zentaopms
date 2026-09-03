<?php
declare(strict_types=1);
/**
 * The setting view file of errorlog module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      ZenTao Team
 * @package     errorlog
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->errorlog->setting));

formPanel
(
    set::submitBtnText($lang->save),
    formGroup
    (
        set::label($lang->errorlog->days),
        input
        (
            set::name('days'),
            set::type('number'),
            set::min(1),
            set::max($config->errorlog->maxSaveDays),
            set::placeholder(sprintf('%d ~ %d', 1, $config->errorlog->maxSaveDays)),
            set::value($config->errorlog->saveDays)
        )
    ),
    formGroup
    (
        set::label(''),
        $lang->errorlog->info
    )
);

render();
