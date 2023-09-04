<?php
declare(strict_types=1);
/**
 * The edit externalapp view file of instance module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng gang<zenggang@easycorp.ltd>
 * @package     instance
 * @link        https://www.zentao.net
 */

namespace zin;

formPanel
(
    set::id('appCreateForm'),
    set::title($lang->edit . $lang->space->appType[$app->type]),
    set::submitBtnText($lang->save),
    set::actions(array('submit', array('text' => $lang->cancel, 'data-type' => 'submit', 'data-dismiss' => 'modal'))),
    formRow
    (
        formGroup
        (
            set::name('name'),
            set::required(true),
            set::label($lang->sonarqube->name),
            set::value($app->name),
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('url'),
            set::required(true),
            set::label($lang->sonarqube->url),
            set::value($app->url),
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('account'),
            set::label($lang->sonarqube->account),
            set::value($app->account),
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('password'),
            set::label($lang->sonarqube->password),
            set::value($app->password),
        )
    ),
);
