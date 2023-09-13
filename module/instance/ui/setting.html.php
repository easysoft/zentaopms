<?php
declare(strict_types=1);
/**
 * The setting view file of instance module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     instance
 * @link        https://www.zentao.net
 */

namespace zin;

formPanel
(
    set::id('instanceSettingForm'),
    set::title($lang->instance->setting),
    set::submitBtnText($lang->save),
    set::actions(array('submit')),
    formRow
    (
        formGroup
        (
            set::name('name'),
            set::width('500px'),
            set::required(true),
            set::label($lang->instance->name),
            set::value($instance->name),
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('memory_kb'),
            set::width('250px'),
            set::control('picker'),
            set::required(true),
            set::label($lang->instance->adjustMem),
            set::value(intval($currentResource->max->memory / 1024)),
            set::items($this->instance->filterMemOptions($currentResource)),
        )
    ),
);
