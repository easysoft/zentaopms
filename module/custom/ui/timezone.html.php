<?php
declare(strict_types=1);
/**
 * The timezone view file of custom module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     custom
 * @link        https://www.zentao.net
 */
namespace zin;

if(!function_exists('date_default_timezone_set'))
{
    panel
    (
        div
        (
            setClass('alert warning-pale bd bd-warning w-1/3'),
            $lang->custom->notice->cannotSetTimezone
        )
    );
}
else
{
    include $this->app->getConfigRoot() . 'timezones.php';
    formPanel
    (
        setID('timezoneForm'),
        set::actions(array('submit')),
        set::actionsClass('w-1/3'),
        formRow
        (
            set::width('1/3'),
            formGroup
            (
                set::label($lang->custom->timezone),
                picker
                (
                    set::name('timezone'),
                    set::items($timezoneList),
                    set::value($config->timezone)
                )
            )
        )
    );
}

/* ====== Render page ====== */
render();
