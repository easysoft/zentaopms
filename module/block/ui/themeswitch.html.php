<?php
declare(strict_types=1);
/**
* The theme switch view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

/**
 * 展示主题切换页面。
 * Print theme switch.
 */
function printThemeSwitch()
{
    global $lang, $config, $app;

    $themes = array();
    foreach($lang->block->themes as $themeKey => $themeName)
    {
        $image = $config->webRoot . "theme/default/images/guide/theme_{$themeKey}.png";
        $themes[] = cell
        (
            set('width', '25%'),
            set('class', 'pt-2 px-2'),
            div
            (
                set('class', 'block ' . ($app->cookie->theme == $themeKey ? 'active' : '')),
                div
                (
                    set('class', 'w-full theme-block state'),
                    set('data-theme', $themeKey),
                    img
                    (
                        set('class', 'p-2'),
                        set('src', $image)
                    ),
                    div
                    (
                        set('class', "px-2 pb-1 text-center theme-{$themeKey}"),
                        span
                        (
                            icon('check-circle mr-2 hidden'),
                            $themeName
                        )
                    )
                )
            )
        );
    }

    return div
    (
        set('class', 'theme-switch'),
        div
        (
            div
            (
                set('class','flex flex-wrap'),
                $themes
            )
        )
    );
}
