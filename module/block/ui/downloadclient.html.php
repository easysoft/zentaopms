<?php
declare(strict_types=1);
/**
* The download client view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

/**
 * 展示客户端下载页面。
 * Print client download page.
 */
function printDownloadClient()
{
    global $lang, $config;

    $downloads = array();
    foreach($lang->block->zentaoclient->edition as $edition => $editionName)
    {
        $downloads[] = cell
        (
            set('class', 'py-6 px-2 border'),
            a
            (
                set('class', 'flex leading-6'),
                set('data-toggle', 'modal'),
                set('href', helper::createLink('misc', 'downloadClient', "action=getPackage&os=$edition")),
                span
                (
                    set('class', 'avatar rounded-full flex-none size-sm has-img mr-2'),
                    img(set('src', $config->webRoot . "theme/default/images/guide/edition_{$edition}.png"), set('class', 'inline p-1.5'))
                ),
                span($editionName)
            )
        );
    }

    return div
    (
        set('class', 'download-client p-4'),
        div
        (
            div(span($lang->block->zentaoclient->common)),
            div(span(set('class', 'text-sm text-gray'), $lang->block->zentaoclient->desc)),
            div
            (
                set('class','flex py-4'),
                cell
                (
                    set('width', '25%'),
                    div
                    (
                        set('class', 'flex col'),
                        $downloads
                    )
                ),
                cell
                (
                    set('class', 'ml-4'),
                    set('width', '75%'),
                    img(set('src', $config->webRoot . 'theme/default/images/guide/' . (common::checkNotCN() ? 'client_en.png' : 'client_cn.png')), set('class', 'h-56'))
                )
            )
        )
    );
}
