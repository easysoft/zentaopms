<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'sidebar' . DS . 'v1.php';
class docMenu extends wg
{
    private array $modules = array();

    private array $selectedKey = array();

    private array $mineTypes = array('mine', 'view', 'collect', 'createdby', 'editedby');

    protected static array $defineProps = array(
        'modules: array',
        'activeKey?: int',
        'settingLink?: string',
        'menuLink: string',
        'title?: string',
        'preserve?: string|bool',
        'linkParams?: string="%s"',
        'libID?: int=0',
        'moduleID?: int=0',
        'spaceType?: string',
        'objectType?: string',
        'objectID?: int=0',
        'hover?: bool=true',
        'sortable?: array',
        'onSort?: function',
        'canSortTo?: function'
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    private function buildLink($item, $releaseID = 0): string
    {
        global $app;
        $url = zget($item, 'url', '');
        if(!empty($url)) return $url;
        $objectType = $this->objectType;
        if($objectType == 'project' && $item->type == 'execution') return '';
        if(in_array($item->type, array('apiLib', 'docLib')))
        {
            $this->libID    = $item->id;
            $this->moduleID = 0;
        }
        if($item->type == 'module') $this->moduleID = $item->id;

        $linkParams = sprintf($this->linkParams, "libID={$this->libID}&moduleID={$this->moduleID}");
        if(in_array($this->spaceType, array('product', 'project', 'custom'))) $linkParams = "objectID={$this->objectID}&{$linkParams}";

        $moduleName = $this->spaceType == 'api' ? 'api' : 'doc';
        $methodName = '';
        if($this->spaceType == 'api')
        {
            $methodName = 'index';
        }
        elseif($item->type == 'annex')
        {
            $methodName = 'showFiles';
            $linkParams = "type={$item->objectType}&objectID={$item->objectID}";
        }
        elseif(in_array($item->type, array('text', 'word','ppt', 'excel', 'attachment')))
        {
            $methodName = 'view';
            $linkParams = "docID={$item->id}";
        }
        elseif($objectType == 'execution' && $app->tab == 'execution')
        {
            $moduleName = 'execution';
            $methodName = 'doc';
        }
        else
        {
            $methodName = empty($this->spaceMethod[$objectType]) ? 'teamSpace' : $this->spaceMethod[$objectType];
            if(in_array($objectType, $this->mineTypes))
            {
                $moduleID = $item->id;
                if(in_array($item->type, array('docLib', 'annex', 'api', 'execution'))) $moduleID = 0;

                $type       = in_array(strtolower($item->type), $this->mineTypes) ? strtolower($item->type) : 'mine';
                $linkParams = "type={$type}&libID={$this->libID}&moduleID={$moduleID}";
            }
            if($item->type == 'module' && !empty($item->object) && $item->object == 'api')
            {
                $linkParams = str_replace(array('browseType=&', 'param=0'), array('browseType=byrelease&', "param={$this->release}"), $linkParams);
            }
        }

        if($releaseID)
        {
            if($this->currentModule == 'doc')
            {
                $linkParams = str_replace(array('browseType=&', 'param=0'), array('browseType=byrelease&', "param={$releaseID}"), $linkParams);
            }
            else
            {
                $moduleName = 'api';
                $methodName = 'index';
                $linkParams = "libID={$this->libID}&moduleID=0&apiID=0&version=0&release={$releaseID}";
            }
        }
        return helper::createLink($moduleName, $methodName, $linkParams);
    }

    private function buildMenuTree(array $items, int $parentID = 0): array
    {
        if(empty($items)) $items = $this->modules;
        if(empty($items)) return array();

        $activeKey   = $this->prop('activeKey');
        $sortTree    = $this->prop('sortable') || $this->prop('onSort');
        $parentItems = array();
        foreach($items as $setting)
        {
            if(!is_object($setting)) continue;

            $setting->parentID = $parentID;

            $itemID = 0;
            if(!in_array(strtolower($setting->type), $this->mineTypes)) $itemID = $setting->id ? $setting->id : $parentID;

            $moduleName = ($setting->type == 'apiLib' || (isset($setting->objectType) && $setting->objectType == 'api')) ? 'api' : 'doc';
            $selected   = isset($setting->active) ? $setting->active : ($itemID && $itemID == $activeKey);
            if($selected) $this->selectedKey[] = $itemID;

            $item = array(
                'key'             => $itemID,
                'text'            => $setting->name,
                'hint'            => $setting->name,
                'icon'            => $this->getIcon($setting),
                'url'             => $this->buildLink($setting),
                'titleAttrs'      => array('data-app' => $this->tab, 'class' => 'item-title w-full'),
                'data-id'         => $itemID,
                'data-lib'        => in_array($setting->type, array('docLib', 'apiLib')) ? $itemID : zget($setting, 'libID', ''),
                'data-type'       => $setting->type,
                'data-objectType' => isset($setting->objectType) ? $setting->objectType : '',
                'data-parent'     => $setting->parentID,
                'data-module'     => $moduleName,
                'selected'        => $selected,
                'actions'         => $this->getActions($setting)
            );

            if($sortTree && ($setting->type == 'module' && hasPriv('doc', 'sortCatalog'))) $item['trailingIcon'] = 'move muted cursor-move';
            if($sortTree && (in_array($setting->type, array('docLib', 'apiLib')) && hasPriv('doc', 'sortDocLib'))) $item['trailingIcon'] = 'move muted cursor-move';

            $children = zget($setting, 'children', array());
            if(!empty($children))
            {
                $children = $this->buildMenuTree($children, $itemID);
                $item['items'] = $children;
            }

            $parentItems[] = $item;
        }
        return $parentItems;
    }

    private function setMenuTreeProps(): void
    {
        global $app, $lang;
        $this->lang          = $lang;
        $this->tab           = $app->tab;
        $this->rawModule     = $app->rawModule;
        $this->rawMethod     = $app->rawMethod;
        $this->currentModule = $app->moduleName;

        $this->release     = $this->prop('release', 0);
        $this->libID       = $this->prop('libID');
        $this->moduleID    = $this->prop('moduleID');
        $this->modules     = $this->prop('modules');
        $this->linkParams  = $this->prop('linkParams', '%s');
        $this->spaceType   = $this->prop('spaceType', '');
        $this->objectType  = $this->prop('objectType', '');
        $this->objectID    = $this->prop('objectID', 0);
        $this->spaceMethod = $this->prop('spaceMethod');

        if($this->rawModule == 'api' && $this->rawMethod == 'view') $this->spaceType = 'api';
        if(empty($this->modules['project']))
        {
            $this->setProp('items', $this->buildMenuTree(array(), $this->libID));
        }
        else
        {
            $items = array();
            $index = 0;
            foreach($this->modules as $treeType => $modules)
            {
                if($treeType == 'project')
                {
                    $treeTitle = $lang->projectCommon;
                    $treeIcon  = 'project';
                }
                elseif($treeType == 'execution')
                {
                    $treeTitle = $lang->execution->common;
                    $treeIcon  = 'run';
                }

                if($treeType != 'annex')
                {
                    $items[] = array(
                        'text'  => $treeTitle,
                        'icon'  => $treeIcon,
                        'class' => 'project-tree-title ' . ($index > 0 ? 'border-t mt-2 pt-2' : ''),
                        'innerClass' => 'items-center'
                    );
                }

                $items = array_merge($items, $this->buildMenuTree($modules, $this->libID));
                $index ++;
            }
            $this->setProp('items', $items);
        }
    }

    private function getActions($item): array|null
    {
        $versionBtn = array();
        if(isset($item->versions) && $item->versions)
        {
            global $lang;
            $versionTitle = $lang->build->common;
            $versionBtn = array(
                'key'       => 'version',
                'text'      => $versionTitle,
                'hint'      => $versionTitle,
                'className' => 'versions-list',
                'type'      => 'dropdown',
                'dropdown'  => array(
                    'placement' => 'bottom-end',
                    'items'     => array()
                )
            );

            foreach($item->versions as $version)
            {
                if($version->id == $this->release)
                {
                    $versionBtn['text'] = $version->version;
                    $versionBtn['hint'] = $version->version;
                }

                $versionBtn['dropdown']['items'][] = array(
                    'text'   => $version->version,
                    'hint'   => $version->version,
                    'url'    => $this->buildLink($item, $version->id),
                    'active' => $version->id == $this->release
                );
            }
        }

        $moreBtn = array();
        if(!isset($item->hasAction) || $item->hasAction || in_array($item->type, array('mine', 'view', 'collect', 'createdBy', 'editedBy')))
        {
            $actions = $this->getOperateItems($item);
            if($actions)
            {
                $moreBtn = array(
                    'key'      => 'more',
                    'icon'     => 'ellipsis-v',
                    'type'     => 'dropdown',
                    'caret'    => false,
                    'dropdown' => array(
                        'placement' => 'bottom-end',
                        'items'     => $actions
                    )
                );
            }
        }

        $actions = array();
        if($versionBtn) $actions[] = $versionBtn;
        if($moreBtn)    $actions[] = $moreBtn;
        return $actions ? $actions : null;
    }

    private function getOperateItems($item): array
    {
        $menus = array();
        if(in_array($item->type, array('docLib', 'apiLib')))
        {
            $itemID     = $item->id ? $item->id : $item->parentID;
            $moduleName = $item->type == 'docLib' ? 'doc' : 'api';
            if(hasPriv($moduleName, 'addCatalog') && !(isset($item->objectType) && $item->objectType == 'custom' && $item->parent == 0))
            {
                $menus['adddirectory'] = array(
                    'key'     => 'adddirectory',
                    'icon'    => 'add-directory',
                    'text'    => $this->lang->doc->libDropdown['addModule'],
                    'onClick' => jsRaw("() => addModule({$itemID}, 'child')")
                );
            }

            if($item->type == 'docLib' && ($item->objectType == 'mine' || ($item->objectType == 'custom' && $item->parent > 0)) && hasPriv($moduleName, 'moveLib'))
            {
                $menus['movelib'] = array(
                    'key'         => 'movelib',
                    'icon'        => 'folder-move',
                    'text'        => $this->lang->doc->moveTo,
                    'data-toggle' => 'modal',
                    'data-size'   => 'sm',
                    'data-url'    => createlink($moduleName, 'moveLib', "libID={$itemID}")
                );
            }

            if(hasPriv($moduleName, 'editLib'))
            {
                $menus['editlib'] = array(
                    'key'         => 'editlib',
                    'icon'        => 'edit',
                    'text'        => (isset($item->objectType) && $item->objectType == 'custom' && $item->parent == 0) ? $this->lang->doc->libDropdown['editSpace'] : $this->lang->doc->libDropdown['editLib'],
                    'data-toggle' => 'modal',
                    'data-url'    => createlink($moduleName, 'editLib', "libID={$itemID}")
                );
            }

            if(hasPriv($moduleName, 'deleteLib'))
            {
                $menus['dellib'] = array(
                    'key'          => 'dellib',
                    'icon'         => 'trash',
                    'text'         => (isset($item->objectType) && $item->objectType == 'custom' && $item->parent == 0) ? $this->lang->doc->libDropdown['deleteSpace'] : $this->lang->doc->libDropdown['deleteLib'],
                    'innerClass'   => 'ajax-submit',
                    'data-url'     => createLink($moduleName, 'deleteLib', "libID={$itemID}"),
                    'data-confirm' => (isset($item->objectType) && $item->objectType == 'custom' && $item->parent == 0) ? $this->lang->doc->confirmDeleteSpace : $this->lang->{$moduleName}->confirmDeleteLib
                );
            }
            if($item->objectType == 'mine' && $item->main == 1) unset($menus['dellib'], $menus['movelib']);
        }
        elseif($item->type == 'module')
        {
            $moduleName = $item->objectType == 'api' ? 'api' : 'doc';
            if(hasPriv($moduleName, 'addCatalog'))
            {
                $menus['adddirectory'] = array(
                    'key'     => 'adddirectory',
                    'icon'    => 'add-directory',
                    'text'    => $this->lang->doc->libDropdown['addSameModule'],
                    'onClick' => jsRaw("() => addModule({$item->id}, 'same')")
                );
                $menus['addsubdirectory'] = array(
                    'key'     => 'addsubdirectory',
                    'icon'    => 'add-directory',
                    'text'    => $this->lang->doc->libDropdown['addSubModule'],
                    'onClick' => jsRaw("() => addModule({$item->id}, 'child')")
                );
            }

            if(hasPriv($moduleName, 'editCatalog'))
            {
                $menus['editmodule'] = array(
                    'key'  => 'editmodule',
                    'icon' => 'edit',
                    'text' => $this->lang->doc->libDropdown['editModule'],
                    'link' => '',
                    'data-toggle' => 'modal',
                    'data-url'    => createlink($moduleName, 'editCatalog', "moduleID={$item->id}&type=" . ($this->rawModule == 'api' ? 'api' : 'doc'))
                );
            }

            if(hasPriv($moduleName, 'deleteCatalog'))
            {
                $menus['delmodule'] = array(
                    'key'          => 'delmodule',
                    'icon'         => 'trash',
                    'text'         => $this->lang->doc->libDropdown['delModule'],
                    'innerClass'   => 'ajax-submit',
                    'data-url'     => createLink($moduleName, 'deleteCatalog', "moduleID={$item->id}"),
                    'data-confirm' => $this->lang->doc->confirmDeleteModule
                );
            }
        }

        return array_values($menus);
    }

    private function getIcon($item): string
    {
        if(isset($item->objectType) && $item->objectType == 'custom' && $item->parent == 0) return ''; // 团队空间下的空间不显示图标

        $type = $item->type;
        if($type == 'apiLib')    return 'interface-lib';
        if($type == 'docLib')    return 'wiki-lib';
        if($type == 'annex')     return 'annex-lib';
        if($type == 'execution') return 'execution';
        if($type == 'text')      return 'file-text';
        if($type == 'word')      return 'file-word';
        if($type == 'ppt')       return 'file-powerpoint';
        if($type == 'excel')     return 'file-excel';
        return '';
    }

    private function getTitle(): ?string
    {
        global $lang;
        $activeKey = $this->prop('activeKey');

        if(empty($activeKey)) return $this->prop('title');

        foreach($this->modules as $module)
        {
            if($module->id == $activeKey) return $module->name;
        }

        return '';
    }

    private function buildBtns(): node|null
    {
        $settingLink = $this->prop('settingLink');
        $settingText = $this->prop('settingText');
        if(!$settingLink) return null;

        global $app;
        $lang = $app->loadLang('datatable')->datatable;
        if(!$settingText) $settingText = $lang->moduleSetting;

        return div
        (
            setClass('col gap-2 py-3 px-7'),
            $settingLink ? a
            (
                setClass('btn'),
                setStyle('background', 'rgb(var(--color-primary-50-rgb))'),
                setStyle('box-shadow', 'none'),
                set('data-app', $app->tab),
                set('data-size', array('width' => '600px', 'height' => '210px')),
                set('data-toggle', 'modal'),
                set('data-class-name', 'doc-setting-modal'),
                set::href($settingLink),
                span
                (
                    setClass('text-primary'),
                    $settingText
                )
            ) : null
        );
    }

    protected function build(): array
    {
        global $app;

        $this->setMenuTreeProps();
        $title     = $this->getTitle();
        $menuLink  = $this->prop('menuLink', '');
        $objectID  = $this->prop('objectID', 0);
        $treeProps = $this->props->pick(array('items', 'activeClass', 'activeIcon', 'activeKey', 'onClickItem', 'defaultNestedShow', 'changeActiveKey', 'isDropdownMenu', 'hover', 'sortable', 'onSort', 'canSortTo'));
        $preserve  = $this->prop('preserve', $app->rawModule . '-' . $app->rawMethod);

        $isInSidebar = $this->parent instanceof sidebar;

        $header = h::header
        (
            setClass('doc-menu-header h-10 flex items-center pl-4 flex-none gap-3', $isInSidebar ? 'is-fixed rounded rounded-r-none canvas' : ''),
            span
            (
                setClass('module-title text-lg font-semibold clip'),
                $title
            )
        );

        $treeType = (!empty($treeProps['onSort']) || !empty($treeProps['sortable'])) ? 'sortableTree' : 'tree';
        return array
        (
            $isInSidebar && !$menuLink ? $header : null,
            div
            (
                $menuLink ? dropmenu
                (
                    set::id('docDropmenu'),
                    set::menuID('docDropmenuMenu'),
                    set::objectID($objectID),
                    set::text($title),
                    set::url($menuLink)
                ) : null,
                div
                (
                    setClass('doc-menu rounded shadow ring bg-white col'),
                    h::main
                    (
                        setClass($menuLink ? 'pt-3' : ''),
                        setClass('col flex-auto overflow-y-auto overflow-x-hidden pl-2 pr-1 py-1 scrollbar-hover'),
                        setStyle('--menu-selected-bg', 'none'),
                        zui::$treeType
                        (
                            set::_tag('menu'),
                            set::lines(),
                            set::preserve($preserve),
                            set::selectedKey(implode(':', $this->selectedKey)),
                            set::afterRender(jsRaw('function(isFirst){return isFirst && this.toggle(this.props.selectedKey)}')),
                            set($treeProps)
                        )
                    ),
                    $this->buildBtns()
                ),
                $isInSidebar ? h::js("$('#mainContainer').addClass('has-doc-menu-header')") : null
            )
        );
    }
}
