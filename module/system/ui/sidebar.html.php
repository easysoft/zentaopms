<?php
declare(strict_types=1);
/**
 * The sidebar view file of system module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     system
 * @link        https://www.zentao.net
 */
namespace zin;

$menuItems = array();
foreach($lang->system->platform->navs as $method => $menu)
{
    $active = $app->rawMethod == strtolower($method) ? 'active' : '';
    if(!common::hasPriv('system', $method)) continue;

    $menuItems[] = li
    (
        setClass('menu-item'),
        a
        (
            setClass($active),
            set::href(createLink('system', $method)),
            setData(array('load' => '', 'no-morph' => 'false')),
            $menu
        )
    );
}

$menuItems ? sidebar
(
    set::showToggle(false),
    div
    (
        setClass('cell p-2.5 bg-white'),
        menu($menuItems)
    )
) : null;
