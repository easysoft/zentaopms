<?php
namespace zin;

class modulemenu extends wg
{
    private $modules = array();

    protected static $defineProps = array('js-render?:bool=true');

    private function buildMenuTree($parent, $parentID)
    {
        $children = $this->getChildModule($parentID);
        if(count($children) === 0) return;

        foreach($children as $child)
        {
            $item = array('key' => $child->id, 'text' => $child->name);
            $items = $this->buildMenuTree($item['items'], $child->id);
            if(count($items) !== 0) $item['items'] = $items;
            $parent[] = $item;
        }

        return $parent;
    }

    private function getChildModule($id)
    {
        return array_filter($this->modules, function($module) use($id) {return $module->parent == $id;});
    }

    protected function build()
    {
        global $app;
        $id = $this->prop('productID');
        $this->setProp('productID', null);
        $this->modules = $app->loadTarget('tree')->getProductStructure($id, 'story');
        $moduleItems = $this->buildMenuTree(array(), 0);
        $this->setProp('items', $moduleItems);
        $this->setProp('activeClass', 'active');

        return zui::modulemenu(inherit($this));
    }
}
