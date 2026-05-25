<?php
declare(strict_types=1);
/**
 * The create view file of artifact module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     artifact
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($title),
    set::labelWidth('100px'),
    set::submitBtnText($lang->artifact->okBtn),
    on::change('[name=format]')->do("const \$name = \$element.find('[name=name]'); \$name.attr('placeholder', target.value === 'file' ? '{$lang->artifact->placeholder->name}' : '{$lang->artifact->notice->nameNotSupportChinese}');"),
    formGroup
    (
        set::label($lang->artifact->format),
        set::name('format'),
        set::required(true),
        set::items($lang->artifact->formatList)
    ),
    formGroup
    (
        set::label($lang->artifact->name),
        set::name('name'),
        set::required(true),
        set::placeholder($lang->artifact->placeholder->name)
    )
);
