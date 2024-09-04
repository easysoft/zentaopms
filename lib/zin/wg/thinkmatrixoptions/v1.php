<?php
declare(strict_types=1);
/**
 * The thinkMatrixOptions widget class file of zin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zemei Wang<wangzemei@easycorp.ltd>
 * @package     zin
 * @link        http://www.zentao.net
 */
namespace zin;

require_once dirname(__DIR__) . DS . 'input' . DS . 'v1.php';

class thinkMatrixOptions extends wg
{
    protected static array $defineProps = array
    (
        'id?: string="$GID"',           // 组件根元素的 ID。
        'colName?: string="colFields"', // 列数据的名称。
        'cols?: array',                 // 列数据的默认值。
        'deleteColTip?: string',        // 禁用删除提示。
        'addColTip?: string',           // 禁用添加提示。
        'addColText: string',           // 添加列按钮文字。
        'quotedQuestions?: array',      // 引用当前选项的问题。
    );

    public static function getPageJS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    public static function getPageCSS(): ?string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    protected function build()
    {
        global $lang, $app;
        $app->loadLang('thinkstep');
        jsVar('tipQuestion', $lang->thinkstep->tips->question);
        jsVar('cannotDeleteColumnTip', $lang->thinkstep->tips->cannotDeleteColumn);

        $id           = $this->prop('id') ? $this->prop('id') : $this->gid;
        $deleteColTip = $this->prop('deleteColTip', $lang->thinkstep->tips->deleteCol);
        $addColText   = $this->prop('addColText', $lang->thinkstep->addCol);
        $addColTip    = $this->prop('addColTip', $lang->thinkstep->tips->addCol);

        return div
        (
            setID($id),
            setClass('think-multiple w-full'),
            div(setClass('think-multiple-body flex overflow-x-auto overflow-y-hidden')),
            setData('quotedQuestions', $this->prop('quotedQuestions')),
            zui::thinkMatrixOptions
            (
                set::_to("#$id"),
                set::deleteColTip($deleteColTip),
                set::colPlaceholder($lang->thinkstep->label->columnTitle),
                set::addColText($addColText),
                set::addColTip($addColTip),
                set($this->props->pick(array('colName', 'cols')))
            )
        );
    }
}
