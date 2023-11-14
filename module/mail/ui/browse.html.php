<?php
declare(strict_types=1);
/**
* The browse file of mail module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      chen.tao <chentao@easycorp.ltd>
* @package     mail
* @link        https://www.zentao.net
*/
namespace zin;

$canBatchDelete = hasPriv('mail', 'batchDelete');
$queueList      = initTableData($queueList, $config->mail->browse->dtable->fieldList);

/* ZIN: layout. */
panel
(
    set::title($lang->mail->browse),
    dtable
    (
        set::cols($config->mail->browse->dtable->fieldList),
        set::data($queueList),
        set::userMap($users),
        set::checkable($canBatchDelete),
        set::onRenderCell(jsRaw('window.renderCell')),
        $canBatchDelete ? set::footToolbar(array
        (
            'type'  => 'btn-group',
            'items' => array(array
            (
                'text'            => $lang->delete,
                'btnType'         => 'secondary',
                'className'       => 'batch-btn',
                'data-on'         => 'click',
                'data-call'       => 'onClickBatchDelete',
                'data-params'     => 'event',
                'data-formaction' => createLink('mail', 'batchDelete'),
                'data-confirm'    => $lang->mail->confirmDelete
            ))
        )) : null,
        set::footPager(usePager())
    )
);
