<?php
declare(strict_types=1);
/**
 * The edit file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang<Yidong@easycorp.ltd>
 * @package     story
 * @link        http://www.zentao.net
 */
namespace zin;

$canEditContent = str_contains(',draft,changing,', ",{$story->status},");
$forceReview    = $this->story->checkForceReview();
$assignedToList = $story->status == 'closed' ? $users + array('closed' => 'Closed') : $users;

$planCount    = !empty($story->planTitle) ? count($story->planTitle) : 0;
$multiplePlan = ($this->session->currentProductType != 'normal' && empty($story->branch) && $planCount > 1);

$minStage    = $story->stage;
$stageList   = implode(',', array_keys($this->lang->story->stageList));
$minStagePos = strpos($stageList, $minStage);
if(!empty($story->stages) and $branchTagOption)
{
    foreach($story->stages as $branch => $stage)
    {
        $position = strpos(",{$stageList},", ",{$stage},");
        if($position !== false and $position > $minStagePos)
        {
            $minStage    = $stage;
            $minStagePos = $position;
        }
    }
}

jsVar('storyType', $story->type);
jsVar('storyID', $story->id);
jsVar('storyStatus', $story->status);
jsVar('lastReviewer', explode(',', $lastReviewer));
jsVar('storyReviewers', $storyReviewers);
jsVar('reviewerNotEmpty', $lang->story->notice->reviewerNotEmpty);
jsVar('oldProductID', $story->product);
jsVar('twins', $story->twins);
jsVar('relievedTwinsTip', $lang->story->relievedTwinsTip);
jsVar('parentStory', !empty($story->children));
jsVar('moveChildrenTips', $lang->story->moveChildrenTips);
jsVar('executionID', isset($objectID) ? $objectID : 0);
jsVar('langTreeManage', $lang->tree->manage);

detailHeader
(
    to::prefix($lang->story->edit),
    to::title
    (
        entityLabel
        (
            set::level(1),
            set::entityID($story->id),
            set::reverse(true),
            $story->title
        )
    )
);

