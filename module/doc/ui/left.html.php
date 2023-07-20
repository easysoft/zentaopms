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

$docMenuTree = null;
if($spaceType != 'project')
{
    $docMenuTree = docMenu
        (
            set::modules($libTree),
            set::spaceMethod($config->doc->spaceMethod),
            set::libID((int)$libID),
            set::moduleID((int)$moduleID),
            set::linkParams("%s&browseType={$barType}"),
            set::spaceType($spaceType),
            set::objectType(isset($type) ? $type : ''),
            set::objectID(isset($objectID) ? $objectID : 0),
        );
}
else
{
    foreach($libTree as $treeType => $treeData)
    {
        if($treeType == 'annex') $treeData = array($treeData);
        $docMenuTree[] = docMenu
            (
                set::modules($treeData),
                set::spaceMethod($config->doc->spaceMethod),
                set::libID((int)$libID),
                set::moduleID((int)$moduleID),
                set::linkParams("%s&browseType={$barType}"),
                set::spaceType($spaceType),
                set::objectType(isset($type) ? $type : ''),
                set::allText($config->doc->treeNameList[$treeType]),
            );
    }
}

sidebar
(
    $docMenuTree
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

$canAddCatalog['doc'] = common::hasPriv('doc', 'addCatalog');
$canAddCatalog['api'] = common::hasPriv('api', 'addCatalog');

$canEditCatalog['doc'] = common::hasPriv('doc', 'editCatalog');
$canEditCatalog['api'] = common::hasPriv('api', 'editCatalog');

$canDeleteCatalog['doc'] = common::hasPriv('doc', 'deleteCatalog');
$canDeleteCatalog['api'] = common::hasPriv('api', 'deleteCatalog');

$hasModulePriv['doc'] = $canAddCatalog['doc'] || $canEditCatalog['doc'] || $canDeleteCatalog['doc'];
$hasModulePriv['api'] = $canAddCatalog['api'] || $canEditCatalog['api'] || $canDeleteCatalog['api'];

$hasLibPriv['doc'] = $canAddCatalog['doc'] || common::hasPriv('doc', 'editLib') || common::hasPriv('doc', 'deleteLib');
$hasLibPriv['api'] = $canAddCatalog['api'] || common::hasPriv('api', 'editLib') || common::hasPriv('api', 'deleteLib');

jsVar('canAddCatalog',    $canAddCatalog);
jsVar('canEditCatalog',   $canEditCatalog);
jsVar('canDeleteCatalog', $canDeleteCatalog);
jsVar('hasModulePriv',    $hasModulePriv);
jsVar('hasLibPriv',       $hasLibPriv);

$operateList = array();
foreach(array('doc', 'api') as $module)
{
    $operateList[] = div
    (
        ul
        (
            setClass($module . 'LibDorpdown'),
            $canAddCatalog[$module] ? li
            (
                set(array(
                    'data-type'         => 'add',
                    'data-libid'        => '%libID%',
                    'data-method'       => 'addCataLib',
                    'data-moduleid'     => '%moduleID%',
                    'data-has-children' => '%hasChildren%',
                )),
                a
                (
                    icon('add-directory'),
                    $lang->doc->libDropdown['addModule']
                )
            ) : null,
            common::hasPriv($module, 'editLib') ? li
            (
                set('data-method', 'editLib'),
                a
                (
                    set::link(inlink('editLib', 'libID=%libID%')),
                    set('data-toggle', 'modal'),
                    set('data-type', 'iframe'),
                    icon('edit'),
                    $lang->doc->libDropdown['editLib']
                )
            ) : null,
            common::hasPriv($module, 'deleteLib') ? li
            (
                set('data-method', 'deleteLib'),
                a
                (
                    setClass('ajaxSubmit'),
                    set('data-confirm', $lang->doc->libDropdown['deleteLibConfirm']),
                    set::link(inlink('deleteLib', 'libID=%libID%')),
                    icon('trash'),
                    $lang->doc->libDropdown['deleteLib']
                )
            ) : null,
        ),
        ul
        (
            setClass($module . 'ModuleDorpdown'),
            $canAddCatalog[$module] ? li
            (
                set(array(
                    'data-type'   => 'addCataBro',
                    'data-method' => 'addCataLib',
                    'data-id'     => '%moduleID%',
                )),
                a
                (
                    icon('add-directory'),
                    $lang->doc->libDropdown['addSameModule']
                )
            ) : null,
            $canAddCatalog[$module] ? li
            (
                set(array(
                    'data-type'         => 'add',
                    'data-method'       => 'addCataChild',
                    'data-id'           => '%moduleID%',
                    'data-has-children' => '%hasChildren%',
                )),
                a
                (
                    icon('add-directory'),
                    $lang->doc->libDropdown['addSubModule']
                )
            ) : null,
            $canEditCatalog[$module] ? li
            (
                setClass('edit-module'),
                set('data-method', 'editCata'),
                a
                (
                    set('data-link', createLink($module, 'editCatalog', "moduleID=%moduleID%&type=" . ($app->rawModule == 'api' ? 'api' : 'doc'))),
                    set('data-toggle', 'modal'),
                    icon('edit'),
                    $lang->doc->libDropdown['editModule']
                )
            ) : null,
            $canDeleteCatalog[$module] ? li
            (
                set('data-method', 'deleteCata'),
                a
                (
                    setClass('ajaxSubmit'),
                    set('data-confirm', $lang->doc->libDropdown['deleteLibConfirm']),
                    set::link(createLink($module, 'deleteCatalog', 'rootID=%libID%&moduleID=%moduleID%')),
                    icon('trash'),
                    $lang->doc->libDropdown['delModule']
                )
            ) : null,
        )
    );
}

div
(
    setID('dropDownData'),
    setClass('hidden'),
    $operateList
);
