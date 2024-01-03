<?php
declare(strict_types=1);
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
    protected static array $defineProps = array(
        'items?: array',    // 使用一个列定义对象数组来定义表单项。
        'grid?: bool=true', // 是否启用网格部件，禅道中所有表单都是网格布局，除非有特殊目的，无需设置此项。
        'labelWidth?: int', // 标签宽度，单位为像素。
        'actionsClass?: string="form-group no-label"', // 操作按钮栏的 CSS 类。
        'submitBtnText?: string' // 提交按钮文本。
    );

    protected function created()
    {
        parent::created();

        if(!isAjaxRequest('modal')) return;

        global $app, $lang;
        $module = $app->getModuleName();
        $method = $app->getMethodName();
        $text   = !empty($lang->$module->$method) ? $lang->$module->$method : zget($lang, $method, '');

        $defaultProps = array();
        $defaultProps['submitBtnText'] = $text;
        $defaultProps['class']         = 'px-3 pb-4';

        $this->setDefaultProps($defaultProps);
    }

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
        if($grid)               $props[] = setClass('form-horz');
        if(!empty($labelWidth)) $props[] = setCssVar('form-horz-label-width', $labelWidth);

        return $props;
    }

    protected function buildContent(): array
    {
        list($items, $grid) = $this->prop(array('items', 'grid'));

        $list     = is_array($items) ? array_map(array($this, 'onBuildItem'), $items) : array();
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
