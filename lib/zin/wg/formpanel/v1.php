<?php
declare(strict_types=1);
/**
 * The formPanel widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

require_once dirname(__DIR__) . DS . 'panel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'formgroup' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'formrow' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'form' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'formbatch' . DS . 'v1.php';

/**
 * 表单面板（formPanel）部件类。
 * The form panel widget class.
 *
 * @author Hao Sun
 */
class formPanel extends panel
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array(
        'class?: string="panel-form"', // 类名。
        'size?: string="lg"',                          // 额外尺寸。
        'formID?: string',                             // 表单 ID，如果指定为 '$AUTO'，则自动生成 form-$moduleName-$methodName。
        'formClass?: string',                          // 表单样式。
        'method?: "get"|"post"="post"',                // 表单提交方式。
        'url?: string',                                // 表单提交地址。
        'actions?: array',                             // 表单操作按钮，如果不指定则使用默认行为的 “保存” 和 “返回” 按钮。
        'actionsClass?: string="form-group no-label"', // 表单操作按钮栏类名。
        'target?: string="ajax"',                      // 表单提交目标，如果是 `'ajax'` 提交则为 ajax，在禅道中除非特殊目的，都使用 ajax 进行提交。
        'submitBtnText?: string',                      // 表单提交按钮文本，如果不指定则使用 `$lang->save` 的值。
        'cancelBtnText?: string',                      // 表单取消按钮文本，如果不指定则使用 `$lang->goback` 的值。
        'items?: array',                               // 使用一个列定义对象数组来定义表单项。
        'layout?: string="horz"',                      // 表单布局，可选值为：'horz'、'grid' 和 `normal`。
        'labelWidth?: int',                            // 标签宽度，单位为像素。
        'batch?: bool',                                // 是否为批量操作表单。
        'shadow?: bool=false',                         // 是否显示阴影层。
        'width?: string',                              // 最大宽度。
        'modeSwitcher?: bool',                         // 是否显示表单模式按钮。
        'data?: array|object',                         // 表单项值默认数据。
        'labelData?: array|object',                    // 表单项标签默认数据。
        'loadUrl?: string',                            // 动态更新 URL。
        'autoLoad?: array',                            // 动态更新策略。
        'defaultMode?: string="lite"',                 // 默认表单模式（lite: 简洁版，full: 完整版）。
        'foldableItems?: array|string',                // 可折叠的表单项。
        'pinnedItems?: array|string',                  // 固定显示的表单项。
        'customBtn?: array|bool',                      // 是否显示表单自定义按钮。
        'customFields?: array=[]'                      // @deprecated 自定义表单项。
    );

    public static function getPageJS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    protected function created()
    {
        $customFields = $this->prop('customFields');
        if($customFields === true)
        {
            global $app, $config, $lang;
            $module = $app->rawModule;
            $method = $app->rawMethod;

            $app->loadLang($module);
            $app->loadModuleConfig($module);

            $key           = $method . 'Fields';
            $listFields    = array();
            $listFieldsKey = 'custom' . ucfirst($key);
            if(!empty($config->$module->$listFieldsKey))       $listFields = explode(',', $config->$module->$listFieldsKey);
            if(!empty($config->$module->list->$listFieldsKey)) $listFields = explode(',', $config->$module->list->$listFieldsKey);

            if(empty($listFields))
            {
                $this->setProp('customFields', array());
                return false;
            }

            $fields = array();
            foreach($listFields as $field) $fields[$field] = $lang->$module->$field;

            $showFields   = explode(',', $config->$module->custom->$key);
            $customFields = array('list' => $fields, 'show' => $showFields, 'key' => $key);

            $this->setProp('customFields', $customFields);
        }

        if($this->prop('modeSwitcher'))
        {
            global $lang;

            $modeSwitcher = btnGroup
            (
                set::size('sm'),
                btn
                (
                    setClass('gray-300-outline text-sm rounded-full btn-lite-form'),
                    bind::click('$element.closest(\'.form,.panel-form\').addClass(\'is-lite-form\').removeClass(\'is-full-form\')'),
                    $lang->liteMode
                ),
                btn
                (
                    setClass('gray-300-outline text-sm rounded-full btn-full-form'),
                    bind::click('$element.closest(\'.form,.panel-form\').addClass(\'is-full-form\').removeClass(\'is-lite-form\')'),
                    $lang->fullMode
                )
            );
            $this->addToBlock('headingActions', $modeSwitcher);
        }
    }

    /**
     * Build heading actions.
     *
     * @access protected
     * @return ?wg
     */
    protected function buildHeadingActions(): ?wg
    {
        $headingActions = $this->prop('headingActions');
        if(!$headingActions) $headingActions = array();

        /* Custom fields. */
        $customFields = $this->prop('customFields', array());
        if($customFields)
        {
            global $app;
            $listFields = zget($customFields, 'list', array());
            $showFields = zget($customFields, 'show', array());
            $key        = zget($customFields, 'key', $app->rawMethod);

            if($listFields && $key)
            {
                $urlParams        = "module={$app->rawModule}&section=custom&key={$key}";
                $headingActions[] = formSettingBtn
                (
                    set::customFields(array('list' => $listFields, 'show' => $showFields)),
                    set::urlParams(zget($customFields, 'urlParams', $urlParams)),
                );

                $this->setProp('headingActions', $headingActions);
            }
        }

        return parent::buildHeadingActions();
    }

    /**
     * Build form widget by mode.
     *
     * @access protected
     * @return wg
     */
    protected function buildForm(): wg
    {
        $customFields = $this->prop('customFields', array());
        $listFields   = zget($customFields, 'list', array());
        $showFields   = zget($customFields, 'show', array());
        $hiddenFields = $listFields && $showFields ? array_values(array_diff(array_keys($listFields), $showFields)) : array();

        if($this->prop('batch'))
        {
            $props = formBatch::definedPropsList();
            unset($props['id']);
            $formProps = array_keys($props);
            return new formBatch
            (
                set($this->props->pick($props)),
                $this->children(),
                jsVar('formBatch', true),
                $hiddenFields ? jsVar('hiddenFields', $hiddenFields) : null
            );
        }

        $props     = array_keys(form::definedPropsList());
        $formProps = array();
        foreach($props as $propName)
        {
            if($this->hasProp($propName) && $propName !== 'id') $formProps[] = $propName;
        }

        return new form
        (
            set::className($this->prop('formClass')),
            set($this->props->pick($formProps)),
            $this->children(),
            $hiddenFields ? jsVar('hiddenFields', $hiddenFields) : null
        );
    }

    /**
     * Build widget props.
     *
     * @access protected
     * @return array
     */
    protected function buildProps(): array
    {
        list($width, $batch, $shadow, $defaultMode) = $this->prop(array('width', 'batch', 'shadow', 'defaultMode'));
        $props = parent::buildProps();
        $props[] = setClass("is-$defaultMode-form");

        if($width)     $props[] = setCssVar('--zt-page-form-max-width', $width);
        elseif($batch) $props[] = setCssVar('--zt-page-form-max-width', 'auto');
        if($shadow)    $props[] = setClass('shadow');

        return $props;
    }

    /**
     * Build panel body.
     *
     * @access protected
     * @return wg
     */
    protected function buildBody(): wg
    {
        return div
        (
            setClass('panel-body ' . $this->prop('bodyClass')),
            set($this->prop('bodyProps')),
            $this->buildContainer($this->buildForm())
        );
    }
}
