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
        'id?: string="$GID"',     // 组件根元素的 ID。
        'name?: string="fields"', // 输入框作为表单项的名称。
        'data?: array',           // 默认值。
        'deleteTip?: string',     // 禁用删除提示。
        'addColText: string',     // 添加列按钮文字
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
        $id         = $this->prop('id') ? $this->prop('id') : $this->gid;
        $deleteTip  = $this->prop('deleteTip', $lang->thinkstep->tips->deleteCol);
        $addColText = $this->prop('addColText', $lang->thinkstep->addCol);

        return div
        (
            setID($id),
            setClass('think-multiple w-full h-full overflow-x-auto overflow-y-hidden px-px pb-0.5'),
            div(setClass('think-multiple-body flex')),
            zui::thinkMatrixOptions
            (
                set::_to("#$id"),
                set::deleteTip($deleteTip),
                set::enterPlaceholder($lang->thinkstep->label->columnTitle),
                set::addColText($addColText),
                set($this->props->pick(array('name', 'data')))
            )
        );
    }
}
