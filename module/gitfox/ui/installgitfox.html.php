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

set::zui(true);
global $app;
$app->loadLang('upgrade');
$app->loadLang('install');

jsVar('copySuccess', $lang->upgrade->copySuccess);
jsVar('copyFail', $lang->upgrade->copyFail);
jsVar('nextLink', 'javascript:checkGitFoxServer();');
jsVar('adminRegisterLink', $adminRegisterLink);

div
(
    setID('main'),
    setClass('flex justify-center'),
    div
    (
        setID('mainContent'),
        setClass('px-1 mt-2 w-full max-w-7xl'),
        panel
        (
            setClass('py-2'),
            set::title($title),
            set::titleClass('text-xl'),
            on::init()->call('agreeChange'),
            form
            (
                div
                (
                    setClass('block overflow-hidden h-96 p-3'),
                    col
                    (
                        div
                        (
                            setClass('text-danger mt-2 mb-4'),
                            $lang->gitfox->installGitFoxTip
                        ),
                        div
                        (
                            setClass('mb-2'),
                            p(setClass('font-semibold mb-2'), $lang->gitfox->execScript),
                            h::pre
                            (
                                setID('script'),
                                setClass('pre-wrap break-all break-words rounded-md bg-gray-100'),
                                $script,
                                btn(setClass('ghost ml-2'), set(array('icon' => 'copy', 'url' => 'javascript:copyCommand("#script");'))),
                            ),
                        ),
                        div
                        (
                            setClass('mt-2 mb-2'),
                            checkbox
                            (
                                on::change('agreeChange'),
                                set::primary(false),
                                set::checked(false),
                                html($lang->gitfox->checkInstall)
                            )
                        )
                    )
                ),
                set::actions
                (
                    array
                    (
                        array
                        (
                            'text'  => $lang->install->solution->skip,
                            'class' => 'gray-200',
                            'url'   => $adminRegisterLink
                        ),
                        array
                        (
                            'text'  => $lang->install->next,
                            'type'  => 'primary',
                            'class' => 'btn-install',
                            'url'   => 'javascript:checkGitFoxServer();'
                        )
                    )
                )
            )
        )
    )
);

render('pagebase');
