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

$canEditContent = str_contains(',draft,changing,', ",{$story->status},");
$forceReview    = $this->story->checkForceReview();
$assignedToList = $story->status == 'closed' ? $users + array('closed' => 'Closed') : $users;

$planCount    = !empty($story->planTitle) ? count($story->planTitle) : 0;
$multiplePlan = ($product->type != 'normal' && empty($story->branch) && $planCount > 1);

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
            span(setID('storyTitle'), $story->title)
        )
    )
);

detailBody
(
    setID('dataform'),
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
            inputControl
            (
                input
                (
                    setClass('filter-none'),
                    set::name('title'),
                    set::value($fields['title']['default']),
                    set::disabled(!$canEditContent)
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
        $canEditContent ? section
        (
            set::title($lang->story->reviewers),
            formRow
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
        ) : null,
        section
        (
            set::title($lang->story->legendSpec),
            $canEditContent ? formGroup(editor(set::name('spec'), html($story->spec))) : set::content($story->spec),
            $canEditContent ? null : set::useHtml(true)
        ),
        section
        (
            set::title($lang->story->verify),
            $canEditContent ? formGroup(editor(set::name('verify'), html($story->verify))) : set::content($story->verify),
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
        $canEditContent || $story->files ? section
        (
            set::title($lang->story->legendAttach),
            $canEditContent ? upload() : null,
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
        tableData
        (
            setClass('mt-5'),
            set::title($lang->story->legendBasicInfo),
            $story->parent <= 0 ? item
            (
                set::trClass(zget($fields['product'], 'className', '')),
                set::name($lang->story->product),
                row
                (
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
                            set::name('branch'),
                            set::items($fields['branch']['options']),
                            set::value($fields['branch']['default']),
                            on::change('loadBranch')
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
                inputGroup
                (
                    span
                    (
                        setID('moduleIdBox'),
                        picker
                        (
                            set::name('module'),
                            set::items($fields['module']['options']),
                            set::value($fields['module']['default'])
                        )
                    ),
                    count($moduleOptionMenu) == 1 ? btn(set::url($this->createLink('tree', 'browse', "rootID={$story->product}&view=story&currentModuleID=0&branch={$story->branch}")), setData(array('toggle' => 'modal')), $lang->tree->manage) : null,
                    count($moduleOptionMenu) == 1 ? btn(set('onclick', "loadProductModules({$story->product})"), setClass('refresh'), icon('refresh')) : null
                )
            ),
            $story->parent >= 0 && $story->type == 'story' && $app->tab == 'product' ? item
            (
                set::trClass(zget($fields['parent'], 'className', '')),
                set::name($lang->story->parent),
                picker(setID('parent'), set::name('parent'), set::items($fields['parent']['options']), set::value($fields['parent']['default']))
            ) : null,
            $story->type == 'story' ? item
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
                    empty($fields['plan']['options']) ? btn(set('onclick', "loadProductPlans({$story->product})"), setClass('refresh'), icon('refresh')) : null
                )
            ) : null,
            item
            (
                set::name($lang->story->source),
                picker(setID('source'), set::name('source'), set::items($fields['source']['options']), set::value($fields['source']['default']), on::change('toggleFeedback(e.target)'))
            ),
            item
            (
                set::name($lang->story->sourceNote),
                input(set::name('sourceNote'), set::value($story->sourceNote))
            ),
            item
            (
                set::name($lang->story->status),
                span(setClass("status-{$story->status}"), $this->processStatus('story', $story)),
                formHidden('status', $story->status)
            ),
            $story->type == 'story' ? item
            (
                set::name($lang->story->stage),
                picker(setID('stage'), set::name('stage'), set::items($fields['stage']['options']), set::value($minStage))
            ) : null,
            item
            (
                set::name($lang->story->category),
                picker(setID('category'), set::name('category'), set::items($fields['category']['options']), set::value($fields['category']['default']))
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
                        setData(array('toggle' => 'modal')),
                        icon('cog')
                    ),
                    isset($contactList) ? null : btn
                    (
                        setID('refreshMailto'),
                        setClass('text-black'),
                        icon('refresh')
                    )
                )
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
                    set::items($fields['assignedTo']['options']),
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
        tableData
        (
            set::title($lang->story->legendMisc),
            $story->status == 'closed' ? item
            (
                set::trClass('duplicateStoryBox'),
                set::name($lang->story->duplicateStory),
                picker(setID('duplicateStory'), set::name('duplicateStory'), set::items($fields['duplicateStory']['options']), set::value($fields['duplicateStory']['default']), set::placeholder($lang->bug->placeholder->duplicate))
            ) : null,
            item
            (
                set::name($story->type == 'story' ? $lang->requirement->linkStory : $lang->story->linkStory),
                ($story->type == 'story' && common::hasPriv('story', 'linkStories')) ? btn
                (
                    setClass('secondary'),
                    setID('linkStoriesLink'),
                    setData(array('toggle' => 'modal', 'size' => 'lg')),
                    on::click('linkStories'),
                    $lang->story->linkStoriesAB
                ) : null,
                ($story->type == 'requirement' && common::hasPriv('requirement', 'linkRequirements')) ? btn
                (
                    setClass('secondary'),
                    setID('linkStoriesLink'),
                    setData(array('toggle' => 'modal', 'size' => 'lg')),
                    on::click('linkStories'),
                    $lang->story->linkRequirementsAB
                ) : null
            ),
            item
            (
                set::name(' '),
                !empty($story->linkStoryTitles) ? h::ul
                (
                    setID('linkedStories'),
                    array_values(array_map(function($linkStoryID, $linkStoryTitle) use($story)
                    {
                        $linkStoryField = $story->type == 'story' ? 'linkStories' : 'linkRequirements';
                        return h::li
                        (
                            set::title($linkStoryTitle),
                            checkbox(set::name($linkStoryField . '[]'), set::rootClass('inline'), set::value($linkStoryID), set::checked(true)),
                            label(setClass('circle size-sm'), $linkStoryID),
                            span(setClass('linkStoryTitle'), $linkStoryTitle)
                        );
                    }, array_keys($story->linkStoryTitles), array_values($story->linkStoryTitles)))
                ) : null,
                div(setID('linkStoriesBox'))
            )
        )
    )
);

render();
