<?php
declare(strict_types=1);
/**
 * The license view file of install module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     install
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

jsVar('nextLink', $config->inQuickon ? inlink('step5') : inlink('step1'));

div
(
    set::id('main'),
    div
    (
        set::id('mainContent'),
        setClass('px-1 mt-2'),
        panel
        (
            setClass('p-8'),
            set::title($lang->install->license),
            set::titleClass('text-xl'),
            textarea($license),
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
                setClass('text-center'),
                btn
                (
                    setClass('px-6 btn-install'),
                    $config->inQuickon ? set::url(inlink('step5')) : set::url(inlink('step1')),
                    set::type('primary'),
                    $lang->install->next
                )
            )
        )
    )
);

render('pagebase');
