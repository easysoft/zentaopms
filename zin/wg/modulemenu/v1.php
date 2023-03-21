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

    private function setMenuTreeProps()
    {
        global $app;
        $id = $this->prop('productID');
        $this->setProp('productID', null);
        $this->modules = $app->loadTarget('tree')->getProductStructure($id, 'story');
        $this->setProp('items', $this->buildMenuTree(array(), 0));
        $this->setDefaultProps(array('activeClass' => 'active'));
    }

    protected function build()
    {
        $this->setMenuTreeProps();
        $activeKey = $this->prop('activeKey');
        $closeBtn = null;
        if(!empty($activeKey))
        {
            $closeBtn = a
            (
                set('href', $this->prop('closeLink')),
                h::i
                (
                    setClass('icon icon-close'),
                )
            );
        }
        return div
        (
            setClass('module-menu'),
            h::header
            (
                span
                (
                    setClass('module-title'),
                    $this->prop('title')
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
                    setClass('btn primary'),
                    $this->prop('moduleSettingText')
                ),
                a
                (
                    setClass('btn white'),
                    $this->prop('displaySettingText')
                ),
            )
        );
    }
}
