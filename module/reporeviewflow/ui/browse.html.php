<?php
declare(strict_types=1);
/**
 * The browse review flow view file of reporeviewflow module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     reporeviewflow
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('branchTypePairs', array(0 => $lang->all) + $branchTypePairs);
$canCreate = hasPriv('reporeviewflow', 'create');

$module = $app->tab == 'devops' ? 'repo' : $app->tab;
dropmenu
(
    set::module($module),
    set::tab($module),
    set::url(createLink($module, 'ajaxGetDropMenu', "objectID={$repoID}&module={$app->rawModule}&method={$app->rawMethod}"))
);
featureBar();
if($canCreate)
{
    toolBar
    (
        set::items(array(
            array
            (
                'icon'  => 'plus',
                'class' => 'btn primary',
                'text'  => $lang->reporeviewflow->create,
                'url'   => createLink('reporeviewflow', 'create', "repoID=$repoID")
            )
        ))
    );
}
jsVar('deleteConfirm', $lang->reporeviewflow->notice->deleteReviewFlow);

$data = initTableData($flowList, $config->reporeviewflow->dtable->fieldList);
dtable
(
    set::cols($config->reporeviewflow->dtable->fieldList),
    set::data($data),
    set::onRenderCell(jsRaw('window.renderReviewFlowCell')),
    set::footPager(usePager('pager'))
);
