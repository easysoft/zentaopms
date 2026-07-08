<?php
declare(strict_types=1);
/**
 * The devopsintroduction view file of install module of ZenTaoPMS.
 * @copyright   Copyright 2009-2026 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     install
 * @link        https://www.zentao.net
 */
namespace zin;
global $app;
$app->loadLang('install');

set::zui(true);

div
(
    setID('main'),
    setClass('flex justify-center'),
    div
    (
        setID('mainContent'),
        setClass('px-auto mt-2 w-full max-w-7xl'),
        panel
        (
            setClass('py-2'),
            set::title($title),
            set::titleClass('text-xl'),
            form
            (
                div
                (
                    setClass('block overflow-hidden h-auto p-5 progress text-md'),
                    html($lang->gitfox->devopsDescription)
                ),
                set::actions
                (
                    array
                    (
                        $isInstall ? array
                        (
                            'text'  => $lang->install->solution->skip,
                            'class' => 'gray-200',
                            'url'   => $adminRegisterLink
                        ) : null,
                        array
                        (
                            'text'  => $lang->install->start,
                            'class' => 'primary',
                            'url'   => $devopsLink
                        )
                    )
                )
            )
        )
    )
);

render('pagebase');
