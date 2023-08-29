<?php
declare(strict_types=1);
namespace zin;

class moduleMenu extends wg
{
    private array $modules = array();
    private static array $filterMap = array();

    protected static array $defineProps = array(
        'modules: array',
        'activeKey?: int',
        'settingLink?: string',
        'closeLink: string',
        'showDisplay?: bool=true',
        'allText?: string',
        'title?: string'
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    private function buildMenuTree(array $parentItems, int|string $parentID): array
    {
        $children = $this->getChildModule($parentID);
        if(count($children) === 0) return [];

        $activeKey = $this->prop('activeKey');

        foreach($children as $child)
        {
            $item = array(
                'key' => $child->id,
                'text' => $child->name,
                'url' => $child->url,
                'items' => array(),
                'active' => $child->id == $activeKey,
            );
            $items = $this->buildMenuTree($item['items'], $child->id);
            if(count($items) !== 0) $item['items'] = $items;
            else unset($item['items']);
            $parentItems[] = $item;
        }

        return $parentItems;
    }

    private function getChildModule(int|string $id): array
    {
        return array_filter($this->modules, function($module) use($id)
        {
            /* Remove the rendered module. */
            if(isset(static::$filterMap["$module->parent-$module->id"])) return false;

            if($module->parent != $id) return false;

            static::$filterMap["$module->parent-$module->id"] = true;
            return true;
        });
    }

    private function setMenuTreeProps(): void
    {
        $this->modules = $this->prop('modules');
        $this->setProp('items', $this->buildMenuTree(array(), 0));
    }

    private function getTitle(): string
    {
        if($this->prop('title')) return $this->prop('title');

        global $lang;
        $activeKey = $this->prop('activeKey');

        if(empty($activeKey))
        {
            $allText = $this->prop('allText');
            if(empty($allText)) return $lang->all;
            return $allText;
        }

        foreach($this->modules as $module)
        {
            if($module->id == $activeKey) return $module->name;
        }

        return '';
    }

    private function buildBtns(): wg|null
    {
        $settingLink = $this->prop('settingLink');
        $settingText = $this->prop('settingText');
        $showDisplay = $this->prop('showDisplay');
        if(!$settingLink && !$showDisplay) return null;

        global $app;
        $lang = $app->loadLang('datatable')->datatable;
        $currentModule = $app->rawModule;
        $currentMethod = $app->rawMethod;

        if(!$settingText) $settingText = $lang->moduleSetting;

        $datatableId = $app->moduleName . ucfirst($app->methodName);

        return div
        (
            setClass('col gap-2 py-3 px-7'),
            $settingLink
                ? a
                (
                    setClass('btn'),
                    setStyle('background', '#EEF5FF'),
                    setStyle('box-shadow', 'none'),
                    set('data-app', $app->tab),
                    set::href($settingLink),
                    $settingText
                )
                : null,
            $showDisplay
                ? a
                (
                    setClass('btn white'),
                    set('data-toggle', 'modal'),
                    set::href(helper::createLink('datatable', 'ajaxDisplay', "datatableId=$datatableId&moduleName=$app->moduleName&methodName=$app->methodName&currentModule=$currentModule&currentMethod=$currentMethod")),
                    $lang->displaySetting
                )
                : null,
        );
    }

    private function buildCloseBtn(): wg|null
    {
        $closeLink = $this->prop('closeLink');
        if(!$closeLink) return null;

        $activeKey = $this->prop('activeKey');
        if(empty($activeKey)) return null;

        return a
        (
            set('href', $closeLink),
            icon('close', setStyle('color', 'var(--color-slate-600)'))
        );
    }

    protected function build(): wg
    {
        $this->setMenuTreeProps();
        $title = $this->getTitle();

        return div
        (
            setClass('module-menu rounded shadow-sm bg-white col rounded-sm'),
            h::header
            (
                setClass('h-10 flex items-center pl-4 flex-none gap-3'),
                span
                (
                    setClass('module-title text-lg font-semibold'),
                    $title
                ),
                $this->buildCloseBtn(),
            ),
            h::main
            (
                setClass('col flex-auto overflow-y-auto overflow-x-hidden pl-4 pr-1'),
                zui::tree(set($this->props->pick(array('items', 'activeClass', 'activeIcon', 'activeKey', 'onClickItem', 'defaultNestedShow', 'changeActiveKey', 'isDropdownMenu'))))
            ),
            $this->buildBtns(),
        );
    }
}
