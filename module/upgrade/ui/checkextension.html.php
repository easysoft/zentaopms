<?php
declare(strict_types=1);
/**
 * The checkextension view file of upgrade module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     upgrade
 * @link        https://www.zentao.net
 */
namespace zin;

set::zui(true);

$index      = 1;
$extensions = array();
foreach($extensionsName as $extension => $extensionName)
{
    $extensions[] = div
    (
        set::class('p-1'),
        $index . '.',
        span(set::class('pl-1'), $extensionName),
        !empty($removeCommands[$extension]) ? div
        (
            set::class('border p-4 mb-4'),
            set::style(array('background-color' => 'var(--color-gray-100)')),
            div
            (
                div($this->lang->extension->unremovedFiles),
                div(set::class('pt-2'), html(join('<br />', $removeCommands[$extension])))
            )
        ) : null
    );
    $index ++;
}

div
(
    set::id('main'),
    div
    (
        set::id('mainContent'),
        set::class('bg-white p-4'),
        set::style(array('margin' => '50px auto 0', 'width' => '800px')),
        div
        (
            set::class('article-h1 mb-4'),
            $title
        ),
        div
        (
            div(span(set::class('text-base text-black'), $this->lang->upgrade->forbiddenExt)),
            div(set::class('p-2'), $extensions)
        ),
        div(set::class('center'), a(set::href(inlink('selectVersion')), set::class('btn btn-wide primary'), $this->lang->upgrade->continue)),
    )
);

render('pagebase');

