<?php
declare(strict_types=1);
/**
 * The planview file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;
$isModal = isInModal();
$isModal ? detailHeader(to::prefix(entityLabel(set(array('level' => 2, 'text' => $lang->codescan->trigger))))) : include 'plan.header.html.php';

if($isModal)
{
    unset($config->codescan->trigger->dtable->fieldList['latestScanTime']);
    unset($config->codescan->trigger->dtable->fieldList['latestExecStatus']);
    unset($config->codescan->trigger->dtable->fieldList['result']);
    unset($config->codescan->trigger->dtable->fieldList['actions']);
}
else
{
    unset($config->codescan->trigger->dtable->fieldList['latestScan']);

    $config->codescan->trigger->dtable->fieldList['actions']['list']['editTrigger']['url']['params']   = "triggerID={id}&planID={plan}&serviceRepoID={$serviceRepoID}";
    $config->codescan->trigger->dtable->fieldList['actions']['list']['deleteTrigger']['url']['params'] = "triggerID={id}&planID={plan}&serviceRepoID={$serviceRepoID}";
}
$triggerList = initTableData($triggers, $config->codescan->trigger->dtable->fieldList);

panel
(
    $isModal ? null : div
    (
        setID('planMenu'),
        setClass('flex justify-between'),
        $headers,
        hasPriv('codescan', 'createTrigger') ? btn
        (
            set::icon('plus'),
            set::type('primary'),
            set::text($lang->codescan->createTrigger),
            setClass('link mr-actions flex-none'),
            setData('toggle', 'modal'),
            set::url(inLink('createTrigger', "planID={$planID}&serviceRepoID={$serviceRepoID}")
        )) : null
    ),
    div
    (
        set::bodyClass('flex'),
        div
        (
            setClass('w-full flex-auto'),
            dtable
            (
                set::sortLink(createLink('codescan', 'planview', "serviceRepoID={$serviceRepoID}&planID={$planID}&repoID={$repoID}&type={$type}&orderBy={name}_{sortType}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}")),
                set::orderBy($orderBy),
                set::cols($config->codescan->trigger->dtable->fieldList),
                set::data($triggerList),
                set::loadPartial(true),
                set::extraHeight('+144'),
                set::footPager(usePager())
            )
        )
    )
);
