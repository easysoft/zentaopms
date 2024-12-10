<?php
declare(strict_types=1);
/**
 * The treestory view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

/* Get min stage. */
$minStage    = $story->stage;
$stageList   = implode(',', array_keys($this->lang->story->stageList));
$minStagePos = strpos($stageList, $minStage);
if($story->stages and $branches)
{
    foreach($story->stages as $branch => $stage)
    {
        if(strpos($stageList, $stage) !== false and strpos($stageList, $stage) > $minStagePos)
        {
            $minStage    = $stage;
            $minStagePos = strpos($stageList, $stage);
        }
    }
}

$reviewed   = $lang->noData;
$reviewedBy = explode(',', $story->reviewedBy);
if(count($reviewedBy) > 1)
{
    $reviewed = '';
    foreach($reviewedBy as $account) $reviewed .= ' ' . zget($users, trim($account));
}

$moduleTitle = '';
$moduleItems = array();
if(empty($modulePath))
{
    $moduleTitle .= '/';
    $moduleItems[] = '/';
}
else
{
    foreach($modulePath as $key => $module)
    {
        $moduleTitle .= $module->name;
        if(!common::hasPriv('product', 'browse'))
        {
            $moduleItems[] = $module->name;
        }
        else
        {
            $arrow = '';
            if(isset($modulePath[$key + 1]))
            {
                $moduleTitle .= '/';
                $arrow        = $lang->arrow;
            }

            $moduleItems[] = a
            (
                setClass('text-primary'),
                set::href(createLink('product', 'browse', "productID={$story->product}&branch={$story->branch}&browseType=byModule&param={$module->id}")),
                html($module->name . $arrow)
            );
        }
    }
}

$mailtoList = array();
$mailto     = explode(',', $story->mailto);
if(empty($mailto))
{
    $mailtoList[] = $lang->noData;
}
else
{
    foreach($mailto as $account)
    {
        $mailtoList[] = span
            (
                setClass('mr-1'),
                zget($users, trim($account))
            );
    }
}

