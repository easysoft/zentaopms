<?php
namespace zin;

class moduleMenu extends wg
{
    private $modules = [];

    protected static $defineProps = [
        'productID:number',
        'activeKey:number',
        'closeLink:string'
    ];

    public static function getPageCSS()
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    private function buildMenuTree($parentItems, $parentID)
    {
        $children = $this->getChildModule($parentID);
        if(count($children) === 0) return [];

        foreach($children as $child)
        {
            $item = array('key' => $child->id, 'text' => $child->name, 'items' => []);
            $items = $this->buildMenuTree($item['items'], $child->id);
            if(count($items) !== 0) $item['items'] = $items;
            else unset($item['items']);
            $parentItems[] = $item;
        }
        return $parentItems;
    }

    private function getChildModule($id)
    {
        return array_filter($this->modules, fn($module) => $module->parent == $id);
    }

    private function setMenuTreeProps()
    {
        global $app;
        $id = $this->prop('productID');
        $this->setProp('productID', null);
        $this->modules = $app->loadTarget('tree')->getProductStructure($id, 'story');
        $this->setProp('items', $this->buildMenuTree([], 0));
        $this->setDefaultProps(array('activeClass' => 'active'));
    }

    private function getTitle($activeKey)
    {
        foreach($this->modules as $module)
            if($module->id == $activeKey)
                return $module->name;
    }

    protected function build()
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
            h::main
            (
                zui::menutree(inherit($this))
            ),
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
