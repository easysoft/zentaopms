<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'sidebar' . DS . 'v1.php';
class thinkNodeMenu extends wg
{
    private array $modules = array();

    protected static array $defineProps = array(
        'modules: array',
        'activeKey?: int',
        'hover?: bool=true',
        'showAction?: bool=true',
        'checkbox?: bool',
        'preserve?: string|bool',
        'checkOnClick?: bool|string',
        'defaultNestedShow?: bool=true',
        'onCheck?: function'
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    private function buildMenuTree(array $items, int $parentID = 0): array
    {
        if(empty($items)) $items = $this->modules;
        if(empty($items)) return array();

        $activeKey   = $this->prop('activeKey');
        $parentItems = array();
        foreach($items as $setting)
        {
            if(!is_object($setting)) continue;

            $item   = array(
                'key'         => $setting->id,
                'text'        => $setting->title,
                'hint'        => $setting->title,
                'url'         => $setting->url,
                'data-id'     => $setting->id,
                'data-type'   => $setting->type,
                'data-parent' => $setting->parent,
                'selected'    => $setting->id == $activeKey,
                'actions'     => $this->prop('showAction') ? $this->getActions($setting) : null,
                'data-wizard' => $setting->wizard,
            );

            $children = zget($setting, 'children', array());
            if(!empty($children))
            {
                $children = $this->buildMenuTree($children, $setting->id);
                $item['items'] = $children;
            }

            $parentItems[] = $item;
        }
        return $parentItems;
    }

    private function setMenuTreeProps(): void
    {
        global $app, $lang;
        $this->lang    = $lang;
        $this->modules = $this->prop('modules');

        $this->setProp('items', $this->buildMenuTree(array(), 0));
    }

    private function getActions($item): array
    {
        $actions = array();
        $moreBtn = $this->getOperateItems($item);
        $actions[] = array(
            'key'      => 'more',
            'icon'     => 'ellipsis-v',
            'type'     => 'dropdown',
            'caret'    => false,
            'dropdown' => array(
                'placement' => 'bottom-start',
                'items'     => $moreBtn
            )
        );

        return $actions;
    }

    private function getOperateItems($item): array
    {
        $canAddChild        = true;
        $showQuestionOfNode = true;
        if(!empty($item->children))
        {
            foreach($item->children as $child)
            {
                if($canAddChild && $child->type == 'question')    $canAddChild        = false;
                if($showQuestionOfNode && $child->type == 'node') $showQuestionOfNode = false;
            }
        }

        $menus = array();
        if($item->type == 'node') $menus[] = array(
            'key'     => 'addNode',
            'icon'    => 'add-chapter',
            'text'    => $this->lang->thinkwizard->designer->treeDropdown['addSameNode'],
            'onClick' => jsRaw("() => addStep({$item->id}, 'same')")
        );
        if($item->grade != 3 && $item->type == 'node' && $canAddChild) $menus[] = array(
            'key'     => 'addNode',
            'icon'    => 'add-sub-chapter',
            'text'    => $this->lang->thinkwizard->designer->treeDropdown['addChildNode'],
            'onClick' => jsRaw("() => addStep({$item->id}, 'child')")
        );
        $levelType   = $item->type != 'node' ? 'same' : 'child';
        $confirmTips = $this->lang->thinkwizard->step->deleteTips[$item->type];

        $menus = array_merge($menus, array(
            array(
                'key'  => 'editNode',
                'icon' => 'edit',
                'text' => $this->lang->thinkwizard->designer->treeDropdown['edit'],
                'url'  => createLink('thinkwizard', 'design', "wizardID={$item->wizard}&stepID={$item->id}&action=edit")
            ),
            !$item->existNotNode ? array(
                'key'          => 'deleteNode',
                'icon'         => 'trash',
                'text'         => $this->lang->thinkwizard->designer->treeDropdown['delete'],
                'innerClass'   => 'ajax-submit',
                'data-url'     => createLink('thinkstep', 'ajaxDelete', "stepID={$item->id}"),
                'data-confirm' => $confirmTips,
            ) : array(
                'key'            => 'deleteNode',
                'icon'           => 'trash',
                'text'           => $this->lang->thinkwizard->designer->treeDropdown['delete'],
                'innerClass'     => 'text-gray opacity-50',
                'data-toggle'    => 'tooltip',
                'data-title'     => $this->lang->thinkwizard->step->cannotDeleteNode,
                'data-placement' => 'right',
            ),
            array('type' => 'divider'),
            array(
                'key'  => 'addTransition',
                'icon' => 'transition',
                'text' => $this->lang->thinkwizard->designer->treeDropdown['addTransition'],
                'url'  => createLink('thinkwizard', 'design', "wizardID={$item->wizard}&stepID={$item->id}&action=create&addType=transition&levelType=$levelType")
            ),
        ));

        if(($showQuestionOfNode && $item->type == 'node') || $item->hasSameQuestion || $item->type == 'question') $menus = array_merge($menus, array(
            array('type' => 'divider'),
            array(
                'key'  => 'addRadio',
                'icon' => 'radio',
                'text' => $this->lang->thinkwizard->designer->treeDropdown['addRadio'],
                'url'  => createLink('thinkwizard', 'design', "wizardID={$item->wizard}&stepID={$item->id}&action=create&addType=radio&levelType=$levelType")
            ),
            array(
                'key'  => 'addCheckbox',
                'icon' => 'checkbox',
                'text' => $this->lang->thinkwizard->designer->treeDropdown['addCheckbox'],
                'url'  => createLink('thinkwizard', 'design', "wizardID={$item->wizard}&stepID={$item->id}&action=create&addType=checkbox&levelType=$levelType")
            ),
            array(
                'key'  => 'addInput',
                'icon' => 'input',
                'text' => $this->lang->thinkwizard->designer->treeDropdown['addInput'],
                'url'  => createLink('thinkwizard', 'design', "wizardID={$item->wizard}&stepID={$item->id}&action=create&addType=input&levelType=$levelType")
            ),
            array(
                'key'  => 'addTableInput',
                'icon' => 'cell-input',
                'text' => $this->lang->thinkwizard->designer->treeDropdown['addTableInput'],
                'url'  => createLink('thinkwizard', 'design', "wizardID={$item->wizard}&stepID={$item->id}&action=create&addType=tableInput&levelType=$levelType")
            ),
        ));
        return $menus;
    }

    private function buildActions(): node
    {
        return btn
        (
            set::type('ghost'),
            setClass('text-gray absolute top-2 right-3 z-10 toggle-btn'),
            set::icon('fold-all'),
            on::click('toggleQuestionShow'),
        );
    }

    protected function build(): array
    {
        $this->setMenuTreeProps();
        $treeProps   = set($this->props->pick(array('items', 'activeClass', 'activeIcon', 'activeKey', 'onClickItem', 'defaultNestedShow', 'changeActiveKey', 'isDropdownMenu', 'checkbox', 'checkOnClick', 'onCheck')));
        $isInSidebar = $this->parent instanceof sidebar;

        return array
        (
            div
            (
                setClass('think-node-menu rounded bg-white col bg-canvas mt-1 ml-4 pb-3 h-full'),
                zui::tree
                (
                    set::_tag('menu'),
                    set::defaultNestedShow(true),
                    set::hover(true),
                    set::className('bg-canvas col flex-auto scrollbar-hover scrollbar-thin overflow-y-auto overflow-x-hidden'),
                    $treeProps
                ),
                $isInSidebar ? array
                (

                    $this->buildActions(),
                    row
                    (
                        setClass('w-full h-10 justify-end p-1 absolute bottom-0 right-0 pr-4 z-10 bg-canvas'),
                        btn
                        (
                            set::type('ghost'),
                            set::size('sm'),
                            set::icon('menu-arrow-left text-gray'),
                            set::hint($this->lang->collapse),
                            on::click()->do('$this.closest(".sidebar").sidebar("toggle");')
                        )
                    ),
                    h::js("$('#mainContainer').addClass('has-sidebar');$('#mainContainer .sidebar').addClass('relative');")
                ) : null,
            ),
        );
    }
}
