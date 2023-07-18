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
        'id?: string="$GID"',                          // ID，如果不指定则自动生成（使用 zin 部件 GID）。
        'formClass?: string',                          // 表单样式。
        'method?: "get"|"post"="post"',                // 表单提交方式。
        'url?: string',                                // 表单提交地址。
        'actions?: array',                             // 表单操作按钮，如果不指定则使用默认行为的 “保存” 和 “返回” 按钮。
        'actionsClass?: string="form-group no-label"', // 表单操作按钮栏类名。
        'target?: string="ajax"',                      // 表单提交目标，如果是 `'ajax'` 提交则为 ajax，在禅道中除非特殊目的，都使用 ajax 进行提交。
        'submitBtnText?: string',                      // 表单提交按钮文本，如果不指定则使用 `$lang->save` 的值。
        'cancelBtnText?: string',                      // 表单取消按钮文本，如果不指定则使用 `$lang->goback` 的值。
        'items?: array',                               // 使用一个列定义对象数组来定义表单项。
        'grid?: bool=true',                            // 是否启用网格部件，禅道中所有表单都是网格布局，除非有特殊目的，无需设置此项。
        'labelWidth?: int',                            // 标签宽度，单位为像素。
        'batch?: bool',                                // 是否为批量操作表单。
        'shadow?: bool=false',                         // 是否显示阴影层。
        'width?: string'                               // 最大宽度。
    );

    /**
     * Define default properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defaultProps = array(
        'customFields' => array(),
    );

    public static function getPageJS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
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

        $customFields = $this->prop('customFields');

        /* Custom fields. */
        if($customFields)
        {
            global $app;
            $urlParams = isset($customFields['urlParams']) ? $customFields['urlParams'] : "module={$app->rawModule}&section=custom&key=batchCreateFields";

            $headingActions[] = formSettingBtn(set::customFields($customFields['items']), set::urlParams($urlParams));
        }

        $this->setProp('headingActions', $headingActions);

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
        $customFields = $this->prop('customFields');
        $hiddenFields = array();
        if(!empty($customFields['items']))
        {
            $hiddenFields = array_values(array_filter(array_map(function($item)
            {
                return $item['show'] ? false : $item['name'];
            }, $customFields['items'])));
        }

        if($this->prop('batch'))
        {
            return new formBatch
            (
                set($this->props->pick(array_keys(formBatch::definedPropsList()))),
                $this->children(),
                jsVar('formBatch', true),
                $hiddenFields ? jsVar('hiddenFields', $hiddenFields) : null,
            );
        }

        return new form
        (
            set::class($this->prop('formClass')),
            set($this->props->pick(array_keys(form::definedPropsList()))),
            $this->children(),
            $hiddenFields ? jsVar('hiddenFields', $hiddenFields) : null,
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
        list($width, $batch, $shadow) = $this->prop(array('width', 'batch', 'shadow'));
        $props = parent::buildProps();

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
            setClass('panel-body'),
            $this->buildForm()
        );
    }
}
