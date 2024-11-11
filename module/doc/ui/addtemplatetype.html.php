<?php
declare(strict_types=1);
/**
 * The addtemplatetype view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zhou Xin<zhouxin@chandao.net>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->docTemplate->addTemplateType));
formPanel
(
    setID('addForm'),
    set::submitBtnText($lang->save),
    formGroup
    (
        set::label($lang->docTemplate->scope),
        picker
        (
            set::name('root'),
            set::value($scope),
            set::items($scopes),
            set::required(true),
            on::change('changeScope')
        )
    ),
    formGroup
    (
        set::label($lang->docTemplate->parentModule),
        picker
        (
            set::name('parent'),
            set::value($parentModule),
            set::items($moduleItems),
            set::required(true)
        )
    ),
    formGroup
    (
        set::name('name'),
        set::label($lang->docTemplate->typeName),
        set::control('input')
    )
);
