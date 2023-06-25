<?php
declare(strict_types=1);
/**
* The UI file of mail module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      chen.tao <chentao@easycorp.ltd>
* @package     mail
* @link        https://www.zentao.net
*/

namespace zin;

$canBatchDelete = hasPriv('mail', 'batchDelete');

$cols = $this->loadModel('datatable')->getSetting('mail');

$queueList = initTableData($queueList, $cols);
foreach($queueList as &$queue)
{
    foreach($queue->actions as $idx => $action)
    {
        if(!empty($action['disabled'])) unset($queue->actions[$idx]);
    }
}

panel
(
    set::title($lang->mail->browse),
    dtable
    (
        set::cols($cols),
        set::data($queueList),
        set::userMap($users),
        set::customCols(true),
        set::checkable($canBatchDelete),
        $canBatchDelete ? set::footToolbar(array
        (
            'type'  => 'btn-group',
            'items' => array(array
            (
                'text'    => $lang->delete,
                'btnType' => 'secondary',
                'url'     => createLink('mail', 'batchDelete'),
                'onClick' => jsRaw('window.onClickBatchDelete')
            ))
        )) : null,
        set::footPager(usePager())
    )
);

jsVar('confirmDeleteTip', $lang->mail->confirmDelete);

render();
