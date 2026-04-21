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
include 'plan.header.html.php';
$canUnlink = hasPriv('codescan', 'unlinksolution');
$canView   = hasPriv('codescan', 'solutionview');
$notCN     = common::checkNotCN();

$setItems     = array();
$childActions = array();
if(!empty($solutionList))
{
    foreach($solutionList as $solution)
    {
        if($canUnlink) $childActions = array(array(
            'icon'         => 'unlink',
            'url'          => inLink('unlinkSolution', "serviceRepoID={$serviceRepoID}&planID={$planID}&setID={$solution->id}"),
            'text'         => $lang->codescan->unlinkSolution,
            'innerClass'   => 'ajax-submit',
            'data-confirm' => $lang->codescan->notice->confirmUnlinkSolution
        ));

        $count       = 0;
        $langAvatars = array();
        $langCount   = empty($solution->langs) ? 0 : count($solution->langs);
        if(!empty($solution->langs))
        {
            foreach($solution->langs as $language)
            {
                if($count > 3) break;

                $langAvatars[] = avatar(set::text($language), set::size('sm'));
                $count ++;
            }
        }

        $setItems[] = div
            (
                setClass('pb-4 pr-4 h-36 w-1/3'),
                col
                (
                    setClass('setting-box cursor-pointer border border-hover rounded-md px-2 py-1 h-full', $canView ? 'open-url' : null),
                    set('data-id', $solution->id),
                    $canView ? set('data-url', inLink('solutionview', "setID={$solution->id}")) : null,
                    div
                    (
                        setClass('flex justify-between items-center'),
                        div
                        (
                            setClass('clip font-bold text-md my-2'),
                            set::title($solution->name),
                            $solution->name
                        ),
                        $canUnlink ? dropdown
                        (
                            setClass('size-sm ghost w-4 flex-none'),
                            btn
                            (
                                on::click()->prevent(),
                                setClass('btn dropdown-toggle ghost'),
                                set::icon('ellipsis-v'),
                                set::caret(false)
                            ),
                            set::items($childActions)
                        ) : null
                    ),
                    div
                    (
                        setClass('h-12 mb-2'),
                        set::title($notCN && !empty($solution->descEn) ? $solution->descEn : $solution->desc),
                        p
                        (
                            setClass('line-clamp-2'),
                            html($notCN && !empty($solution->descEn) ? $solution->descEn : $solution->desc)
                        )
                    ),
                    empty($solution->langs) ? null : div
                    (
                        setClass('flex items-center'),
                        set::title(implode(',', $solution->langs)),
                        cell(set::className('flex items-center mr-2'), $langAvatars),
                        $langCount > 4 ? label(setClass('label rounded-full mr-1'), '+' . ($langCount - 4)) : null,
                        $langCount > 1 ? cell(sprintf($lang->codescan->countLanguages, $langCount)) : zget($langList, $solution->langs[0])
                    )
                )
            );
    }
}

empty($setItems) ? null : $sections = div
(
    setClass('flex flex-wrap solution-block'),
    $setItems
);

div
(
    setID('planMenu'),
    setClass('flex justify-between'),
    $headers
);
detailBody
(
    sectionList
    (
        setID('solution'),
        setClass('border-r'),
        section
        (
            set::title($lang->codescan->solution),
            empty($sections) ? div(setClass('text-center'), $lang->noData) : $sections
        )
    ),
    detailSide
    (
        tabs
        (
            setID('basicInfo'),
            set::collapse(true),
            tabPane
            (
                set::key('basicInfo'),
                set::active(true),
                set::title($lang->basicInfo),
                tableData
                (
                    item
                    (
                        set::name($lang->codescan->name),
                        $plan->name
                    ),
                    item
                    (
                        set::name($lang->codescan->repo),
                        zget($repoList, $plan->repoID)
                    ),
                    item
                    (
                        set::name($lang->codescan->branch),
                        html
                        (
                            p
                            (
                                setClass('w-48 line-clamp-3'),
                                set::title(empty($plan->scanBranch) ? '' : $plan->scanBranch),
                                empty($plan->scanBranch) ? '' : $plan->scanBranch
                            )
                        )
                    ),
                    item
                    (
                        set::name($lang->codescan->branchReg),
                        html(p(setClass('w-48 line-clamp-3'), set::title(empty($plan->branchReg) ? '' : $plan->branchReg), empty($plan->branchReg) ? '' : zget($plan, 'branchReg', '')))
                    ),
                    item
                    (
                        set::name($lang->codescan->scope),
                        zget($lang->codescan->scopeList, $plan->scanType)
                    ),
                    item
                    (
                        set::name($lang->codescan->latestScanTime),
                        $plan->latestScanTime
                    ),
                    item
                    (
                        set::name($lang->codescan->latestExecStatus),
                        empty($plan->latestExecStatus) ? '' : zget($lang->codescan->latestExecStatusList, $plan->latestExecStatus)
                    ),
                    item
                    (
                        set::name($lang->codescan->latestScanResult),
                        empty($plan->latestExecResult) ? '' : zget($lang->codescan->latestScanResultList, $plan->latestExecResult)
                    )
                )
            )
        )
    ),
    $actions ? history
    (
        set::objectType('codescanplan'),
        set::objectID($planID),
        set::commentUrl(createLink('action', 'comment', array('objectType' => 'codescanplan', 'objectID' => $planID)))
    ) : null
);
