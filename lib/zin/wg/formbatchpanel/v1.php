<?php
declare(strict_types=1);
/**
 * The formBatchPanel widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */

namespace zin;

require_once dirname(__DIR__) . DS . 'formpanel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'pastedialog' . DS . 'v1.php';

/**
 * 批量编辑表单面板（formBatchPanel）部件类。
 * The batch operate form panel widget class.
 *
 * @author Hao Sun
 */
class formBatchPanel extends formPanel
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array(
        'uploadParams?: string|false',  // 多图上传的参数，如果设置为 `false` 则不显示按钮
        'pasteField?: string|false',    // 多行录入的字段名，如果设置为 `false` 则不显示按钮
        'items?: array[]',              // 使用一个列定义对象数组来定义批量表单项。
        'minRows?: int',                // 最小显示的行数目。
        'maxRows?: int',                // 最多显示的行数目。
        'data?: array[]',               // 初始化行数据。
        'mode?: string',                // 批量操作模式，可以为 `'add'`（批量添加） 或 `'edit'`（批量编辑）。
        'actionsText?: string',         // 操作列头部文本，如果不指定则使用 `$lang->actions` 的值。
        'addRowIcon?: string|false',    // 添加行的图标，如果设置为 `false` 则不显示图标
        'deleteRowIcon?: string|false', // 删除行的图标，如果设置为 `false` 则不显示图标
        'sortRowIcon?: string|false',   // 排序行的图标，如果设置为 `false` 则不显示图标
        'sortable?: boo|array',         // 排序配置，设置为 false 不启用排序，设置为 true 使用默认排序
        'onRenderRow?: function',       // 渲染行时的回调函数。
        'onRenderRowCol?: function',    // 渲染列时的回调函数。
        'batchFormOptions?: array'      // 批量表单选项。
    );

    public static function getPageCSS(): ?string
    {
        return <<<'CSS'
        .panel-form-batch {margin: 0 auto; padding-bottom: 0}
        .panel-form-batch > .panel-heading {padding-left: 0; padding-right: 0}
        .panel-form-batch > .panel-body {position: relative; padding: 0; margin: 0 -16px}
        .panel-form-batch .form {gap: 0}
        .panel-form-batch .form-batch-container {max-height: calc(100vh - 214px); padding: 0 16px 16px; flex: auto; min-height: 0; overflow: auto}
        .panel-form-batch .form-actions {left: 0; position: static; flex: none; padding: 24px 0; border-top: 1px solid var(--color-border)}
        .modal-body .panel-form-batch .form-batch-container {max-height: calc(100vh - 208px);}
        .modal-body > .panel-form-batch {box-shadow: none; margin-top: -12px; margin-bottom: -24px}
        .modal-actions + .modal-body > .panel-form-batch > .panel-heading {padding-right: 20px; background: var(--color-canvas); position: sticky; top: -12px; z-index: 12}
        CSS;
    }


    /**
     * Define default properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defaultProps = array(
        'class'        => 'panel-form panel-form-batch',
        'uploadParams' => false,
        'pasteField'   => false,
        'customFields' => array(),
        'batch'        => true,
        'shadow'       => true
    );

    protected function getHeadingActions(): array
    {
        global $lang;

        $actions      = parent::getHeadingActions();
        $uploadImage  = $this->prop('uploadParams') && hasPriv('file', 'uploadImages');
        $pasteField   = $this->prop('pasteField');

        /* Multi-input. */
        if($pasteField)
        {
            array_unshift($actions, array('class' => 'btn primary-pale mr-2', 'data-toggle' => 'modal', 'data-target' => '#paste-dialog', 'text' => $lang->pasteText, 'data-backdrop' => 'static'));

            $headingActionsBlock = $this->block('headingActions');
            if(empty($headingActionsBlock) || array_every($headingActionsBlock, function($item){return !($item instanceof pasteDialog);}))
            {
                $this->addToBlock('headingActions', new pasteDialog(set::field($pasteField)));
            }
        }

        /* Upload images. */
        if($uploadImage) array_unshift($actions, array('url' => createLink('file', 'uploadImages', $this->prop('uploadParams')), 'class' => 'btn primary-pale mr-4', 'data-toggle' => 'modal', 'data-width' => '0.7', 'text' => $lang->uploadImages));

        return $actions;
    }
}
