<?php
declare(strict_types=1);
/**
 * The edit view file of jenkins module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     jenkins
 * @link        https://www.zentao.net
 */

namespace zin;

formPanel
(
    set::id('jenkinsCreateForm'),
    set::title($lang->jenkins->edit),
    set::submitBtnText($lang->save),
    set::actions(array('submit', array('text' => $lang->cancel, 'data-type' => 'submit', 'data-dismiss' => 'modal'))),
    formRow
    (
        formGroup
        (
            set::name('name'),
            set::label($lang->jenkins->name),
            set::value($jenkins->name),
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('url'),
            set::label($lang->jenkins->url),
            set::value($jenkins->url),
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('account'),
            set::label($lang->jenkins->account),
            set::value($jenkins->account),
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('token'),
            set::label($lang->jenkins->token),
            set::value($jenkins->token),
            set::placeholder($lang->jenkins->tokenFirst)
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('password'),
            set::label($lang->jenkins->password),
            set::value($jenkins->password),
            set::placeholder($lang->jenkins->tips)
        )
    ),
);
