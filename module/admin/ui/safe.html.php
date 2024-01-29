<?php
declare(strict_types=1);
/**
 * The safe view file of admin module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     admin
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('adminLang', $lang->admin);

$menuItems = array();
if(common::hasPriv('admin', 'safe'))
{
    $menuItems[] = li
    (
        setClass('menu-item'),
        a
        (
            setClass('active'),
            set::href(createLink('admin', 'safe')),
            $lang->admin->safe->set
        )
    );
}
if(common::hasPriv('admin', 'checkWeak'))
{
    $menuItems[] = li
    (
        setClass('menu-item'),
        a
        (
            set::href(createLink('admin', 'checkWeak')),
            $lang->admin->safe->checkWeak
        )
    );
}
if(common::hasPriv('admin', 'resetPWDSetting'))
{
    $menuItems[] = li
    (
        setClass('menu-item'),
        a
        (
            set::href(createLink('admin', 'resetPWDSetting')),
            $lang->admin->resetPWDSetting
        )
    );
}

div
(
    setID('mainContent'),
    setClass('row has-sidebar-left'),
    $menuItems ? sidebar
    (
        set::showToggle(false),
        div
        (
            setClass('cell p-2.5 bg-white'),
            menu
            (
                $menuItems
            )
        )
    ) : null,
    formPanel
    (
        setClass('admin-safe-form'),
        formRow
        (
            formGroup
            (
                on::change('showModeRule'),
                set::label($lang->admin->safe->password),
                inputGroup
                (
                    radioList
                    (
                        set::name('mode'),
                        set::items($lang->admin->safe->modeList),
                        set::value(isset($config->safe->mode) ? $config->safe->mode : 0),
                        set::inline(true)
                    ),
                    span
                    (
                        setClass('flex items-center ml-8 text-gray safe-notice'),
                        icon
                        (
                            setClass('text-warning mr-1'),
                            'help'
                        ),
                        span
                        (
                            !empty($config->safe->changeWeak) ? $lang->admin->safe->noticeWeakMode : $lang->admin->safe->noticeMode
                        )
                    )
                )
            )
        ),
        formRow
        (
            setID('mode1Rule'),
            $config->safe->mode != 1 ? setClass('hidden') : '',
            formGroup
            (
                span
                (
                    setClass('flex items-center text-gray'),
                    icon
                    (
                        setClass('text-warning mr-1'),
                        'help'
                    ),
                    $lang->admin->safe->modeRuleList[1] . $lang->admin->safe->noticeStrong
                )
            )
        ),
        formRow
        (
            setID('mode2Rule'),
            $config->safe->mode != 2 ? setClass('hidden') : '',
            formGroup
            (
                span
                (
                    setClass('flex items-center text-gray'),
                    icon
                    (
                        setClass('text-warning mr-1'),
                        'help'
                    ),
                    $lang->admin->safe->modeRuleList[2] . $lang->admin->safe->noticeStrong
                )
            )
        ),
        formRow
        (
            formGroup
            (
                set::label($lang->admin->safe->weak),
                textarea
                (
                    set::name('weak'),
                    set::value($config->safe->weak),
                    set::rows('5')
                )
            )
        ),
        formRow
        (
            formGroup
            (
                on::change('changeWeakChange'),
                set::label($lang->admin->safe->changeWeak),
                set::name('changeWeak'),
                set::control('radioListInline'),
                set::items($lang->admin->safe->modifyPasswordList),
                set::value(isset($config->safe->changeWeak) ? $config->safe->changeWeak : 0)
            )
        ),
        formRow
        (
            formGroup
            (
                set::label($lang->admin->safe->modifyPasswordFirstLogin),
                set::name('modifyPasswordFirstLogin'),
                set::control('radioListInline'),
                set::items($lang->admin->safe->modifyPasswordList),
                set::value(isset($config->safe->modifyPasswordFirstLogin) ? $config->safe->modifyPasswordFirstLogin : 0)
            )
        ),
        formRow
        (
            formGroup
            (
                set::label($lang->admin->safe->loginCaptcha),
                inputGroup
                (
                    radioList
                    (
                        set::name('loginCaptcha'),
                        set::items($lang->admin->safe->loginCaptchaList),
                        set::value(isset($config->safe->loginCaptcha) ? $config->safe->loginCaptcha : 0),
                        set::inline(true),
                        set::disabled(!extension_loaded('gd') || empty($gdInfo['FreeType Support']))
                    ),
                    (!extension_loaded('gd') || empty($gdInfo['FreeType Support'])) ? span
                    (
                        setClass('flex items-center ml-8 text-gray'),
                        icon
                        (
                            setClass('text-warning mr-1'),
                            'help'
                        ),
                        $lang->admin->safe->noticeGd,
                        formHidden('loginCaptcha', isset($config->safe->loginCaptcha) ? $config->safe->loginCaptcha : 0)
                    ) : null
                )
            )
        )
    )
);


render();

