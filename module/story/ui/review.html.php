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

data('activeMenuID', $story->type);
jsVar('storyID', $story->id);
jsVar('storyType', $story->type);
jsVar('rawModule', $this->app->rawModule);
jsVar('isMultiple', count($reviewers) > 1);
jsVar('isLastOne', $isLastOne);

$fields = useFields('story.review');

$fields->field('checkAffection')->control(function() use($getAffectedTabs, $story, $users){return $getAffectedTabs($story, $users);});

modalHeader();

div
(
    setClass('panel-form mx-auto'),
    form(set::fields($fields)),
    h::hr(setClass('mt-6 mb-6')),
    history(set::objectID($story->id))
);