div
(
    setClass('section-list', 'canvas', 'pt-4', 'pb-6', 'px-4', 'mb-4'),
    div
    (
        setClass('flex items-center flex-nowrap mb-4'),
        label
        (
            setClass('flex-none rounded-full gray-outline'),
            $story->id
        ),
        span
        (
            setClass('mx-2 text-lg font-bold clip'),
            $story->title
        ),
        label
        (
            setClass('flex-none rounded-full story-status status-' . $story->status),
            $this->processStatus('story', $story)
        )
    ),
    div
    (
        setClass('flex items-center flex-nowrap mb-4'),
        div
        (
            setClass('flex-1'),
            $lang->story->stage,
            span
            (
                setClass('ml-2 font-bold'),
                $lang->story->stageList[$story->stage]
            )
        ),
        div
        (
            setClass('flex-1'),
            $lang->story->estimate,
            span
            (
                setClass('ml-2 font-bold'),
                $story->estimate
            )
        )
    ),
    btngroup
    (
        setID('actionButtons'),
        setClass('mb-4 mt-4'),
        hasPriv('story', 'change') ? btn
        (
            setClass('text-primary'),
            set::icon('alter'),
            set::hint($lang->story->operateList['changed']),
            set::url(createLink('story', 'change', array('storyID' => $story->id))),
            set::disabled(!$this->story->isClickable($story, 'change')),
            set('data-app', $app->tab)
        ) : null,
        hasPriv('story', 'delete') ? btn
        (
            setClass('text-primary ml-2 ajax-submit'),
            set::icon('trash'),
            set::hint($lang->story->delete),
            set::url(createLink('story', 'delete', array('storyID' => $story->id))),
            set::disabled(!$this->story->isClickable($story, 'delete')),
            set('data-confirm', $story->type == 'requirement' ? str_replace($lang->SRCommon, $lang->URCommon, $lang->story->confirmDelete) : $lang->story->confirmDelete)
        ) : null,
        hasPriv('story', 'review') ? btn
        (
            setClass('text-primary'),
            set::icon('search'),
            set::hint($lang->story->review),
            set::url(createLink('story', 'review', array('storyID' => $story->id))),
            set::disabled(!$this->story->isClickable($story, 'review')),
            set('data-app', $app->tab)
        ) : null,
        hasPriv('story', 'close') ? btn
        (
            setClass('text-primary'),
            set::icon('off'),
            set::hint($lang->story->close),
            set::url(createLink('story', 'close', array('storyID' => $story->id))),
            set::disabled(!$this->story->isClickable($story, 'close')),
            set('data-toggle', 'modal')
        ) : null,
        hasPriv('story', 'edit') ? btn
        (
            setClass('text-primary ml-2'),
            set::icon('edit'),
            set::hint($lang->story->edit),
            set::url(createLink('story', 'edit', array('storyID' => $story->id))),
            set::disabled(!$this->story->isClickable($story, 'edit')),
            set('data-app', $app->tab)
        ) : null,
        $story->isParent == '0' && hasPriv('testcase', 'create') ? btn
        (
            setClass('text-primary ml-2'),
            set::icon('sitemap'),
            set::hint($lang->testcase->create),
            set::url(createLink('testcase', 'create', "productID={$story->product}&branch={$story->branch}&module=0&from=execution&param={$executionID}&storyID={$story->id}")),
            set::disabled($story->type != 'story'),
            set('data-app', $app->tab)
        ) : null
    ),
    hr(),
    section
    (
        set::title($lang->story->legendSpec),
        set::content(empty($story->spec) ? $lang->noData : $story->spec),
        set::useHtml(true)
    ),
    hr(),
    section
    (
        set::title($lang->story->legendVerify),
        set::content(empty($story->verify) ? $lang->noData : $story->verify),
        set::useHtml(true)
    ),
    h::hr(set::className('mt-5 mb-1')),
    fileList
    (
        set::padding(false),
        set::files($story->files)
    ),
    hr(),
    section
    (
        set::title($lang->story->legendBasicInfo),
        tableData
        (
            $product->shadow ? null : item
            (
                set::name($lang->story->product),
                common::hasPriv('product', 'view') ? a(set::href($this->createLink('product', 'view', "productID=$story->product")), $product->name) : $product->name
            ),
            $product->type == 'normal' ? null : item
            (
                set::name($lang->story->branch),
                common::hasPriv('product', 'view') ? a(set::href($this->createLink('product', 'view', "productID=$story->product&branch=$story->branch")), $branches[$story->branch]) : $branches[$story->branch],
                a(set::href($this->createLink('product', 'view', "productID=$story->product")), $product->name)
            ),
            item
            (
                set::name($lang->story->module),
                set::title($moduleTitle),
                $moduleItems
            ),
            ($story->type != 'requirement' and $story->parent != -1) ? item
            (
                set::trClass('plan-line'),
                set::name($lang->story->plan),
                empty($story->planTitle) ? null : array_values(array_map(function($planID, $planTitle)
                {
                    $items   = array();
                    $items[] = common::hasPriv('productplan', 'view') ? a(set::href(helper::createLink('productplan', 'view', "planID={$planID}")), $planTitle) : $planTitle;
                    $items[] = h::br();
                    return $items;
                }, array_keys($story->planTitle), array_values($story->planTitle)))
            ) : null,
            item
            (
                set::id('source'),
                set::name($lang->story->source),
                zget($lang->story->sourceList, $story->source, '')
            ),
            item
            (
                set::id('sourceNoteBox'),
                set::name($lang->story->sourceNote),
                $story->sourceNote
            ),
            item
            (
                set::name($lang->story->status),
                span
                (
                    setClass("status-{$story->status}"),
                    $this->processStatus('story', $story)
                )
            ),
            $story->type == 'requirement' ? null : item
            (
                set::trClass('stage-line'),
                set::name($lang->story->stage),
                zget($lang->story->stageList, $minStage, '')
            ),
            item
            (
                set::name($lang->story->category),
                zget($lang->story->categoryList, $story->category)
            ),
            item
            (
                set::name($lang->story->pri),
                priLabel($story->pri, set::text($lang->story->priList))
            ),
            item
            (
                set::name($lang->story->estimate),
                $story->estimate . $config->hourUnit
            ),
            in_array($story->source, $config->story->feedbackSource) ? item
            (
                set::name($lang->story->feedbackBy),
                $story->feedbackBy
            ) : null,
            in_array($story->source, $config->story->feedbackSource) ? item
            (
                set::name($lang->story->notifyEmail),
                $story->notifyEmail
            ) : null,
            item
            (
                set::name($lang->story->keywords),
                $story->keywords
            ),
            item
            (
                set::name($lang->story->legendMailto),
                $mailtoList
            )
        ),
        hr(),
        section
        (
            set::title($lang->story->legendRelated),
            tableData
            (
                set::useTable(false),
                $story->type == 'story' ? item
                (
                    set::collapse(true),
                    set::name($lang->story->legendBugs),
                    empty($bugs) ? null : h::ul
                    (
                        array_values(array_map(function($bug) use($lang)
                        {
                            return h::li
                            (
                                set::title($bug->title),
                                label(setClass('circle size-sm'), $bug->id),
                                common::hasPriv('bug', 'view') ? a(set::href(helper::createLink('bug', 'view', "bugID=$bug->id")), setClass('title'), set('data-toggle', 'modal'), set::title($bug->title), $bug->title) : span(setClass('title'), $bug->title),
                                label(setClass("status-{$bug->status} size-sm ml-1"), $lang->bug->statusList[$bug->status])
                            );
                        }, $bugs))
                    )
                ) : null,
                $story->type == 'story' ? item
                (
                    set::collapse(true),
                    set::name($lang->story->legendCases),
                    empty($cases) ? null : h::ul
                    (
                        array_values(array_map(function($case)
                        {
                            return h::li
                                (
                                    set::title($case->title),
                                    label(setClass('circle size-sm'), $case->id),
                                    common::hasPriv('testcase', 'view') ? a(set::href(helper::createLink('testcase', 'view', "caseID=$case->id")), setClass('title'), set('data-toggle', 'modal'), set::title($case->title), $case->title) : span(setClass('title'), $case->title)
                                );
                        }, $cases))
                    )
                ) : null,
                item
                (
                    set::collapse(true),
                    set::name($lang->story->linkStories),
                    empty($story->linkStoryTitles) ? null : h::ul
                    (
                        array_values(array_map(function($storyID, $storyTitle) use($storyProducts, $story)
                        {
                            global $app;
                            $hasPriv = ($app->user->admin || str_contains(",{$app->user->view->products},", ",{$storyProducts[$storyID]},"));
                            return h::li
                                (
                                    set::title($storyTitle),
                                    label(setClass('circle size-sm'), $storyID),
                                    $hasPriv ? a(set::href(helper::createLink('story', 'view', "storyID=$storyID&version=0&param=0&storyType=$story->type")), setClass('title'), set('data-toggle', 'modal'), set::title($storyTitle), $storyTitle) : span(setClass('title'), $storyTitle)
                                );
                        }, array_keys($story->linkStoryTitles), array_values($story->linkStoryTitles)))
                    )
                )
            )
        ),
        hr(),
        section
        (
            set::title($lang->story->legendLifeTime),
            tableData
            (
                item
                (
                    set::name($lang->story->openedBy),
                    zget($users, $story->openedBy) . $lang->at . $story->openedDate
                ),
                item
                (
                    set::name($lang->story->assignedTo),
                    $story->assignedTo ? zget($users, $story->assignedTo) . $lang->at . $story->assignedDate : $lang->noData
                ),
                item
                (
                    set::name($lang->story->reviewedBy),
                    $reviewed
                ),
                item
                (
                    set::name($lang->story->reviewedDate),
                    $story->reviewedBy ? $story->reviewedDate : $lang->noData
                ),
                item
                (
                    set::name($lang->story->closedBy),
                    $story->closedBy ? zget($users, $story->closedBy) . $lang->at . $story->closedDate : $lang->noData
                ),
                item
                (
                    set::tdClass('resolution'),
                    set::name($lang->story->closedReason),
                    $story->closedReason ? zget($lang->story->reasonList, $story->closedReason) : $lang->noData,
                    isset($story->extraStories[$story->duplicateStory]) ? a(set::href(inlink('view', "storyID=$story->duplicateStory")), set::title($story->extraStories[$story->duplicateStory]), "#{$story->duplicateStory} {$story->extraStories[$story->duplicateStory]}") : null
                ),
                item
                (
                    set::name($lang->story->lastEditedBy),
                    $story->lastEditedBy ? zget($users, $story->lastEditedBy) . $lang->at . $story->lastEditedDate : $lang->noData
                )
            )
        )
    )
);

history
(
    set::objectType('story'),
    set::objectID($story->id),
    set::commentBtn(true),
    set::commentUrl(createLink('action', 'comment', array('objectType' => 'story', 'objectID' => $story->id)))
);
