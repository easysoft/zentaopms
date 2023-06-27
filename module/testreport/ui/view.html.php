<?php
declare(strict_types=1);
/**
 * The view file of testreport module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     testreport
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('goalTip',         $lang->testreport->goalTip);
jsVar('foundBugTip',     $lang->testreport->foundBugTip);
jsVar('legacyBugTip',    $lang->testreport->legacyBugTip);
jsVar('activatedBugTip', $lang->testreport->activatedBugTip);
jsVar('fromCaseBugTip',  $lang->testreport->fromCaseBugTip);

detailHeader
(
    to::title
    (
        entityLabel
        (
            set::entityID($report->id),
            set::level(1),
            set::text($report->title)
        )
    )
);

$members = '';
foreach(explode(',', $report->members) as $member) $members .= zget($users, $member) . ' ';

div
(
    set::class('detail-body rounded flex gap-1'),
    div
    (
        set::class('col gap-1 grow'),
        tabs
        (
            tabPane
            (
                set::key('basic'),
                set::title($lang->testreport->view),
                set::active(true),
                sectionList
                (
                    section
                    (
                        set::title($lang->testreport->legendBasic),
                        set::useHtml(true),
                        tableData
                        (
                            item(set::name($lang->testreport->startEnd), $report->begin . ' ~ ' . $report->end),
                            item(set::name($lang->testreport->owner), zget($users, $report->owner)),
                            item(set::name($lang->testreport->members), $members),
                            !empty($execution->desc) ? item
                            (
                                set::name($lang->testreport->goal),
                                icon
                                (
                                    'help',
                                    set('data-toggle', 'tooltip'),
                                    set('id', 'goalTip'),
                                    set('class', 'text-light')
                                ),
                                $execution->desc
                            ) : null,
                            item
                            (
                                set::name($lang->testreport->profile),
                                div
                                (
                                    div(html($storySummary)),
                                    div(html(sprintf($lang->testreport->buildSummary, empty($builds) ? 1 : count($builds)) . $caseSummary)),
                                    div(html(sprintf($lang->testreport->bugSummary, $bugSummary['foundBugs'], count($legacyBugs), $bugSummary['activatedBugs'],  $bugSummary['countBugByTask'], $bugSummary['bugConfirmedRate'] . '%', $bugSummary['bugCreateByCaseRate'] . '%')))
                                )
                            ),
                            item(set::name($lang->testreport->legendComment), empty($report->report) ? $lang->testreport->none : $report->report),
                        )
                    ),
                    $report->files ? section
                    (
                        set::title($lang->files),
                        fileList
                        (
                            set::files($report->files),
                            set::fieldset(false)
                        )
                    ) : null,
                    history()
                )
            ),
            tabPane
            (
                set::key('storyAndBug'),
                set::title($lang->testreport->legendStoryAndBug),
                tableData
                (
                )
            ),
            tabPane
            (
                set::key('tabBuild'),
                set::title($lang->testreport->legendBuild),
                tableData
                (
                )
            ),
            tabPane
            (
                set::key('tabCase'),
                set::title($lang->testreport->legendCase),
                tableData
                (
                )
            ),
            tabPane
            (
                set::key('tabLegacyBugs'),
                set::title($lang->testreport->legendLegacyBugs),
                tableData
                (
                )
            ),
            tabPane
            (
                set::key('tabReport'),
                set::title($lang->testreport->legendReport),
                tableData
                (
                )
            ),
            tabPane
            (
                set::key('legendMore'),
                set::title($lang->testreport->tabMore),
                tableData
                (
                )
            )
        )
    )
);

render();
