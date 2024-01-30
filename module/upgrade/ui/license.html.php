<?php
declare(strict_types=1);
/**
 * The license view file of upgrade module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     upgrade
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

jsVar('confirmLink', inlink('license', 'agree=true'));

div
(
    setID('main'),
    div
    (
        setID('mainContent'),
        setClass('px-1 mt-2 mx-auto'),
        width('1200px'),
        panel
        (
            setClass('py-8 px-2'),
            set::title($lang->upgrade->license),
            set::titleClass('text-xl'),
            set::actions(array()),
            h::textarea
            (
                setClass('form-control'),
                setStyle(array('background-color' => 'unset')),
                set::rows('10'),
                set::readonly('readonly'),
                $license
            ),
            cell
            (
                setClass('mt-2'),
                checkbox
                (
                    on::change('agreeChange'),
                    set::checked(true),
                    html($lang->agreement)
                )
            ),
            cell
            (
                setClass('text-center mt-6'),
                btn
                (
                    setClass('px-8 btn-install'),
                    set::url(inlink('license', 'agree=true')),
                    set::type('primary'),
                    $lang->confirm
                )
            )
        )
    )
);

render('pagebase');
