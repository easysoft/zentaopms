<?php
declare(strict_types=1);
/**
 * The stepsEditor widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      sunhao<sunhao@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';

/**
 * 步骤编辑（stepsEditor）部件类
 * The stepsEditor widget class
 */
class stepsEditor extends wg
{
    /**
     * Define widget properties.
     *
     * @var    array
     * @access protected
     */
    protected static array $defineProps = array
    (
        'id?: string="$GID"',               // 组件根元素的 ID。
        'name?: string="steps"',            // 步骤输入框作为表单项的名称。
        'expectsName?: string="expects"',   // 预期输入框作为表单项的名称。
        'data?: array',                     // 默认值。
        'stepText?: string',                // 步骤文本。
        'expectText?: string',              // 预期文本。
        'sameLevelText?: string',           // 同级文本。
        'subLevelText?: string',            // 子级文本。
        'expectDisabledTip?: string',       // 预期输入框禁用提示。
        'dragNestedTip?: string'            // 拖拽超出提示。
    );

    public static function getPageJS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    public static function getPageCSS(): string|false
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    /**
     * Build the widget.
     *
     * @access protected
     * @return wg
     */
    protected function build(): wg
    {
        $stepText          = $this->prop('stepText',   data('lang.testcase.stepDesc'));
        $expectText        = $this->prop('expectText', data('lang.testcase.stepExpect'));
        $sameLevelText     = $this->prop('sameLevelText', data('lang.testcase.stepSameLevel'));
        $subLevelText      = $this->prop('subLevelText', data('lang.testcase.stepSubLevel'));
        $id                = $this->prop('id');
        $expectDisabledTip = $this->prop('expectDisabledTip', data('lang.testcase.expectDisabledTip'));
        $dragNestedTip     = $this->prop('dragNestedTip', data('lang.testcase.dragNestedTip'));

        return div
        (
            setID($id),
            setClass('steps-editor w-full'),
            div
            (
                setClass('steps-editor-header'),
                row
                (
                    setClass('steps-editor-row'),
                    cell
                    (
                        set::className('steps-editor-col steps-editor-col-step'),
                        $stepText
                    ),
                    cell
                    (
                        set::className('steps-editor-col steps-editor-col-add'),
                        div($sameLevelText),
                        div($subLevelText),
                    ),
                    cell
                    (
                        set::className('steps-editor-col steps-editor-col-expect'),
                        $expectText
                    )
                )
            ),
            div
            (
                set::className('steps-editor-body')
            ),
            zui::stepsEditor
            (
                set::_to("#$id"),
                set::expectDisabledTip($expectDisabledTip),
                set::dragNestedTip($dragNestedTip),
                set($this->props->pick(array('name', 'expectsName', 'data')))
            )
        );
    }
}
