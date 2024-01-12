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
        'formID?: string',                 // 表单 ID，如果指定为 '$AUTO'，则自动生成 form-$moduleName-$methodName。
        'items?: array',                   // 使用一个列定义对象数组来定义表单项。
        'fields?: string|array|fieldList', // 表单字段配置。
        'foldableItems?: array|string',    // 可折叠的表单项。
        'pinnedItems?: array|string',      // 固定显示的表单项。
        'customBtn?: array|bool',          // 是否显示表单自定义按钮。
        'customUrl?: string',              // 自定义表单提交 URL。
        'toolbar?: array|bool',            // 额外的自定义按钮。
        'layout?: string="horz"',          // 表单布局，可选值为：'horz'、'grid' 和 `normal`。
        'labelWidth?: int',                // 标签宽度，单位为像素。
        'submitBtnText?: string',          // 提交按钮文本。
        'requiredFields?: string|false',   // 必填项定义。
        'data?: array|object',             // 表单项值默认数据。
        'labelData?: array|object',        // 表单项标签默认数据。
        'loadUrl?: string',                // 动态更新 URL。
        'autoLoad?: array',                // 自动更新策略。
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

        if($this->prop('customBtn'))
        {
            global $config;
            $module = $app->rawModule;
            $method = $app->rawMethod;
            $key    = $method . 'Fields';

            $app->loadLang($module);
            $app->loadModuleConfig($module);

            if(!$this->hasProp('foldableItems'))
            {
                $listFieldsKey = 'custom' . ucfirst($key);
                $fieldList     = empty($config->$module->$listFieldsKey) ? $config->$module->list->$listFieldsKey : $config->$module->$listFieldsKey;
                $foldableItems = empty($fieldList) ? array() : explode(',', $fieldList);
                if(!empty($foldableItems))
                {
                    $this->setProp('foldableItems', $foldableItems);
                }
            }
            if(!$this->hasProp('pinnedItems'))
            {
                $this->setProp('pinnedItems', explode(',', $config->$module->custom->$key));
            }
        }

        if ($this->prop('formID') === '$AUTO')
        {
            $this->setProp('formID', "form-$module-$method");
        }

        $fields = $this->prop('fields');
        if(is_string($fields)) $fields = explode(',', $fields);
        if(is_array($fields))  $fields = useFields($fields);

        if($fields instanceof fieldList)
        {
            $items = $fields->toList();
            $this->setProp('items', $items);
            if(!is_null($fields->labelData)) $this->setProp('labelData', $fields->labelData);
            if(!is_null($fields->valueData)) $this->setProp('data',      $fields->valueData);
        }
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

    public function onBuildItem(item $item): wg
    {
        return new formGroup(inherit($item));
    }

    protected function getItemProps(item|array $item, string|int $key, ?array $foldableItems = null, ?array $pinnedItems = null, bool $isGrid = false, bool|string|null $requiredFields = null): array
    {
        if(is_string($key) && !isset($item['name'])) $item['name'] = $key;
        $name = $item['name'];

        if(is_string($name))
        {
            if(!isset($item['value'])) $item['value'] = $this->getItemValue($name);
            if(!isset($item['label'])) $item['label'] = $this->getItemLabel($name);
            if($requiredFields !== false && (!isset($item['required']) || $item['required'] === 'auto')) $item['required'] = isFieldRequired($name, $requiredFields);
            if(!isset($item['foldable']) && is_array($foldableItems)) $item['foldable'] = in_array($name, $foldableItems);
            if(!isset($item['pinned']) && is_array($pinnedItems))     $item['pinned'] =   in_array($name, $pinnedItems);

            if($isGrid)
            {
                if(!isset($item['width'])) $item['width'] = '1/2';

                $control = isset($item['control']) ? $item['control'] : array('name' => $name);
                if(is_string($control)) $control = array('control' => $control, 'name' => $name);
                if(!isset($control['id'])) $control['id'] = '';
                $item['control'] = $control;
            }
        }
        return $item;
    }

    protected function buildCustomBtn()
    {
        global $lang;
        list($customBtn, $customUrl) = $this->prop(array('customBtn', 'customUrl'));

        if(is_null($customUrl))
        {
            global $app;
            $customUrl = createLink('custom', 'ajaxSaveCustomFields', "module={$app->rawModule}&section=custom&key={$app->rawMethod}Fields");
        }

        return btn
        (
            setClass('gray-300-outline rounded-full btn-custom-form'),
            setData(array('title' => $lang->fieldDisplaySetting, 'tip' => $lang->fieldSettingTip, 'customUrl' => $customUrl)),
            set::icon('cog-outline'),
            bind::click('zui.FormSetting.show({element: event.target, tip: options.tip, title: options.title, customUrl: options.customUrl});'),
            is_array($customBtn) ? set($customBtn) : null
        );
    }

    public function buildItems(): array
    {
        global $lang;
        list($items, $foldableItems, $pinnedItems, $requiredFields, $customBtn, $toolbar) = $this->prop(array('items', 'foldableItems', 'pinnedItems', 'requiredFields', 'customBtn', 'toolbar'));

        if(!is_array($items))                                   $items         = array();
        if(is_string($pinnedItems) && !empty($pinnedItems))     $pinnedItems   = explode(',', $pinnedItems);
        if(is_string($foldableItems) && !empty($foldableItems)) $foldableItems = explode(',', $foldableItems);
        if(is_null($customBtn) && !empty($foldableItems))       $customBtn     = true;

        $isGrid       = $this->isLayout('grid');
        $list         = array();
        $foldableList = array();

        foreach($items as $key => $item)
        {
            if($item instanceof wg && !($item instanceof item))
            {
                $list[] = $item;
                continue;
            }

            if($item instanceof item)      $item = $item->props->toJson();
            elseif($item instanceof field) $item = $item->toArray();
            $itemsProps = $this->getItemProps($item, $key, $foldableItems, $pinnedItems, $isGrid, $requiredFields);

            $formGroup = new formGroup(set($itemsProps));
            if(isset($itemsProps['foldable']) && $itemsProps['foldable']) $foldableList[] = $formGroup;
            else $list[] = $formGroup;
        }

        $toolbarList = array();
        if(!empty($toolbar))
        {
            if(!isset($toolbar['items'])) $toolbar = array('items' => $toolbar);
            $toolbarList[] = toolbar(set($toolbar));
        }

        if(!empty($foldableList))
        {
            $list[]        = div(setClass('panel-form-divider'));
            $toolbarList[] = toolbar
            (
                setClass('size-sm'),
                btn
                (
                    setClass('gray-300-outline rounded-full btn-toggle-fold'),
                    setData(array('collapse-text' => $lang->hideMoreInfo, 'expand-text' => $lang->showMoreInfo)),
                    bind::click('$element.closest(\'.panel-form\').toggleClass(\'show-fold-items\')')
                ),
                $customBtn ? $this->buildCustomBtn() : null
            );
        }

        if(!empty($toolbarList)) $foldableList[] = div(setClass('panel-form-toolbar'), $toolbarList);

        return array_merge($list, $foldableList);
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
        $props[] = setClass("form-$layout", $this->prop('requiredFields') === false ? 'no-required' : '');

        if($layout == 'horz')
        {
            $labelWidth = $this->prop('labelWidth');
            if(!empty($labelWidth)) $props[] = setCssVar('form-horz-label-width', $labelWidth);
        }

        if($this->hasProp('formID'))  $props[] = setID($this->prop('formID'));
        if($this->hasProp('loadUrl')) $props[] = setData('load-url', $this->prop('loadUrl'));

        $autoLoad = $this->prop('autoLoad');
        if(is_array($autoLoad) || is_object($autoLoad))
        {
            $props[] = bind::change(implode('', array
            (
                'const autoLoad = ' . json_encode($autoLoad) . ';',
                'const $ele = $(event.target);',
                'Object.keys(autoLoad).forEach(selector => ',
                '{',
                    'const setting = autoLoad[selector];',
                    'if(!".[#".includes(selector[0])) selector = `[name="${selector}"]`;',
                    'if(!$ele.closest(selector).length) return;',
                    'loadForm($.extend({target: $ele}, typeof setting === "string" ? {items: setting} : setting));',
                '});'
            )));
        }

        return $props;
    }

    protected function buildContent(): array
    {
        $items    = $this->buildItems();
        $children = $this->children();
        if(!empty($children)) $items = array_merge($items, $children);

        if($this->isLayout('horz'))
        {
            foreach($items as $key => $item)
            {
                if($item instanceof formGroup) $items[$key] = new formRow($item);
            }
        }

        return $items;
    }
}
