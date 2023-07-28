<?php
declare(strict_types=1);
/**
 * The deactivate view file of extension module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     extension
 * @link        https://www.zentao.net
 */
namespace zin;

$removeCommands = implode('<br />', $removeCommands);

div
(
    set::class('p-2'),
    div
    (
        set::class('article-h1 text-center mb-2'),
        $title,
    ),
    $removeCommands ?  div
    (
        set::class('font-bold mb-2'),
        $lang->extension->unremovedFiles,
    ) : null,
    $removeCommands ?  div
    (
        set::class('border bg-surface p-2'),
        html($removeCommands)
    ) : null,
    hr(),
    btn
    (
        $lang->extension->viewDeactivated,
        set::url(createLink('extension', 'browse', 'status=deactivated'))
    )
);

render();
