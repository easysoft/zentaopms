<?php
declare(strict_types=1);
/**
 * The space view file of kanban module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     kanban
 * @link        https://www.zentao.net
 */
namespace zin;

featureBar
(
    set::current($browseType),
    set::linkParams("browseType={key}"),
    li
    (
        set::className('nav-item ml-2'),
        checkbox
        (
            setID('showClosed'),
            on::change('toggleOnlyAutoCase'),
            set::checked($this->cookie->showClosed),
            $lang->kanban->showClosed
        )
    )
);
toolbar
(
    !empty($unclosedSpace) and $browseType != 'involved' ? item(set::icon('plus'), set::text($lang->kanban->create), set::className('secondary'), set::url(createLink('kanban', 'create', "spaceID=0&type={$browseType}")), set('data-toggle', 'modal')) : null,
    $browseType != 'involved' ? item(set::icon('plus'), set::text($lang->kanban->createSpace), set::className('primary'), set::url(createLink('kanban', 'createSpace', "type={$browseType}")), set('data-toggle', 'modal')) : null
);

render();
