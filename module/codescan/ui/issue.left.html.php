<?php
declare(strict_types=1);
/**
 * The issue view file of codescan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@chandao.com>
 * @package     codescan
 * @link        https://www.zentao.net
 */
namespace zin;
$taskItems = array(array('text' => $lang->codescan->allTask, 'value' => 0));
if(!empty($taskList))
{
    foreach($taskList as $id => $taskName)
    {
         $item = array('text' => $taskName, 'value' => $id);
         $item['content'] = array('html' => "<label class='label bg-primary-50 text-primary mr-2 flex-none'>{$id}</label><div class='flex clip'>{$taskName}</div>", 'class' => 'w-full flex nowrap');

         $taskItems[] = $item;
    }

    $taskItems = array_slice($taskItems, 0, 11);
}

$selected     = !empty($params['branch']) ? (string)$this->cookie->issueFile : (!empty($ruleName) ? $ruleName : $lang->all);
$selectTaskID = isset($params['taskID']) ? $params['taskID'] : 0;
$isRule       = strpos($extras, 'ruleID') !== false;

sidebar
(
    set::maxWidth(800),
    setID('moduleMenu'),
    setClass('module-menu shadow ring rounded bg-canvas col relative'),
    div
    (
        h::header
        (
            setClass('module-menu-header h-10 flex items-center flex-none gap-3 is-fixed rounded rounded-r-none justify-between'),
            picker
            (
                setClass('w-1/2 flex-none'),
                set::name('scanTask'),
                set::required(true),
                set::popWidth('auto'),
                set::items($taskItems),
                set::value(0),
                on::change()->call('changeIssueTask')
            ),
            div
            (
                setClass('pl-2 canvas flex-auto h-full clip flex justify-between items-center'),
                span
                (
                    setClass('module-title text-lg font-semibold clip'),
                    set::title($selected),
                    $selected
                ),
                $selected == $lang->all ? null : btn
                (
                    setClass('btn-close rounded-full'),
                    set::icon('close'),
                    set::url(inLink('issue', "repoID={$repoID}&taskID=0&serviceRepoID={$serviceRepoID}&type={$browseType}&queryID=0&severity={$severity}&extras=taskID={$selectTaskID}" . ($isRule ? ',ruleID=all' : ''))),
                    set::size('sm'),
                    set::type('ghost')
                )
            )
        )
    ),
    div
    (
        setClass('overflow-scroll scrollbar-hover overflow-x-hidden pb-8'),
        empty($fileTree) ? null : div
        (
            setClass('issue-tree-file', empty($params['ruleID']) ? '' : 'hidden'),
            treeEditor
            (
                set::defaultNestedShow(true),
                set::collapsedIcon('icon-folder-o'),
                set::expandedIcon('icon-folder-open-o'),
                set::normalIcon('icon-doc'),
                set::onClickItem(jsRaw('window.issueTreeClick')),
                set::selected(empty($params['branch']) ? '' : ($params['branch'] . str_replace(array('/', '-', '.'), '', (string)$this->cookie->issueFile))),
                set::hover(true),
                set::lines(true),
                set::items($fileTree)
            )
        ),
        empty($ruleTree) ? null : div
        (
            setClass('issue-tree-rule', !empty($params['ruleID']) ? '' : 'hidden'),
            treeEditor
            (
                set::defaultNestedShow(true),
                set::selected(empty($params['ruleID']) ? '' : $params['ruleID']),
                set::hover(true),
                set::lines(true),
                set::items($ruleTree)
            )
        )
    ),
    row
    (
        setClass('justify-end p-2 flex-none canvas absolute bottom-0 right-0 z-0 w-full'),
        btn
        (
            set::type('surface tree-type-btn'),
            setClass(empty($params['ruleID']) ? 'primary' : ''),
            setData('type', 'file'),
            set::size('sm'),
            set::text($lang->codescan->catalog),
            set::url(inLink('issue', "repoID={$repoID}&taskID=0&serviceRepoID={$serviceRepoID}&type={$browseType}&queryID=0&severity={$severity}&extras=taskID={$selectTaskID}"))
        ),
        btn
        (
            set::type('surface mx-2 tree-type-btn'),
            setClass(!empty($params['ruleID']) ? 'primary' : ''),
            setData('type', 'rule'),
            set::size('sm'),
            set::text($lang->codescan->rule),
            set::url(inLink('issue', "repoID={$repoID}&taskID=0&serviceRepoID={$serviceRepoID}&type={$browseType}&queryID=0&severity={$severity}&extras=taskID={$selectTaskID},ruleID=all"))
        )
    ),
    on::init()->do('$("#mainContainer").addClass("has-module-menu-header")')
);
