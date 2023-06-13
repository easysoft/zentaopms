<?php
declare(strict_types=1);
/**
 * The testcase view file of user module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     user
 * @link        https://www.zentao.net
 */
namespace zin;
include './featurebar.html.php';

$that = zget($lang->user->thirdPerson, $user->gender);
$testcaseNavs['case2Him']  = array('text' => sprintf($lang->user->case2Him, $that),  'url' => inlink('testcase', "userID={$user->id}&type=case2Him"));
$testcaseNavs['caseByHim'] = array('text' => sprintf($lang->user->caseByHim, $that), 'url' => inlink('testcase', "userID={$user->id}&type=caseByHim"));
if(isset($testcaseNavs[$type])) $testcaseNavs[$type]['active'] = true;

$cols = array();
foreach($config->user->defaultFields['testcase'] as $field) $cols[$field] = $config->testcase->dtable->fieldList[$field];
$cols['id']['checkbox']        = false;
$cols['title']['nestedToggle'] = false;
$cols['status']['statusMap']['changed'] = $lang->story->changed;;

foreach($cases as $case)
{
    if($type == 'case2Him') $case->id = $case->case;
    if((isset($case->fromCaseVersion) && $case->fromCaseVersion > $case->version) || $case->needconfirm) $case->status = 'changed';
}

panel
(
    setClass('list'),
    set::title(null),
    set::headingActions(array(nav(set::items($testcaseNavs)))),
    dtable
    (
        set::userMap($users),
        set::bordered(true),
        set::cols($cols),
        set::data(array_values($cases)),
        set::onRenderCell(jsRaw('window.renderCell')),
        set::footPager(usePager()),
    )
);

render();
