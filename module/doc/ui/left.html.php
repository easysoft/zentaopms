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

jsVar('release', isset($release) ? $release : 0);
jsVar('versionLang', $lang->build->common);
jsVar('spaceType', $this->session->spaceType);
jsVar('rawModule', $this->app->rawModule);
jsVar('rawMethod', $this->app->rawMethod);
jsVar('objectType', isset($type) ? $type : '');
jsVar('objectID',   isset($objectID) ? $objectID : '');
jsVar('isFirstLoad', isset($isFirstLoad) ? $isFirstLoad: '');
jsVar('canViewFiles', common::hasPriv('doc', 'showfiles'));
jsVar('spaceMethod', $config->doc->spaceMethod);
jsVar('canSortDocCatalog', common::hasPriv('doc', 'sortCatalog'));
jsVar('canSortAPICatalog', common::hasPriv('api', 'sortCatalog'));

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
    )
);

if($app->rawMethod == 'view' and common::hasPriv('doc', 'displaySetting'))
{
    div
    (
        setClass('text-center bottom-btn-tree'),
        a
        (
            setClass('btn btn-sm btn-primary'),
            set('href', inlink('displaySetting')),
            set('data-toggle', 'modal'),
            set('data-size', 'sm'),
            $lang->doc->displaySetting
        )
    );
}
