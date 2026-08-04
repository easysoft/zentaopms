<?php
declare(strict_types=1);
/**
 * The edit file of story module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang<Yidong@easycorp.ltd>
 * @package     story
 * @link        https://www.zentao.net
 */
namespace zin;

$canEditContent = str_contains(',draft,changing,', ",{$story->status},");

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

$fields = useFields('story.edit');

detailForm
(
    setID('dataform'),
    set::ajax(array('beforeSubmit' => jsRaw('clickSubmit'))),
    on::change('[name=parent]', 'loadGrade'),
    on::change('[name=grade]', 'checkGrade'),
    $canEditContent ? set::actions(array
    (
        array('btnType' => 'submit', 'class' => 'primary',   'data-status' => 'active', 'text' => $lang->save),
        array('btnType' => 'submit', 'class' => 'secondary', 'data-status' => 'draft',  'text' => $story->status == 'changing' ? $lang->story->doNotSubmit : $lang->story->saveDraft),
        isInModal() ? null : array('text' => $lang->goback, 'back' => 'APP')
    )) : null,
    set::data($story),
    set::fields($fields)
);
