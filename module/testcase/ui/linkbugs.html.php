<?php
declare(strict_types=1);
/**
 * The link bugs view file of testcase module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     testcase
 * @link        https://www.zentao.net
 */
namespace zin;

$footToolbar = array('items' => array(array('text' => $lang->save, 'btnType' => 'secondary', 'className' => 'link-btn')));

modalHeader
(
    set::title($lang->testcase->linkBugs),
    set::entityText($case->title),
    set::entityID($case->id)
);

searchForm
(
    set::module('bug'),
    set::simple(true),
    set::show(true)
);

dtable
(
    set::cols($config->testcase->linkbugs->dtable->fieldList),
    set::data($bugs2Link),
    set::userMap($users),
    set::checkable(true),
    set::footToolbar($footToolbar),
    set::footPager(usePager())
);
