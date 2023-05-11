<?php
namespace zin;

require_once dirname(__DIR__) . DS . 'formgroup' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'formrow' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'formbase' . DS . 'v1.php';

/**
 * 通用表单（form）部件类，支持 Ajax 提交
 * The common form widget class
 */
class form extends formBase
{
    protected static $defineProps = array
    (
        'items?: array',
        'grid?: bool',
        'labelWidth?: int'
    );

    protected static $defaultProps = array
    (
        'grid'          => true,
        'actionsClass'  => 'form-group no-label'
    );

    public function onBuildItem(item|array $item): wg
    {
        if(!($item instanceof item))
        {
            if($item instanceof wg) return $item;
            $item = item(set($item));
        }

        if($this->prop('grid')) return new formRow(inherit($item));

        return new formGroup(inherit($item));
    }

    protected function buildActions(): wg|null
    {
        $actions = parent::buildActions();
        if($this->prop('grid') && !empty($actions)) $actions = div(setClass('form-row'), $actions);
        return $actions;
    }

    protected function buildProps(): array
    {
        list($grid, $labelWidth) = $this->prop(array('grid', 'labelWidth'));
        $props = parent::buildProps();
        if($grid)               $props[] = set::class('form-grid');
        if(!empty($labelWidth)) $props[] = setCssVar('form-label-width', $labelWidth);

        return $props;
    }

    protected function buildContent(): array
    {
        list($items, $grid) = $this->prop(['items', 'grid']);

        $list     = is_array($items) ? array_map(array($this, 'onBuildItem'), $items) : [];
        $children = $this->children();
        if(!empty($children)) $list = array_merge($list, $children);

        if($grid)
        {
            foreach($list as $key => $item)
            {
                if($item instanceof formGroup) $list[$key] = new formRow($item);
            }
        }

        return $list;
    }
}
