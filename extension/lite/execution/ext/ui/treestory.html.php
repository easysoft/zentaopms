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
    foreach($reviewedBy as $account) $reviewed .= ' ' . zget($users, trim($account));
}

div
(
    setClass('section-list', 'canvas', 'pt-4', 'pb-6', 'px-4', 'mb-4'),
    div
    (
        setClass('flex items-center flex-nowrap mb-4'),
        label
        (
            setClass('flex-none rounded-full dark-outline'),
            $story->id
        ),
        label
        (
            setClass('mx-2 flex-none rounded-full status-' . $story->status),
            $this->processStatus('story', $story)
        ),
        span
        (
            setClass('text-md font-bold clip'),
            $story->title
        )
    ),
    div
    (
        div
        (
            $lang->story->stage,
            span
            (
                setClass('ml-2 font-bold'),
                $lang->story->stageList[$story->stage]
            )
        ),
        div
        (
            setClass('mt-4'),
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
            setClass('text-primary ml-2'),
            set::icon('search'),
            set::hint($lang->story->review),
            set::url(createLink('story', 'review', array('storyID' => $story->id))),
            set::disabled(!$this->story->isClickable($story, 'review')),
            set('data-app', $app->tab)
        ) : null,
        hasPriv('story', 'close') ? btn
        (
            setClass('text-primary ml-2'),
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
        ) : null
    ),
    hr(),
    section
    (
        set::title($lang->story->legendSpec),
        set::content(empty($story->spec) ? $lang->noData : $story->spec),
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
            item
            (
                set::name($lang->story->module),
                set::title($moduleTitle),
                $moduleItems
            ),
            item
            (
                set::name($lang->story->status),
                span
                (
                    setClass("status-story status-{$story->status}"),
                    $this->processStatus('story', $story)
                )
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
    set::commentBtn(true),
    set::commentUrl(createLink('action', 'comment', array('objectType' => 'story', 'objectID' => $story->id)))
);
