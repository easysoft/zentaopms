<?php
declare(strict_types=1);
/**
 * The requirement view file of my module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     my
 * @link        https://www.zentao.net
 */
namespace zin;

featureBar
(
    set::current($type),
    set::linkParams("mode=requirement&type={key}&param=$param"),
    li(searchToggle())
);

$stories = initTableData($stories, $config->my->requirement->dtable->fieldList, $this->story);
$cols    = array_values($config->my->requirement->dtable->fieldList);
$data    = array_values($stories);

$footToolbar = array('items' => array
(
    array('text' => $lang->edit, 'className' => 'batch-btn', 'btnType' => 'primary', 'data-url' => helper::createLink('todo', 'batchEdit', "from=myTodo&type=$type&userID={$user->id}&status=$status")),
));

dtable
(
    set::cols($cols),
    set::data($data),
    set::userMap($users),
    set::fixedLeftWidth('44%'),
    set::checkable(true),
    set::footToolbar($footToolbar),
    set::footPager(usePager()),
);

render();
