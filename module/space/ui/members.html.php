<?php
declare(strict_types=1);
/**
 * The members view file of space module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     space
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('noticeLang', $lang->space->notice);
jsVar('managerLang', $lang->space->manager);

dropmenu
(
    set::module('space'),
    set::tab('space'),
    set::objectID($spaceID),
    set::url(createLink('space', 'ajaxGetDropMenu', "spaceID=$spaceID&module={$app->rawModule}&method={$app->rawMethod}"))
);

featureBar();
toolbar
(
    hasPriv('space', 'manageMembers') ? item(set(array
    (
        'icon'  => 'persons',
        'text'  => $lang->space->manageMembers,
        'class' => "primary create-project-btn",
        'url'   => $this->createLink('space', 'manageMembers', "spaceID={$spaceID}")
    ))) : null
);

if(!empty($space) && $space->auth == 'extend') unset($config->spaceMember->dtable->fieldList['group']);
$data = initTableData($members, $config->spaceMember->dtable->fieldList, $this->space);
dtable
(
    set::fixedLeftWidth('30%'),
    set::cols(array_values($config->spaceMember->dtable->fieldList)),
    set::userMap($users),
    set::emptyTip($lang->noData),
    set::data($data),
    set::onRenderHeaderCell(jsRaw('window.renderHeaderCell')),
    set::onRenderCell(jsRaw('window.renderMemberList'))
);
