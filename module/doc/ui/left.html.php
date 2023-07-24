<?php
declare(strict_types=1);
/**
 * The left tree view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

sidebar
(
    docMenu
    (
        set::modules($libTree),
        set::spaceMethod($config->doc->spaceMethod),
        set::libID((int)$libID),
        set::moduleID((int)$moduleID),
        set::linkParams("%s&browseType={$barType}"),
        set::spaceType($spaceType),
        set::objectType(isset($type) ? $type : ''),
        set::objectID(isset($objectID) ? $objectID : 0),
        set::title(isset($objectDropdown['text']) ? $objectDropdown['text'] : ''),
        set::menuLink(isset($objectDropdown['link']) ? $objectDropdown['link'] : ''),
        set::settingLink($app->rawMethod !== 'view' && common::hasPriv('doc', 'displaySetting') ? inlink('displaySetting') : ''),
        set::settingText($lang->doc->displaySetting),
    )
);
