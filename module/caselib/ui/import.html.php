<?php
declare(strict_types=1);
/**
 * The import view file of caselib module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     caselib
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader
(
    set::entityText($lang->testcase->fileImport),
    set::id(''),
    set::title('')
);

formPanel
(
    set::formClass('border-0'),
    formRow
    (
        formGroup
        (
            set::label($lang->caselib->selectFile),
            fileInput()
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->caselib->encode),
            set::name('encode'),
            set::items($config->charsets[$this->cookie->lang]),
            set::value('utf-8')
        )
    )
);

render();