detailBody
(
    set::id('dataform'),
    set::isForm(true),
    set::ajax(array('beforeSubmit' => jsRaw('clickSubmit'))),
    $canEditContent ? set::actions(array
    (
        array('btnType' => 'submit', 'class' => 'primary',   'data-status' => 'active', 'text' => $lang->save),
        array('btnType' => 'submit', 'class' => 'secondary', 'data-status' => 'draft',  'text' => $story->status == 'changing' ? $lang->story->doNotSubmit : $lang->story->saveDraft),
        isInModal() ? null : array('text' => $lang->goback, 'back' => 'APP')
    )) : null,
    sectionList
    (
        section
        (
            set::title($lang->story->title),
            formGroup
            (
                set::name('title'),
                set::value($fields['title']['default']),
                set::disabled(!$canEditContent)
            )
        ),
        $canEditContent ? section
        (
            set::title($lang->story->reviewers),
            formGroup
            (
                row
                (
                    setClass('reviewerRow'),
                    span
                    (
                        setClass('reviewerBox'),
                        setStyle('width', '100%'),
                        picker
                        (
                            set::id('reviewer'),
                            set::name('reviewer[]'),
                            set::items($fields['reviewer']['options']),
                            set::value($fields['reviewer']['default']),
                            set::multiple(true),
                            on::change('changeReviewer')
                        )
                    ),
                    $forceReview ? null : checkbox
                    (
                        set::id('needNotReview'),
                        set::name('needNotReview'),
                        set::checked(empty($fields['reviewer']['default'])),
                        set::value(1),
                        set::text($lang->story->needNotReview),
                        on::change('changeNeedNotReview(e.target)')
                    )
                )
            )
        ) : null,
        section
        (
            set::title($lang->story->legendSpec),
            $canEditContent ? formGroup(editor(set::name('spec'), htmlSpecialString($story->spec))) : set::content($story->spec),
            $canEditContent ? null : set::useHtml(true)
        ),
        empty($twins) ? null : section
        (
            set::title($lang->story->changeSyncTip),
            to::suffix(set::title($lang->story->syncTip), icon('help')),
            h::ul
            (
                array_values(array_map(function($twin) use($story, $branches, $lang)
                {
                    $branch     = isset($branches[$twin->branch]) ? $branches[$twin->branch] : '';
                    $stage      = $lang->story->stageList[$twin->stage];
                    $labelClass = $story->branch == $twin->branch ? 'primary' : '';

                    return h::li
                    (
                        setClass('twins'),
                        $branch ? label(setClass($labelClass . ' circle branch size-sm'), set::title($branch), $branch) : null,
                        label(setClass('circle size-sm'), $twin->id),
                        common::hasPriv('story', 'view') ? a(set::href($this->createLink('story', 'view', "id={$twin->id}")), setClass('title'), set::title($twin->title), set('data-toggle', 'modal'), $twin->title) : span(setClass('title'), $twin->title),
                        label(setClass('size-sm'), set::title($stage), $stage),
                        common::hasPriv('story', 'relieved') ? a(set::title($lang->story->relievedTwins), setClass("relievedTwins unlink size-xs"), on::click('unlinkTwins'), set('data-id', $twin->id), icon('unlink')) : null
                    );
                }, $twins))
            )
        ),
        $canEditContent || $story->files ? section
        (
            set::title($lang->story->legendAttach),
            $canEditContent ? fileSelector() : null,
            $story->files ? fileList
            (
                set::files($story->files),
                set::fieldset(false),
                set::object($story)
            ) : null
        ) : null,
        section
        (
            set::title($lang->story->comment),
            formGroup(editor(set::name('comment')))
        )
    ),
    history(),
    detailSide
    (
        set::isForm(true),
        tableData
        (
            set::title($lang->story->legendBasicInfo),
            item
            (
                set::name($lang->story->module),
                inputGroup
                (
                    span
                    (
                        set('id', 'moduleIdBox'),
                        picker
                        (
                            set::name('module'),
                            set::items($fields['module']['options']),
                            set::value($fields['module']['default'])
                        )
                    ),
                    count($moduleOptionMenu) == 1 ? btn(set::url($this->createLink('tree', 'browse', "rootID={$story->product}&view=story&currentModuleID=0&branch={$story->branch}")), set('data-toggle', 'modal'), $lang->tree->manage) : null,
                    count($moduleOptionMenu) == 1 ? btn(set('onclick', "loadProductModules({$story->product})"), setClass('refresh'), icon('refresh')) : null
                )
            ),
            $story->parent >= 0 && $story->type == 'story' ? item
            (
                set::trClass(zget($fields['parent'], 'className', '')),
                set::name($lang->story->parent),
                picker(setID('parent'), set::name('parent'), set::items($fields['parent']['options']), set::value($fields['parent']['default']))
            ) : null,
            item
            (
                set::name($lang->story->status),
                span(setClass("status-{$story->status}"), $this->processStatus('story', $story)),
                formHidden('status', $story->status)
            ),
            item
            (
                set::name($lang->story->pri),
                priPicker(set::name('pri'), set::items($fields['pri']['options']), set::value($fields['pri']['default']))
            ),
            item
            (
                set::name($lang->story->estimate),
                $story->parent >= 0 ? input(set::name('estimate'), set::value($story->estimate)) : $story->estimate
            ),
            item
            (
                set::trClass('feedbackBox'),
                set::trClass(in_array($story->source, $config->story->feedbackSource) ? '' : 'hidden'),
                set::name($lang->story->feedbackBy),
                input(set::name('feedbackBy'), set::value($story->feedbackBy))
            ),
            item
            (
                set::trClass('feedbackBox'),
                set::trClass(in_array($story->source, $config->story->feedbackSource) ? '' : 'hidden'),
                set::name($lang->story->notifyEmail),
                input(set::name('notifyEmail'), set::value($story->notifyEmail))
            ),
            item
            (
                set::name($lang->story->keywords),
                input(set::name('keywords'), set::value($story->keywords))
            ),
            item
            (
                set::name($lang->story->mailto),
                inputGroup
                (
                    picker(setID('mailto'), set::name('mailto[]'), set::items($fields['mailto']['options']), set::value($fields['mailto']['default']), set::multiple(true)),
                    isset($contactList) ? picker
                    (
                        setID('contactListMenu'),
                        set::name('contactListMenu'),
                        set::items($contactList),
                        set::value()
                    ) : btn
                    (
                        set('url', createLink('my', 'managecontacts', 'listID=0&mode=new')),
                        set('title', $lang->user->contacts->manage),
                        set('data-toggle', 'modal'),
                        icon('cog')
                    ),
                    isset($contactList) ? null : btn
                    (
                        set('id', 'refreshMailto'),
                        set('class', 'text-black'),
                        icon('refresh')
                    )
                )
            )
        ),
        tableData
        (
            set::title($lang->story->legendLifeTime),
            item
            (
                set::name($lang->story->openedBy),
                zget($users, $story->openedBy)
            ),
            item
            (
                set::name($lang->story->assignedTo),
                picker
                (
                    setID('assignedTo'),
                    set::name('assignedTo'),
                    set::items($fields['assignedTo']['options']),
                    set::value($fields['assignedTo']['default'])
                )
            ),
            $story->status == 'reviewing' ? item
            (
                set::name($lang->story->reviewers),
                picker
                (
                    set::id('reviewer'),
                    set::name('reviewer[]'),
                    set::items($fields['reviewer']['options']),
                    set::value($fields['reviewer']['default']),
                    set::multiple(true),
                    on::change('changeReviewer')
                )
            ) : null,
            $story->status == 'closed' ? item
            (
                set::name($lang->story->closedBy),
                picker(setID('closedBy'), set::name('closedBy'), set::items($fields['closedBy']['options']), set::value($fields['closedBy']['default']))
            ) : null,
            $story->status == 'closed' ? item
            (
                set::name($lang->story->closedReason),
                picker(setID('closedReason'), set::name('closedReason'), set::items($fields['closedReason']['options']), set::value($fields['closedReason']['default']), on::change('setStory'))
            ) : null
        )
    )
);

render();
