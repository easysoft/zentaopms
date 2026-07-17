<?php
declare(strict_types=1);
/**
 * The change view file of story module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）集团有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Wang Yidong <yidong@easycorp.ltd>
 * @package     story
 * @link        https://www.zentao.net
 */
namespace zin;
include './affected.html.php';

if($app->tab == 'product') data('activeMenuID', $story->type);
jsVar('lastReviewer', explode(',', $lastReviewer));
jsVar('storyID', $story->id);
jsVar('oldStoryTitle', $story->title);
jsVar('oldStorySpec', $story->spec);
jsVar('oldStoryVerify', $story->verify);
jsVar('changed', 0);
jsVar('storyType', $story->type);
jsVar('rawModule', $this->app->rawModule);
jsVar('page', $this->app->rawMethod);

$fields = useFields('story.change');

$fields->field('checkAffection')->control(function() use($getAffectedTabs, $story, $users){return $getAffectedTabs($story, $users);});

modalHeader();
div
(
    setClass('panel-form mx-auto'),
    formPanel
    (
        set::ajax(array('beforeSubmit' => jsRaw('clickSubmit'))),
        set::actions(array
        (
            array('text' => $lang->save,               'data-status' => 'active', 'class' => 'primary',   'btnType' => 'submit'),
            array('text' => $lang->story->doNotSubmit, 'data-status' => 'draft',  'class' => 'secondary', 'btnType' => 'submit'),
            isInModal() ? null : array('text' => $lang->goback, 'data-back' => 'APP', 'class' => 'open-url')
        )),
        set::fields($fields)
    ),
    h::hr(setClass('mt-6 mb-6')),
    history(set::objectID($story->id))
);
