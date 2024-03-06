<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'datalist' . DS . 'v1.php';

class storyLifeInfo extends wg
{
    protected static array $defineProps = array
    (
        'story'     => '?object',   // 当前需求。
        'reviewers' => '?array',    // 评审人数据。
        'users'     => '?array'     // 用户列表。
    );

    protected function getItems(): array
    {
        global $lang;

        $story = $this->prop('story', data('story'));
        if(!$story) return array();

        $users     = $this->prop('users', data('users'));
        $reviewers = $this->prop('reviewers', data('reviewers'));
        $items     = array();

        $items[$lang->story->openedBy] = zget($users, $story->openedBy) . $lang->at . $story->openedDate;
        $items[$lang->story->assignedTo] = $story->assignedTo ? zget($users, $story->assignedTo) . $lang->at . $story->assignedDate : null;
        $items[$lang->story->reviewers] = array
        (
            'children' => wg(div
            (
                setClass('row gap-2 flex-wrap'),
                array_values(array_map(function($reviewer, $result) use($users, $lang)
                {
                    return !empty($result) ? span(setClass('mr-2'), set::title($lang->story->reviewed), set::style(array('color' => '#cbd0db')), zget($users, $reviewer)) : span(setClass('mr-2'), set::title($lang->story->toBeReviewed), zget($users, $reviewer));
                }, array_keys($reviewers), array_values($reviewers)))
            ))
        );
        $items[$lang->story->reviewedDate] = $story->reviewedDate;
        $items[$lang->story->closedBy] = $story->closedBy ? zget($users, $story->closedBy) . $lang->at . $story->closedDate : null;
        $items[$lang->story->closedReason] = array
        (
            'class'    => 'resolution',
            'children' => wg
            (
                $story->closedReason ? zget($lang->story->reasonList, $story->closedReason) : null,
                isset($story->extraStories[$story->duplicateStory]) ? a(set::href(inlink('view', "storyID=$story->duplicateStory")), set::title($story->extraStories[$story->duplicateStory]), "#{$story->duplicateStory} {$story->extraStories[$story->duplicateStory]}") : null
            )
        );
        $items[$lang->story->lastEditedBy] = $story->lastEditedBy ? zget($users, $story->lastEditedBy) . $lang->at . $story->lastEditedDate : null;

        return $items;
    }

    protected function build()
    {
        return new datalist
        (
            set::className('story-life-info'),
            set::items($this->getItems())
        );
    }
}
