<?php
declare(strict_types=1);
/**
 * The mydoclist view file of doc module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     doc
 * @link        https://www.zentao.net
 */
namespace zin;

$docContent = null;
if(empty($docs))
{
    $emptyCreateBtn = isset($buildCreateBtn) && is_callable($buildCreateBtn) ? $buildCreateBtn($type, $objectID, $lib, $moduleID, $templateParam, $buttonItems) : null;
    $docContent = div
    (
        setClass('table-empty-tip flex justify-center items-center'),
        span
        (
            setClass('text-gray'),
            $lang->doc->noDoc
        ),
        $browseType != 'bysearch' && $libID && common::hasPriv('doc', 'create') ? $emptyCreateBtn : null
    );
}
else
{
    jsVar('iconList', $config->doc->iconList);
    jsVar('draftText', $lang->doc->draft);
    jsVar('canViewDoc', common::hasPriv('doc', 'view'));
    jsVar('canCollect', common::hasPriv('doc', 'collect') && $libType && $libType != 'api');
    jsVar('currentAccount', $app->user->account);
    jsVar('spaceMethodList', $config->doc->spaceMethod);
    jsVar('myspacePriv', common::hasPriv('doc', 'myspace'));
    jsVar('productspacePriv', common::hasPriv('doc', 'productspace'));
    jsVar('projectspacePriv', common::hasPriv('doc', 'projectspace'));
    jsVar('teamspacePriv', common::hasPriv('doc', 'teamspace'));
    jsVar('currentTab', $app->tab);

    $cols = array();
    foreach($config->doc->dtable->fieldList as $colName => $col)
    {
        if($type == 'mine' && in_array($colName, array('objectName', 'module', 'editedBy'))) continue;
        if($colName == 'addedBy' && in_array($type, array('mine', 'createdby'))) continue;

        if($canExport && $colName == 'id') $col['type'] = 'checkID';
        $cols[$colName] = $col;
    }

    $tableData = initTableData($docs, $cols);

    $params = "libID={$libID}&moduleID={$moduleID}&browseType={$browseType}&param={$param}&orderBy={$orderBy}&recTotal={recTotal}&recPerPage={recPerPage}&pageID={page}";
    if($app->rawMethod == 'myspace') $params = "type={$type}&" . $params;
    $docContent = dtable
    (
        set::userMap($users),
        set::cols($cols),
        set::data($tableData),
        set::checkable($canExport),
        set::onRenderCell(jsRaw('window.rendDocCell')),
        set::footPager(
            usePager
            (
                array('linkCreator' => helper::createLink('doc', $app->rawMethod, $params)),
            ),
        ),
    );
}
