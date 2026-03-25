<?php
declare(strict_types=1);
/**
 * The create plan view file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($title),
    set::actions(array()),
    formRow
    (
        formGroup
        (
            set::name('ignoreDate'),
            set::required(true),
            set::label($lang->codescan->ignoreDate),
            set::items($lang->codescan->ignoreDateList)
        ),
        btn
        (
            set::text($lang->save),
            set::type('primary'),
            set::btnType('submit')
        )
    )
);
