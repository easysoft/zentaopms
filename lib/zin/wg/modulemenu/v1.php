<?php
declare(strict_types=1);
namespace zin;

class moduleMenu extends wg
{
    private $modules = array();

    protected static $defineProps = array(
        'modules:array',
        'activeKey:int',
        'closeLink:string'
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    private function buildMenuTree(array $parentItems, int|string $parentID): array
    {
        $children = $this->getChildModule($parentID);
        if(count($children) === 0) return [];

        foreach($children as $child)
        {
            $item = array('key' => $child->id, 'text' => $child->name, 'url' => $child->url, 'items' => []);
            $items = $this->buildMenuTree($item['items'], $child->id);
            if(count($items) !== 0) $item['items'] = $items;
            else unset($item['items']);
            $parentItems[] = $item;
        }
        return $parentItems;
    }

    private function getChildModule(int|string $id): array
    {
        return array_filter($this->modules, fn($module) => $module->parent == $id);
    }

    private function setMenuTreeProps(): void
    {
        $this->modules = $this->prop('modules');
        $this->setProp('items', $this->buildMenuTree([], 0));
        $this->setDefaultProps(array('activeClass' => 'active'));
    }

    private function getTitle($activeKey): string
    {
        foreach($this->modules as $module)
        {
            if($module->id == $activeKey) return $module->name;
        }

        return '';
    }

    protected function build(): wg
    {
        global $app;
        $lang = $app->loadLang('datatable')->datatable;
        $this->setMenuTreeProps();
        $activeKey = $this->prop('activeKey');
        $title = $this->getTitle($activeKey);
        $closeBtn = null;
        if(!empty($activeKey))
        {
            $closeBtn = a
            (
                set('href', $this->prop('closeLink')),
                h::i
                (
                    setClass('icon icon-close'),
                    setStyle('color', '#313C52')
                )
            );
        }

        return div
        (
            setClass('module-menu rounded shadow-sm'),
            h::header
            (
                span
                (
                    setClass('module-title'),
                    $title
                ),
                $closeBtn
            ),
            h::main(zui::menutree(set($this->props->pick(array('items', 'activeClass', 'activeIcon', 'activeKye', 'onClickItem', 'defaultNestedShow', 'changeActiveKey', 'isDropdownMenu'))))),
            div
            (
                setClass('setting-btns'),
                a
                (
                    setClass('btn'),
                    setStyle('background', '#EEF5FF'),
                    setStyle('border', 'none'),
                    $lang->moduleSetting
                ),
                a
                (
                    setClass('btn white'),
                    $lang->displaySetting
                ),
            )
        );
    }
}
