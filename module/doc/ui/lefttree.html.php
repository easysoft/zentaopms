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

h::css
(
    <<<CSS
    [data-type="mine"]{display: none;}
    [data-type="mine"] + menu{--list-nested-indent: 0px !important; border-bottom-width: 1px; padding-bottom: 8px; margin-bottom: 8px; max-height: calc(100vh - 250px); overflow-y:auto; padding-left:5px; padding-right: 5px;}
    [data-type="mine"] + menu:before{border-left-width: 0px !important;}
    CSS
);

$canSort = hasPriv('doc', 'sortCatalog');

if($app->rawModule == 'doc' && $app->rawMethod == 'view')
{
    $settingLink = hasPriv('doc', 'displaySetting') ? inlink('displaySetting') : '';
    $settingText = $lang->doc->displaySetting;
}
elseif(hasPriv('doc', 'editLib') && $spaceType == 'custom')
{
    $spaceID     = $objectID;
    $settingLink = inlink('editLib', "libID=$spaceID");
    $settingText = $lang->doclib->editSpace;
}
else
{
    $settingLink = '';
    $settingText = '';
}

sidebar
(
    set::width(200),
    set::minWidth(200),
    docMenu
    (
        set::modules($libTree),
        set::spaceMethod($config->doc->spaceMethod),
        set::libID((int)$libID),
        set::release(isset($release) ? $release : 0),
        set::moduleID((int)$moduleID),
        set::linkParams($linkParams),
        set::spaceType($spaceType),
        set::objectType(isset($type) ? ($type == 'execution' ? 'project' : $type) : ''),
        set::objectID(isset($objectID) ? $objectID : 0),
        set::sortable(array('handle' => '.icon-move')),
        set::onSort(jsRaw('window.updateOrder')),
        set::canSortTo(jsRaw('window.canSortTo')),
        set::title(!empty($objectDropdown['text']) ? $objectDropdown['text'] : $objectTitle),
        set::menuLink(isset($objectDropdown['link']) ? $objectDropdown['link'] : ''),
        set::settingLink($settingLink),
        set::settingText($settingText),
        set::defaultNestedShow(true)
    )
);

if($spaceType == 'mine')
{
    h::js
    (
        <<<JAVASCRIPT
        waitDom('.doc-menu .listitem[data-type="mine"]', function()
        {
            let \$item = \$(this).parent();
            if(\$item.hasClass('is-nested-hide')) \$item.trigger('click');
        })
        JAVASCRIPT
    );
}
