<?php
declare(strict_types=1);
/**
 * The edit file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang<Yidong@easycorp.ltd>
 * @package     story
 * @link        https://www.zentao.net
 */
namespace zin;

$uid            = uniqid();
$canEditContent = str_contains(',draft,changing,', ",{$story->status},");
$forceReview    = $this->story->checkForceReview($story->type);
$assignedToList = $story->status == 'closed' ? array('closed' => 'Closed') : $users;
$storyEstimate  = $story->estimate ? helper::formatHours($story->estimate) : 0;

$planCount    = !empty($story->planTitle) ? count($story->planTitle) : 0;
$multiplePlan = ($product->type != 'normal' && empty($story->branch) && $planCount > 1) || ($story->type != 'story');
$showPlan     = $config->vision == 'or' ? false : true;

$minStage    = $story->stage;
$stageList   = implode(',', array_keys($this->lang->story->stageList));
$minStagePos = strpos($stageList, $minStage);
if(!empty($story->stages) && isset($fields['stage']['options']))
{
    foreach($story->stages as $branch => $stage)
    {
        $position = strpos(",{$stageList},", ",{$stage},");
        if($position !== false && $position > $minStagePos)
        {
            $minStage    = $stage;
            $minStagePos = $position;
        }
    }
}

if($story->type == 'story')
{
    unset($fields['stage']['options']['delivered']);
    unset($fields['stage']['options']['delivering']);
}

if($app->tab == 'product') data('activeMenuID', $story->type);
jsVar('storyType', $story->type);
jsVar('storyID', $story->id);
jsVar('storyStatus', $story->status);
jsVar('isParent', $story->isParent);
jsVar('oldProductID', $story->product);
jsVar('oldGrade', $story->grade);
jsVar('oldParent', $story->parent);
jsVar('lastReviewer', explode(',', $lastReviewer));
jsVar('reviewedBy', explode(',', trim($story->reviewedBy, ',')));
jsVar('defaultReviewer', array_keys($fields['reviewer']['options']));
jsVar('storyReviewers', $storyReviewers);
jsVar('reviewerNotEmpty', $lang->story->notice->reviewerNotEmpty);
jsVar('notDeleted', $lang->story->notice->notDeleted);
jsVar('twins', $story->twins);
jsVar('relievedTwinsTip', $lang->story->relievedTwinsTip);
jsVar('changeProductTips', $lang->story->changeProductTips);
jsVar('moveChildrenTips', $lang->story->moveChildrenTips);
jsVar('executionID', isset($objectID) ? $objectID : 0);
jsVar('langTreeManage', $lang->tree->manage);
jsVar('feedbackSource', $config->story->feedbackSource);
jsVar('relievedTip', $lang->story->relievedTip);

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
            span(setID('storyTitle'), $story->title)
        )
    )
);

