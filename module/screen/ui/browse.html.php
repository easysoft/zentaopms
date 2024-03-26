<?php
declare(strict_types = 1);
/**
 * The browse view file of screen module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     screen
 * @link        https://www.zentao.net
 */

namespace zin;

jsVar('showGuide', $showGuide);

if(empty($screens))
{
    div
    (
        setClass('flex justify-center items-center text-gray bg-white h-48'),
        $lang->screen->noscreens
    );
}
else
{
    $items   = array();
    $canView = hasPriv('screen', 'view');

    foreach($screens as $screenID => $screen)
    {
        if($screenID == 3 && !hasPriv('screen', 'annualData')) continue;

        $content = div
        (
            setClass('border border-strong'),
            div
            (
                setClass("h-48 image_{$screen->status}"),
                img(setClass('h-full object-cover object-top'), set(array('src' => $screen->src, 'width' => '100%')))
            ),
            div
            (
                setClass('px-4 py-3 h-20 relative'),
                setData(array('builtin' => $screen->builtin, 'status' => $screen->status)),
                div(setClass('text-black text-md overflow-hidden'), set::title($screen->name), $screen->name),
                div(setClass('text-gray text-sm overflow-hidden'), set::title($screen->name), $screen->desc ?: $lang->screen->noDesc),
                !empty($screen->actions) ? div
                (
                    setClass('absolute right-0 top-0'),
                    dropdown
                    (
                        btn
                        (
                            set::type('ghost'),
                            set::icon('ellipsis-v'),
                            set::caret(false),
                            on::click()->prevent()->stop()
                        ),
                        set::items($screen->actions),
                        set::flip(true),
                        set::placement('bottom-center'),
                        set::strategy('absolute'),
                        set::hasIcons(false),
                        set::trigger('hover'),
                    )
                ) : null
            ),
        );

        $items[] = div
        (
            setData(array('id' => $screen->id)),
            setClass('pl-2.5 pr-2.5 mb-2 w-1/4'),
            $canView ? a
            (
                set::href(createLink('screen', 'view', "id={$screen->id}")),
                set::target('_blank'),
                $content
            ) : $content
        );
    }

    div(setClass('flex flex-wrap'),$items);
}


if($showGuide)
{
    modal
    (
        setID('firstGuide'),
        set::closeBtn(false),
        set::bodyProps(array('style' => array('padding' => '0'))),
        div
        (
            setStyle(array('background' => "url($imageURL) no-repeat", 'background-size' => '100%', 'height' => '460px')),
            $version == 'pms' ? a
            (
                setStyle(array('position' => 'absolute', 'width' => '50px', 'height' => '20px', 'top' => '235px', 'right' => '117px')),
                set::href($lang->admin->bizInfoURL),
                set::target('_blank')
            ) : null
        ),
        set::footerClass('form-actions'),
        set::footerActions(array(array('data-dismiss' => 'modal', 'class' => 'primary', 'text' => $lang->close)))
    );
}
