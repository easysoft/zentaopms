<?php
declare(strict_types=1);
/**
 * The browse view file of extension module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     extension
 * @link        https://www.zentao.net
 */
namespace zin;

if(!empty($error))
{
    div
    (
        p
        (
            set::class('text-danger mb-3'),
            html($error),
        ),
        btn
        (
            on::click('loadCurrentPage'),
            set::type('primary'),
            $lang->extension->refreshPage,
        )
    );
}
else
{
    featurebar
    (
        set::current($tab),
        set::linkParams("status={key}")
    );
    
    toolbar
    (
        hasPriv('extension', 'upload') ? item(set(array
        (
            'icon' => 'cog',
            'text' => $lang->extension->upload,
            'class' => 'ghost',
            'url' => createLink('extension', 'upload'),
            'data-toggle' => 'modal'
        ))) : null,
        hasPriv('extension', 'obtain') ? item(set(array
        (
            'icon'  => 'download-alt',
            'text'  => $lang->extension->obtain,
            'class' => 'primary',
            'url'   => createLink('extension', 'obtain')
        ))) : null,
    );
}

render();
