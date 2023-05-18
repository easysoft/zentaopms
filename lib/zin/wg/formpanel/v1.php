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
    protected static $defineProps = array
    (
        'class?: string="panel-form rounded-md shadow ring-0 canvas px-4 pb-4 mb-4 mx-auto"', // 类名。
        'size?: string="lg"',                          // 额外尺寸。
        'id?: string="$GID"',                          // ID，如果不指定则自动生成（使用 zin 部件 GID）。
        'method?: "get"|"post"="post"',                // 表单提交方式。
        'url?: string',                                // 表单提交地址。
        'actions?: array=["submit","cancel"]',         // 表单操作按钮，如果不指定则使用默认行为的 “保存” 和 “返回” 按钮。
        'actionsClass?: string="form-group no-label"', // 表单操作按钮栏类名。
        'target?: string="ajax"',                      // 表单提交目标，如果是 `'ajax'` 提交则为 ajax，在禅道中除非特殊目的，都使用 ajax 进行提交。
        'submitBtnText?: string',                      // 表单提交按钮文本，如果不指定则使用 `$lang->save` 的值。
        'cancelBtnText?: string',                      // 表单取消按钮文本，如果不指定则使用 `$lang->goback` 的值。
        'items?: array',                               // 使用一个列定义对象数组来定义表单项。
        'grid?: bool=true',                            // 是否启用网格部件，禅道中所有表单都是网格布局，除非有特殊目的，无需设置此项。
        'labelWidth?: int',                            // 标签宽度，单位为像素。
        'batch?: bool'                                 // 是否为批量操作表单。
    );

    /**
     * The lifecycle method of created.
     *
     * Set default title to panel.
     * @access protected
     * @return void
     */
    protected function created()
    {
        $this->setDefaultProps(['title' => data('title')]);
    }

    /**
     * Build form widget by mode.
     *
     * @access protected
     * @return void
     */
    protected function buildForm()
    {
        if($this->prop('batch'))
        {
            return new formBatch
            (
                set($this->props->pick(array_keys(formBatch::getDefinedProps()))),
                $this->children()
            );
        }

        return new form
        (
            set($this->props->pick(array_keys(form::getDefinedProps()))),
            $this->children()
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
        $props = parent::buildProps();
        if($this->prop('batch')) $props[] = setCssVar('--zt-page-form-max-width', 'auto');
        return $props;
    }

    /**
     * Build panel body.
     *
     * @access protected
     * @return void
     */
    protected function buildBody()
    {
        return div
        (
            setClass('panel-body'),
            $this->buildForm()
        );
    }
}
