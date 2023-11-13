<?php
declare(strict_types=1);
/**
* The download mobile view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

/**
 * 展示移动端下载页面。
 * Print app download page.
 */
function printDownloadMobile()
{
    global $lang, $config;

    return div
    (
        set('class', 'download-client p-4'),
        div
        (
            div(span($lang->block->zentaoapp->common)),
            div(span(set('class', 'text-sm text-gray'), $lang->block->zentaoapp->desc)),
            div
            (
                set('class','flex py-4'),
                cell
                (
                    set('width', '67%'),
                    div
                    (
                        set('class', 'flex'),
                        div(img(set('src', $config->webRoot . 'theme/default/images/guide/app_index.png')), set('class', 'flex-1 pr-2')),
                        div(img(set('src', $config->webRoot . 'theme/default/images/guide/app_execution.png')), set('class', 'flex-1 pr-2')),
                        div(img(set('src', $config->webRoot . 'theme/default/images/guide/app_statistic.png')), set('class', 'flex-1 pr-2'))
                    )
                ),
                cell
                (
                    set('width', '32%'),
                    div(img(set('src', $config->webRoot . 'theme/default/images/main/mobile_qrcode.png'))),
                    div(span($lang->block->zentaoapp->downloadTip))
                )
            )
        )
    );
}
