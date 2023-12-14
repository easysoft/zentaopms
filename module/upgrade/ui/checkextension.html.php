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
        set::className('p-1'),
        $index . '.',
        span(set::className('pl-1'), $extensionName),
        !empty($removeCommands[$extension]) ? div
        (
            set::className('border p-4 mb-4'),
            set::style(array('background-color' => 'var(--color-gray-100)')),
            div
            (
                div($this->lang->extension->unremovedFiles),
                div(set::className('pt-2'), html(join('<br />', $removeCommands[$extension])))
            )
        ) : null
    );
    $index ++;
}

div
(
    setID('main'),
    div
    (
        setID('mainContent'),
        set::className('bg-white p-4'),
        set::style(array('margin' => '50px auto 0', 'width' => '800px')),
        div
        (
            set::className('text-lg font-bold mb-4'),
            $title
        ),
        div
        (
            div(span(set::className('text-base text-black'), $this->lang->upgrade->forbiddenExt)),
            div(set::className('p-2'), $extensions)
        ),
        div(set::className('center'), a(set::href(inlink('selectVersion')), set::className('btn btn-wide primary'), $this->lang->upgrade->continue))
    )
);

render('pagebase');