detailBody
(
    setID('dataform'),
    set::isForm(true),
    set::ajax(array('beforeSubmit' => jsRaw('clickSubmit'))),
    on::change('[name=parent]', 'loadGrade'),
    on::change('[name=grade]', 'checkGrade'),
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
            set::required(true),
            set::title($lang->story->title),
            inputControl
            (
                input
                (
                    setClass('filter-none'),
                    set::name('title'),
                    set::value($fields['title']['default']),
                    set::readonly(!$canEditContent)
                ),
                set::suffixWidth('40'),
                to::suffix
                (
                    colorPicker
                    (
                        set::heading($lang->story->colorTag),
                        set::name('color'),
                        set::value($story->color),
                        set::syncColor('#title, #storyTitle')
                    )
                )
            )
        ),
        section
        (
            set::required(true),
            set::title($lang->story->reviewers),
            !$canEditContent ? set::hidden(true) : null,
            inputGroup
            (
                picker
                (
                    setID('reviewer'),
                    set::name('reviewer[]'),
                    set::items($fields['reviewer']['options']),
                    set::value($fields['reviewer']['default']),
                    set::multiple(true),
                    on::change('changeReviewer')
                ),
                $forceReview ? null : span
                (
                    setClass('input-group-addon'),
                    checkbox
                    (
                        setID('needNotReview'),
                        set::name('needNotReview'),
                        set::checked(empty($fields['reviewer']['default'])),
                        set::value(1),
                        set::text($lang->story->needNotReview),
                        on::change('changeNeedNotReview(e.target)')
                    )
                ),
                formHidden('needNotReview', $forceReview ? 0 : 1)
            )
        ),
        section
        (
            set::title($lang->story->legendSpec),
            $canEditContent ? formGroup(editor(set::name('spec'), set::uid($uid), html($story->spec))) : set::content($story->spec),
            $canEditContent ? null : set::useHtml(true)
        ),
        section
        (
            set::title($lang->story->verify),
            $canEditContent ? formGroup(editor(set::name('verify'), set::uid($uid), html($story->verify))) : set::content($story->verify),
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
                        common::hasPriv('story', 'view') ? a(set::href($this->createLink('story', 'view', "id={$twin->id}")), setClass('title'), set::title($twin->title), setData(array('toggle' => 'modal')), $twin->title) : span(setClass('title'), $twin->title),
                        label(setClass('size-sm'), set::title($stage), $stage),
                        common::hasPriv('story', 'relieved') ? a(set::title($lang->story->relievedTwins), setClass("relievedTwins unlink size-xs"), on::click('unlinkTwins'), setData(array('id' => $twin->id)), icon('unlink')) : null
                    );
                }, $twins))
            )
        ),
        section
        (
            setID('files'),
            setClass(!$canEditContent && !$story->files ? 'hidden' : ''),
            set::title($lang->story->legendAttach),
            $canEditContent ? fileSelector(set::defaultFiles($story->files)) : null
        ),
        section
        (
            setID('comment'),
            set::title($lang->story->comment),
            formGroup(editor(set::name('comment'), set::uid($uid)))
        )
    ),
    history(set::objectID($story->id)),
    detailSide
    (
        set::isForm(true),
        tableData
        (
            setClass('mt-5'),
            set::title($lang->story->legendBasicInfo),
            !$product->shadow ? item
            (
                set::trClass(zget($fields['product'], 'className', '')),
                set::name($lang->story->product),
                row
                (
                    on::change('[name=branch]')->call('loadBranch'),
                    picker
                    (
                        setID('product'),
                        set::name('product'),
                        set::items($fields['product']['options']),
                        set::value($fields['product']['default']),
                        on::change('loadProduct'),
                        set::required(true)
                    ),
                    span
                    (
                        setClass('branchIdBox'),
                        setClass($product->type == 'normal' ? 'hidden' : ''),
                        $product->type != 'normal' ? picker
                        (
                            setID('branch'),
                            set::width('100px'),
                            set::name('branch'),
                            set::items($fields['branch']['options']),
                            set::value($fields['branch']['default']),
                        ) : null
                    )
                )
            ) : null,
            $story->parent > 0 && $product->type != 'normal' ? item
            (
                set::name(sprintf($lang->product->branch, $lang->product->branchName[$product->type])),
                picker(setID('branch'), set::name('branch'), set::items($fields['branch']['options']), set::value($fields['branch']['default']))
            ) : null,
            item
            (
                set::name($lang->story->module),
                modulePicker
                (
                    set::items($fields['module']['options']),
                    set::value($fields['module']['default']),
                    set::manageLink(createLink('tree', 'browse', "rootID={$story->product}&view=story&currentModuleID=0&branch={$story->branch}"))
                )
            ),
            ($story->parent >= 0 && ($showGrade || $story->type != 'epic')) && !$story->twins ? item
            (
                set::trClass(zget($fields['parent'], 'className', '')),
                set::name($lang->story->parent),
                picker(setID('parent'), set::name('parent'), set::items($fields['parent']['options']), set::value($fields['parent']['default']))
            ) : null,
            $showGrade ? item
            (
                set::name($lang->story->grade),
                picker(setID('grade'), set::name('grade'), set::required(true), set::items($fields['grade']['options']), set::value($story->grade), set::disabled($gradeRule == 'stepwise'))
            ) : picker(setID('grade'), set::name('grade'), set::required(true), set::items($fields['grade']['options']), set::value($story->grade), set::hidden(true)),
            $showPlan ? item
            (
                set::trClass(zget($fields['plan'], 'className', '')),
                set::name($lang->story->plan),
                inputGroup
                (
                    span
                    (
                        setID('planIdBox'),
                        picker(set::name($multiplePlan ? 'plan[]' : 'plan'), set::items($fields['plan']['options']), set::value($fields['plan']['default']), set::multiple($multiplePlan)),
                    ),
                    empty($fields['plan']['options']) ? btn(set::url($this->createLink('productplan', 'create', "productID={$story->product}&branch={$story->branch}")), setData(array('toggle' => 'modal')), icon('plus')) : null,
                    empty($fields['plan']['options']) ? btn(set('onclick', "loadProductPlans({$story->product}, {$story->branch})"), setClass('refresh'), icon('refresh')) : null
                )
            ) : null,
            item
            (
                set::trClass('sourceBox'),
                set::name($lang->story->source),
                picker(setID('source'), set::name('source'), set::items($lang->{$story->type}->sourceList), set::value($fields['source']['default']), on::change('window.toggleFeedback(e.target)'))
            ),
            item
            (
                set::trClass('sourceNoteBox'),
                set::name($lang->story->sourceNote),
                input(set::name('sourceNote'), set::value($story->sourceNote))
            ),
            item
            (
                set::name($lang->story->status),
                span(setClass("status-{$story->status}"), $this->processStatus('story', $story)),
                formHidden('status', $story->status)
            ),
            item
            (
                set::name($lang->story->stage),
                $story->isParent == '0' && $story->type == 'story' ? picker(setID('stage'), set::name('stage'), set::items($fields['stage']['options']), set::value($minStage)) : formHidden('stage', $story->stage),
                $story->isParent == '0' && $story->type == 'story' ? null : zget($fields['stage']['options'], $story->stage)
            ),
            item
            (
                set::name($lang->story->category),
                picker(setID('category'), set::name('category'), set::items($lang->{$story->type}->categoryList), set::value($fields['category']['default']))
            ),
            item
            (
                set::name($lang->story->pri),
                priPicker(set::name('pri'), set::items($lang->{$story->type}->priList), set::value($fields['pri']['default']))
            ),
            item
            (
                set::name($lang->story->estimate),
                $story->isParent == '0' ? input(set::name('estimate'), set::value($storyEstimate)) : $storyEstimate
            ),
            item
            (
                set::trClass(in_array($story->source, $config->story->feedbackSource) ? 'feedbackBox' : 'feedbackBox hidden'),
                set::name($lang->story->feedbackBy),
                input(set::name('feedbackBy'), set::value($story->feedbackBy))
            ),
            item
            (
                set::trClass(in_array($story->source, $config->story->feedbackSource) ? 'feedbackBox' : 'feedbackBox hidden'),
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
                mailto(set::items($fields['mailto']['options']), set::value($fields['mailto']['default']))
            ),
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
                    set::items($assignedToList),
                    set::value($fields['assignedTo']['default'])
                )
            ),
            $story->status == 'reviewing' ? item
            (
                set::name($lang->story->reviewers),
                picker
                (
                    setID('reviewer'),
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
            ) : null,
        ),
        $story->status == 'closed' ? tableData
        (
            set::title($lang->story->legendMisc),
            item
            (
                set::trClass('duplicateStoryBox'),
                set::name($lang->story->duplicateStory),
                picker(setID('duplicateStory'), set::name('duplicateStory'), set::items($fields['duplicateStory']['options']), set::value($fields['duplicateStory']['default']), set::placeholder($lang->bug->placeholder->duplicate))
            ),
        ) : null
    )
);

render();
