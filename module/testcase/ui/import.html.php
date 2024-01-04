<?php
declare(strict_types=1);
/**
 * The import view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

set::title($lang->testcase->fileImport);

formPanel
(
    formGroup
    (
        set::label($lang->testcase->importFile),
        fileInput()
    ),
    formGroup
    (
        set::label($lang->testcase->encoding),
        picker
        (
            set::name('encode'),
            set::items($config->charsets[$this->cookie->lang]),
            set::required(true),
            set::value('utf-8')
        )
    )
);

render('modalDialog');

