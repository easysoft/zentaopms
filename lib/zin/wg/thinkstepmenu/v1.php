<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'sidebar' . DS . 'v1.php';
class thinkStepMenu extends wg
{
    private array $modules = array();

    protected static array $defineProps = array(
        'modules: array',
        'wizard: object',
        'marketID?: int',
        'from?: string',
        'activeKey?: int',
        'hover?: bool=true',
        'showAction?: bool=true',
        'toggleNonNodeShow?: bool=false',
        'checkbox?: bool',
        'preserve?: string|bool',
        'checkOnClick?: bool|string',
        'defaultNestedShow?: bool=true',
        'hidden?: bool=false',
        'onCheck?: function',
        'sortable?: array',
        'onSort?: function'
    );

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    private function getQuotedText(array $modules, array $quotedTitle, string &$quoteIndex = ''): string
    {
        foreach($modules as $item)
        {
            if(in_array($item->id, $quotedTitle)) $quoteIndex = $quoteIndex . (!empty($quoteIndex) ? '、' : '') . $item->index;
            if(!empty($item->children))
            {
                $childrenResult = $this->getQuotedText($item->children, $quotedTitle, $quoteIndex);
                if($childrenResult) return $childrenResult;
            }
        }
        return $quoteIndex;
    }

    private function buildMenuTree(array $items, int $parentID = 0): array
    {
        global $config;
        if(empty($items)) $items = $this->modules;
        if(empty($items)) return array();

        $activeKey         = $this->prop('activeKey');
        $toggleNonNodeShow = $this->prop('toggleNonNodeShow');
        $hidden            = $this->prop('hidden');
        $sortTree          = $this->prop('sortable') || $this->prop('onSort');
        $wizard            = $this->prop('wizard');
        $hiddenModelType   = in_array($wizard->model, $config->thinkwizard->hiddenMenuModel);
        $parentItems       = array();
        $questionItems     = array();

        if($hiddenModelType) $questionItems = array_values($items);

        foreach($items as $setting)
        {
            if(!is_object($setting)) continue;
            $options     = !empty($setting->options) && is_string($setting->options) ? json_decode($setting->options) : array();
            $quotedTitle = !empty($options->quoteTitle) ? explode(',', $options->quoteTitle) : null;
            $quotedText  = '';
            /* 给引用其他问题的多选题添加标签。Add tags to multiple-choice questions that reference other questions. */
            if($quotedTitle)
            {
                $quotedIndex = $this->getQuotedText($this->modules, $quotedTitle);
                $quotedText  = sprintf($this->lang->thinkstep->treeLabel, $quotedIndex);
            }

            $itemQuestionIndex = 0;

            foreach ($questionItems as $index => $questionItem)
            {
                if($questionItem->id == $setting->id) $itemQuestionIndex = $index;
            }

            if(!empty($itemQuestionIndex) && $hiddenModelType) $preStep = $questionItems[$itemQuestionIndex - 1];

            $canView       = common::hasPriv('thinkstep', 'view');
            $unClickable   = $toggleNonNodeShow && $setting->id != $activeKey && $setting->type != 'node' && json_decode($setting->answer) == null;
            $preStepAnswer = !empty($preStep) && !empty($preStep->answer) ? json_decode($preStep->answer, true) : array();
            $preStepResult = !empty($preStepAnswer) ? $preStepAnswer['result'] : array();
            $hiddenStep    = $unClickable || (!empty($preStep) && empty($preStepResult));
            $item          = array(
                'key'         => $setting->id,
                'text'        => (isset($setting->index) ? ($setting->index . '. ') : '') . $setting->title,
                'subtitle'    => (!empty($quotedText) && !in_array($wizard->model, $config->thinkwizard->hiddenMenuModel)) ? array('html' => "<span class='label size-sm rounded-full warning-pale'>$quotedText</span>") : null,
                'hint'        => $unClickable ? $this->lang->thinkrun->error->unanswered :$setting->title,
                'url'         => $unClickable || !$canView ? '' : $setting->url,
                'data-id'     => $setting->id,
                'data-type'   => $setting->type,
                'data-parent' => $setting->parent,
                'data-order'  => $setting->order,
                'data-level'  => $setting->grade,
                'selected'    => $setting->id == $activeKey,
                'disabled'    => $unClickable,
                'actions'     => $this->prop('showAction') ? $this->getActions($setting) : null,
                'data-wizard' => $setting->wizard,
                'class'       => $hiddenStep && $hidden ? 'hidden' : ''
            );

            if($sortTree) $item['trailingIcon'] = 'move muted cursor-move';
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
        $app->loadLang('thinkstep');

        $untitledLangs = array('transition' => $lang->thinkstep->untitled . $lang->thinkstep->transition, 'question' => $lang->thinkstep->untitled . $lang->thinkstep->question);
        jsVar('untitledLangs', $untitledLangs);

        $this->setProp('items', $this->buildMenuTree(array(), 0));
    }

    private function getActions($item): array
    {
        $canCreate = common::hasPriv('thinkstep', 'create');
        $canEdit   = common::hasPriv('thinkstep', 'edit');
        $canDelete = common::hasPriv('thinkstep', 'delete');
        if(!$canCreate && !$canEdit && !$canDelete) return array();

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
        global $config;
        $wizard             = $this->prop('wizard');
        $canAddChild        = true;
        $showQuestionOfNode = true;
        $hiddenModelType    = in_array($wizard->model, $config->thinkwizard->hiddenMenuModel);
        $previewCanActions  = !$hiddenModelType || ($hiddenModelType && $item->type == 'transition');
        $from               = '';
        if($hiddenModelType)
        {
            $from = strtolower($wizard->type);
            if($wizard->model == 'appeals') $from = 'appeals';
        }

        if(!empty($item->children))
        {
            foreach($item->children as $child)
            {
                if($canAddChild && $child->type == 'question')    $canAddChild        = false;
                if($showQuestionOfNode && $child->type == 'node') $showQuestionOfNode = false;
            }
        }
        if($hiddenModelType) $canAddChild = false;
        $canCreate   = common::hasPriv('thinkstep', 'create');
        $canEdit     = common::hasPriv('thinkstep', 'edit');
        $canDelete   = common::hasPriv('thinkstep', 'delete');
        $canLink     = common::hasPriv('thinkstep', 'link');
        $parentID    = $item->type != 'node' ? $item->parent : $item->id;
        $confirmTips = empty($item->link) ? $this->lang->thinkstep->deleteTips[$item->type] : array('message' => $this->lang->thinkstep->tips->deleteLinkStep, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x', 'size' => 'sm');
        $menus            = array();
        $transitionAction = array();
        if($canCreate)
        {
            if($item->type == 'node' && !$hiddenModelType) $menus[] = array(
                'key'     => 'addNode',
                'icon'    => 'add-chapter',
                'text'    => $this->lang->thinkstep->actions['sameNode'],
                'onClick' => jsRaw("() => addNode({$item->id}, 'same')")
            );
            if($item->grade != 3 && $item->type == 'node' && $canAddChild) $menus[] = array(
                'key'     => 'addNode',
                'icon'    => 'add-sub-chapter',
                'text'    => $this->lang->thinkstep->actions['childNode'],
                'onClick' => jsRaw("() => addNode({$item->id}, 'child')")
            );

            $transitionAction[] = $previewCanActions ? array('type' => 'divider') : null;
            $transitionAction[] = array(
                'key'     => 'transition',
                'icon'    => 'transition',
                'text'    => $this->lang->thinkstep->createStep . $this->lang->thinkstep->actions['transition'],
                'onClick' => jsRaw("() => addQuestion({$item->id}, {$parentID}, 'transition')"),
            );
        }

        $marketID      = $this->prop('marketID');
        $itemHasQuoted = empty($item->hasQuoted) || $item->hasQuoted == 0;
        $deleteItem    = (!$item->existNotNode && $itemHasQuoted) ? array(
            'key'          => 'deleteNode',
            'icon'         => 'trash',
            'text'         => $this->lang->thinkstep->actions['delete'],
            'innerClass'   => 'ajax-submit',
            'data-url'     => createLink('thinkstep', 'delete', "marketID={$marketID}&stepID={$item->id}&from={$from}"),
            'data-confirm' => $confirmTips,
        ) : array(
            'key'        => 'deleteNode',
            'icon'       => 'trash',
            'text'       => $this->lang->thinkstep->actions['delete'],
            'innerClass' => 'text-gray opacity-50',
            'hint'       => $item->existNotNode ? $this->lang->thinkstep->cannotDeleteNode : $this->lang->thinkstep->cannotDeleteQuestion,
        );
        $linkItem = ($canLink && $item->type === 'question') ? array(
            'key'          => 'linkNode',
            'icon'         => 'link',
            'text'         => $this->lang->thinkstep->actions['link'],
            'data-url'     => createLink('thinkstep', 'link', "marketID={$marketID}&stepID={$item->id}"),
            'data-toggle'  => 'modal',
            'data-dismiss' => 'modal',
            'data-size'    => 'sm'
        ) : array(
            'key'          => 'linkNode',
            'icon'         => 'link',
            'text'         => $this->lang->thinkstep->actions['link'],
            'innerClass'   => 'text-gray opacity-50',
            'hint'         => $this->lang->thinkstep->tips->linkBlocks
        );
        $menus = array_merge($menus, array(
            ($canEdit && $previewCanActions) ? array(
                'key'  => 'editNode',
                'icon' => 'edit',
                'text' => $this->lang->thinkstep->actions['edit'],
                'url'  => createLink('thinkstep', 'edit', "marketID={$marketID}&stepID={$item->id}&from={$from}")
            ) : null,
            ($canDelete && $previewCanActions) ? $deleteItem : null,
            in_array($wizard->model, $config->thinkwizard->venn) && $item->type == 'question' && $canLink ? $linkItem : null
        ), $transitionAction);

        $canAddQuestions = ($showQuestionOfNode && $item->type == 'node') || $item->hasSameQuestion || $item->type == 'question';
        if($canCreate && !$hiddenModelType && $canAddQuestions) $menus = array_merge($menus, array(
            array('type' => 'divider'),
            $this->buildMenuItem('radio', 'radio', $this->lang->thinkstep->createStep . $this->lang->thinkstep->actions['radio'], $item, $parentID, 'radio'),
            $this->buildMenuItem('checkbox', 'checkbox', $this->lang->thinkstep->createStep . $this->lang->thinkstep->actions['checkbox'], $item, $parentID, 'checkbox'),
            $this->buildMenuItem('input', 'input', $this->lang->thinkstep->createStep . $this->lang->thinkstep->actions['input'], $item, $parentID, 'input'),
            $this->buildMenuItem('tableInput', 'multi-input', $this->lang->thinkstep->createStep . $this->lang->thinkstep->actions['tableInput'], $item, $parentID, 'tableInput'),
            $this->buildMenuItem('multicolumn', 'multi-input', $this->lang->thinkstep->createStep . $this->lang->thinkstep->actions['multicolumn'], $item, $parentID, 'multicolumn'),
        ));
        return $menus;
    }

    private function buildMenuItem(string $key, string $icon, string $text, object $item, int $parentID): array
    {
        return array(
            'key'     => $key,
            'icon'    => $icon,
            'text'    => $text,
            'onClick' => jsRaw("() => addQuestion({$item->id}, {$parentID}, 'question', '{$key}')"),
        );
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
        $treeProps   = $this->props->pick(array('items', 'activeClass', 'activeIcon', 'activeKey', 'onClickItem', 'defaultNestedShow', 'changeActiveKey', 'isDropdownMenu', 'checkbox', 'checkOnClick', 'onCheck', 'sortable', 'onSort'));
        $isInSidebar = $this->parent instanceof sidebar;
        $treeType    = (!empty($treeProps['onSort']) || !empty($treeProps['sortable'])) ? 'sortableTree' : 'tree';
        list($marketID, $from) = $this->prop(array('marketID', 'from'));

        return array
        (
            div
            (
                setClass('think-node-menu rounded bg-white col bg-canvas pb-3 h-full no-morph'),
                setData(array('marketID' => $marketID, 'from' => $from ?? '')),
                zui::$treeType
                (
                    set::_id('thinkNodeMenu'),
                    setClass('pl-4'),
                    set::_tag('menu'),
                    set::defaultNestedShow(true),
                    set::hover(true),
                    set::toggleIcons(array('expanded' => 'caret-down cursor-pointer', 'collapsed' => 'caret-right cursor-pointer')),
                    set::className('tree-lines bg-canvas col flex-auto scrollbar-hover scrollbar-thin overflow-y-auto overflow-x-hidden'),
                    set($treeProps)
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
                            on::click('hideSidebar')
                        )
                    ),
                    h::js("$('#mainContainer').addClass('has-sidebar');$('#mainContainer .sidebar').addClass('relative');")
                ) : null
            ),
        );
    }
}
