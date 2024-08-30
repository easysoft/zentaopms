<?php
declare(strict_types=1);
/**
* The vision switch view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

/**
 * 展示界面切换页面。
 * Print vision switch.
 */
function printVisionSwitch()
{
    global $lang, $config;

    $cells = array();
    foreach($lang->block->visions as $vision)
    {
        $image = $config->webRoot . "theme/default/images/guide/vision_{$vision->key}.png";
        if(!common::checkNotCN() and $vision->key == 'rnd') $image = $config->webRoot . "theme/default/images/guide/vision_{$vision->key}_cn.png";
        if(common::checkNotCN() and $vision->key == 'rnd')  $image = $config->webRoot . "theme/default/images/guide/vision_{$vision->key}_en.png";
        $cells[] = cell
        (
            set('width', '50%'),
            set('class', 'flex-1 block mr-4 ' . ($config->vision == $vision->key ? 'active' : 'state')),
            div
            (
                set('class', 'w-full vision-block'),
                set('data-vision', $vision->key),
                img
                (
                    set('class', 'p-2'),
                    set('src', $image),
                    set('style', array('height' => count($lang->block->visions) > 2 ? 'auto' : '180px'))
                ),
                div
                (
                    set('class', 'px-4 pb-2'),
                    div(set('class', 'pb-2'), span(set('class', 'font-bold'), $vision->title)),
                    span(set('class', 'text-sm text-gray'), $vision->text)
                )
            )
        );
    }

    return div
    (
        set('class', 'vision-switch p-4'),
        div
        (
            span(set('class', 'py-2'), $lang->block->visionTitle),
            div
            (
                set('class','flex mt-2'),
                $cells
            )
        )
    );
}
