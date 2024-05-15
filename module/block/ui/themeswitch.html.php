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
            set::width('172px'),
            setClass("p-1 pb-0 rounded-md block theme-{$themeKey}", $app->cookie->theme == $themeKey ? 'active' : ''),
            div
            (
                div
                (
                    setClass('w-full theme-block state'),
                    set('data-theme', $themeKey),
                    img
                    (
                        set('src', $image)
                    ),
                    div
                    (
                        setClass("px-2 py-1 text-center text-white"),
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
        setClass('theme-switch'),
        div
        (
            div
            (
                setClass('flex flex-wrap gap-7 px-10 pt-6'),
                $themes
            )
        )
    );
}
