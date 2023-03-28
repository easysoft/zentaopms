<?php
namespace zin;

class modulemenu extends wg
{
    private $modules = array();

    protected static $defineProps = array
    (
        'productID:number',
        'moduleSettingText?:string="模块设置"',
        'displaySettingText?:string="显示设置"',
    );

    private function buildMenuTree($parent, $parentID)
    {
        $children = $this->getChildModule($parentID);
        if(count($children) === 0) return array();

        foreach($children as $child)
        {
            $item = array('key' => $child->id, 'text' => $child->name, 'items' => array());
            $items = $this->buildMenuTree($item['items'], $child->id);
            if(count($items) !== 0) $item['items'] = $items;
            else                    unset($item['items']);
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
                    setStyle('color', '#313C52')
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
