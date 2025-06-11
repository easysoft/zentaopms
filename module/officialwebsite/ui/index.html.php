<?php
declare(strict_types=1);
/**
 * The index view file of officialwebsite module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jialiang Zhang <zhangjialiang@chandao.com>
 * @package     officialwebsite
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

div
(
    setID('main'),
    setClass('flex justify-center'),
    div
    (
        setID('mainContent'),
        setClass('px-1 mt-2 w-full max-w-7xl'),
        formPanel
        (
            setClass('bg-canvas m-auto mw-auto'),
            set::headingClass('w-96 m-auto'),
            set::submitBtnText($lang->save),
            formRow
            (
                setClass('w-96 m-auto'),
                formGroup
                (
                    set::label('图形验证码'),
                    set::name('code')
                )
            )
        )
    )
);

render('pagebase');
