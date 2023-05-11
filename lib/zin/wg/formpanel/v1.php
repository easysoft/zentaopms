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
        'id?: string',
        'method?: string',
        'url?: string',
        'actions?: array',
        'actionsClass?: string',
        'target?: string',
        'submitBtnText?: string',
        'cancelBtnText?: string',
        'items?: array',
        'grid?: bool',
        'labelWidth?: int',
        'batch?: bool',
    );

    /**
     * Define default properties.
     *
     * @var    array
     * @access protected
     */
    protected static $defaultProps = array
    (
        'class' => 'panel-form rounded-md shadow ring-0 canvas px-4 pb-4 mb-4 mx-auto',
        'size'  => 'lg'
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
