<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'formgroup' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'formrow' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'formbase' . DS . 'v1.php';

/**
 * 通用表单（form）部件类，支持 Ajax 提交。
 * The common form widget class.
 */
class form extends formBase
{
    protected static array $defineProps = array
    (
        'items?: array',            // 使用一个列定义对象数组来定义表单项。
        'layout?: string="horz"',   // 表单布局，可选值为：'horz'、'grid' 和 `normal`。
        'labelWidth?: int',         // 标签宽度，单位为像素。
        'submitBtnText?: string',   // 提交按钮文本。
        'requiredFields?: string',  // 必填项定义。
        'data?: array|object',      // 表单项值默认数据。
        'labelData?: array|object', // 表单项标签默认数据。
        'actionsClass?: string="form-group no-label"' // 操作按钮栏的 CSS 类。
    );

    protected $layout = null;

    protected function isLayout($layout)
    {
        if(is_null($this->layout)) $this->layout = $this->prop('layout');
        return $this->layout == $layout;
    }

    protected function created()
    {
        parent::created();

        global $app, $lang;
        $module = $app->getModuleName();
        $method = $app->getMethodName();
        if(isAjaxRequest('modal'))
        {
            $text   = !empty($lang->$module->$method) ? $lang->$module->$method : zget($lang, $method, '');

            $defaultProps = array();
            $defaultProps['submitBtnText'] = $text;
            $defaultProps['class']         = 'px-3 pb-4';
            $this->setDefaultProps($defaultProps);
        }

        if(!$this->hasProp('data'))      $this->setProp('data',      data($module));
        if(!$this->hasProp('labelData')) $this->setProp('labelData', $lang->$module);
    }

    protected function getItemLabel(string $name): ?string
    {
        $labelData = $this->prop('labelData');
        $lblName   = 'lbl' . ucfirst($name);

        if(is_array($labelData))  return isset($labelData[$lblName]) ? $labelData[$lblName] : (isset($labelData[$name]) ? $labelData[$name] : null);
        if(is_object($labelData)) return isset($labelData->$lblName) ? $labelData->$lblName : (isset($labelData->$name) ? $labelData->$name : null);
        return null;
    }

    protected function getItemValue(string $name): ?string
    {
        $data = $this->prop('data');
        if(is_array($data))  return isset($data[$name]) ? strval($data[$name]) : null;
        if(is_object($data)) return isset($data->$name) ? strval($data->$name) : null;
        return null;
    }

    public function onBuildItem(item|array $item, string|int $key): wg
    {
        if(!($item instanceof item))
        {
            if($item instanceof wg) return $item;
            $item = item(set($item));
        }

        if(is_string($key) && !$item->hasProp('name')) $item->setProp('name', $key);
        $name = $item->prop('name');
        if(is_string($name))
        {
            if(!$item->hasProp('value'))
            {
                $value = $this->getItemValue($name);
                if(!is_null($value)) $item->setProp('value', $value);
            }
            if(!$item->hasProp('label'))
            {
                $label = $this->getItemLabel($name);
                if(!is_null($label)) $item->setProp('label', $label);
            }
        }
        if($this->hasProp('requiredFields') && !$item->hasProp('requiredFields')) $item->setProp('requiredFields', $this->prop('requiredFields'));

        if($this->isLayout('horz')) return new formRow(inherit($item));

        if($this->isLayout('grid'))
        {
            if(!$item->hasProp('width')) $item->setProp('width', '1/2');

            if(is_string($name))
            {
                $control = $item->prop('control');

                if(is_string($control)) $control = array('type' => $control, 'name' => $name);
                if(empty($control))     $control = array('name' => $name);

                if(!isset($control['id'])) $control['id'] = '';
                $item->setProp('control', $control);
            }
        }

        return new formGroup(inherit($item));
    }

    protected function buildActions(): wg|null
    {
        $actions = parent::buildActions();
        if($this->isLayout('horz') && !empty($actions)) $actions = div(setClass('form-row'), $actions);
        return $actions;
    }

    protected function buildProps(): array
    {
        $props = parent::buildProps();
        $layout = $this->prop('layout');
        $props[] = setClass("form-$layout");

        if($layout == 'horz')
        {
            $labelWidth = $this->prop('labelWidth');
            if(!empty($labelWidth)) $props[] = setCssVar('form-horz-label-width', $labelWidth);
        }

        return $props;
    }

    protected function buildContent(): array
    {
        list($items) = $this->prop(array('items'));

        $list     = is_array($items) ? array_map(array($this, 'onBuildItem'), $items, array_keys($items)) : array();
        $children = $this->children();
        if(!empty($children)) $list = array_merge($list, $children);

        if($this->isLayout('horz'))
        {
            foreach($list as $key => $item)
            {
                if($item instanceof formGroup) $list[$key] = new formRow($item);
            }
        }

        return $list;
    }
}
