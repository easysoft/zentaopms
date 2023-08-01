<?php
declare(strict_types=1);
/**
 * The import jira view file of convert module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@easycorp.ltd>
 * @package     convert
 * @link        https://www.zentao.net
 */
namespace zin;

$importUrl = inlink('importJira', "method={$method}&mode=import&type=user&lastID=0&createTable=true");

panel
(
    set::title($lang->convert->jira->importJira),
    h::ul
    (
        setID('importResult'),
        setClass('mx-4'),
        setStyle(array('list-style' => 'disc')),
        li
        (
            setClass('text-danger font-bold importing my-1 hidden'),
            $lang->convert->jira->importing
        )
    ),
    button
    (
        on::click("importJira(event, '{$importUrl}', true)"),
        setClass('btn primary'),
        $lang->convert->jira->start
    )
);

render();
