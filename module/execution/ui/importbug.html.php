<?php
declare(strict_types=1);
/**
 * The task import file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao<caoyanyi@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */

namespace zin;

featureBar
(
    set::current('all'),
    set::linkParams("executionID={$execution->id}"),
    isInModal() ? null : li(searchToggle(set::module('importBug')))
);

$footToolbar['items'][] = array(
    'text'      => $lang->import,
    'className' => 'btn secondary import-bug-btn size-sm'
);
if(!isInModal())
{
    $footToolbar['items'][] = array(
        'text'      => $lang->goback,
        'className' => 'btn btn-info size-sm text-gray',
        'url'       => createLink('execution', 'task', "executionID={$execution->id}"),
        'btnType'   => 'info'
    );
}

$config->execution->importBug->dtable->fieldList['assignedTo']['controlItems'] = $users;
formBase
(
    setID('importForm'),
    set::action(createLink('execution', 'importBug', "executionID={$execution->id}&browseType={$browseType}&param={$param}")),
    set::actions(array()),
    dtable
    (
        set::userMap($users),
        set::cols($config->execution->importBug->dtable->fieldList),
        set::data($bugs),
        set::checkable(true),
        set::showToolbarOnChecked(false),
        set::plugins(array('form')),
        set::footToolbar($footToolbar),
        set::footPager(
            usePager(array('linkCreator' => helper::createLink('execution', 'importBug', "executionID={$execution->id}&browseType={$browseType}&param=$param&recTotal={$pager->recTotal}&recPerPage={recPerPage}&page={page}")))
        )
    )
);
