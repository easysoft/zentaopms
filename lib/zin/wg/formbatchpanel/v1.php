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
    protected static $defineProps = array(
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
        'onRenderRow?: function',       // 渲染行时的回调函数。
        'onRenderRowCol?: function'     // 渲染列时的回调函数。
    );

    /**
     * Define default properties.
     *
     * @var    array
     * @access protected
     */
    protected static $defaultProps = array(
        'uploadParams' => false,
        'pasteField'   => false,
        'customFields' => array(),
        'batch'        => true
    );

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    public static function getPageJS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    private function buildCustomizeAction($customFields)
    {
        global $lang;

        $customLink = createLink('custom', 'ajaxSaveCustomFields', 'module=story&section=custom&key=batchCreateFields');
        return dropdown
        (
            set::arrow('false'),
            set::placement('bottom-end'),
            to::trigger(btn(set::icon('cog-outline'), setClass('ghost'), set::caret(false))),
            to::menu(menu
            (
                setClass('dropdown-menu'),
                on::click('e.stopPropagation();'),
                formpanel
                (
                    set::ID('customizeBatchForm'),
                    set::title($lang->customField),
                    set::url($customLink),
                    set::actions(array
                    (
                        btn(set::text($lang->save), set::btnType('submit'), setClass('primary')),
                        btn(set::text($lang->cancel), set::btnType('reset'), on::click('closeCustomPopupMenu')),
                        btn(set::text($lang->restore), setClass('text-primary ghost font-bold'), set::url($customLink)),
                    )),
                    to::headingActions(array(btn(set::icon('close'), setClass('ghost'), set::size('sm'), on::click('closeCustomPopupMenu')))),
                    array_map(function($field)
                    {
                        return checkbox
                        (
                            set::name('fields[]'),
                            set::value($field['name']),
                            set::text($field['text']),
                            set::checked($field['show'])
                        );
                    }, $customFields),
                )
            ))
        );
    }

    protected function buildHeadingActions()
    {
        $headingActions = $this->prop('headingActions');
        if(!$headingActions) $headingActions = array();

        $uploadImage  = $this->prop('uploadParams') && hasPriv('file', 'uploadImages');
        $pasteField   = $this->prop('pasteField');
        $customFields = $this->prop('customFields');

        global $lang;

        if($uploadImage)
        {
            $headingActions[] = array('url' => createLink('file', 'uploadImages', $this->prop('uploadParams')), 'class' => 'btn primary-pale bd-primary mr-4', 'data-toggle' => 'modal', 'data-width' => '0.7', 'text' => $lang->uploadImages);

            $this->addToBlock('headingActions', modal
            (
                set::id('upload-dialog'),
                set::title(array('html' => div(span($lang->uploadImages), span(set::class('text-gray text-sm font-normal'), $lang->uploadImagesTip)))),
                uploadImgs()
            ));
        }

        if($pasteField)
        {
            $headingActions[] = array('class' => 'btn primary-pale bd-primary', 'data-toggle' => 'modal', 'data-target' => '#paste-dialog', 'text' => $lang->pasteText);

            $this->addToBlock('headingActions', pasteDialog(set::field($pasteField)));
        }

        if($customFields) $headingActions[] = $this->buildCustomizeAction($customFields);

        $this->setProp('headingActions', $headingActions);

        return parent::buildHeadingActions();
    }
}
