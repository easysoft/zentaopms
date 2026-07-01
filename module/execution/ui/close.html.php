<?php
declare(strict_types=1);
/**
 * The close view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

$confirmTip   = !empty($unclosedTasks) ? sprintf($this->lang->execution->confirmCloseExecution, implode($this->lang->comma, array_keys($unclosedTasks))) : '';
$confirmURL   = $this->createLink('execution', 'close', "executionID={$executionID}&from={$from}");
$beforeSubmit = jsRaw("() =>
{
    if(window.confirmShown) return true;
    window.confirmShown = true;

    let realBegan = '{$execution->realBegan}';
    let realEnd   = $('[name=realEnd]').val();
    let today     = zui.formatDate(zui.createDate(), 'yyyy-MM-dd');
    if(realBegan >= realEnd || realEnd > today) return true;

    return zui.Modal.confirm('{$confirmTip}').then((res) =>
    {
        if(res)
        {
            const formData = new FormData($('#zin_execution_close_{$executionID}_form')[0]);
            $.ajaxSubmit({url: '{$confirmURL}', data: formData});
        }
        if(!res) window.confirmShown = false;
        return res;
    });
}");

$ajaxOptions = array();
if(!empty($unclosedTasks)) $ajaxOptions['beforeSubmit'] = $beforeSubmit;
$ajaxOptions['onValidateField'] = jsRaw('showErrorTip');

$space = common::checkNotCN() ? ' ' : '';
modalHeader(set::title($lang->execution->close . $space . $lang->executionCommon));
formPanel
(
    set::formID('zin_execution_close_' . $executionID . '_form'),
    set::submitBtnText($lang->execution->close . $space . $lang->executionCommon),
    set::ajax($ajaxOptions),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->execution->realEnd),
        set::name('realEnd'),
        set::control('date'),
        set::value(!helper::isZeroDate($execution->realEnd) ? $execution->realEnd : helper::today())
    ),
    formGroup
    (
        set::label($lang->comment),
        editor(set::name('comment'), set::rows('6'))
    )
);
hr();
history();
/* ====== Render page ====== */
render();
