<?php
declare(strict_types=1);
/**
 * The edit view file of gitfox module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     gitfox
 * @link        https://www.zentao.net
 */

namespace zin;

formPanel
(
    set::id('gitfoxCreateForm'),
    set::title($lang->gitfox->edit),
    set::submitBtnText($lang->save),
    set::actions(array('submit', array('text' => $lang->cancel, 'data-type' => 'submit', 'data-dismiss' => 'modal'))),
    formRow
    (
        formGroup
        (
            set::name('name'),
            set::label($lang->gitfox->name),
            set::value($gitfox->name),
            set::placeholder($lang->gitfox->placeholder->name)
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('url'),
            set::label($lang->gitfox->url),
            set::value($gitfox->url),
            set::placeholder($lang->gitfox->placeholder->url)
        )
    ),
    formRow
    (
        formGroup
        (
            set::name('token'),
            set::label($lang->gitfox->token),
            set::value($gitfox->token),
            set::placeholder($lang->gitfox->placeholder->token)
        )
    )
);
