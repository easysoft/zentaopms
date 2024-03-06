<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'btn' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'storylist' . DS . 'v1.php';

class linkedStoryList extends storyList
{
    protected static array $defineProps = array
    (
        'story'          => '?object',      // 被关联的需求。
        'unlinkBtn'      => '?array|bool',  // 取消关联按钮。
        'unlinkStoryTip' => '?string',      // 取消关联提示信息。
        'newLinkBtn'     => '?array|bool'   // 底部新关联按钮。
    );

    public static function getPageJS(): ?string
    {
        return <<<'JS'
        window.unlinkStory = function(e)
        {
            const $this = $(e.target).closest('li').find('.unlinkStory');
            zui.Modal.confirm({message: window.unlinkStoryTip || $this.closest('ul').data('unlinkStoryTip'), icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
            {
                if(res) $.get($this.attr('url'), function(){$this.closest('li').remove()});
            });
        };
        JS;
    }

    protected ?object $story = null;

    protected function created()
    {
        $story = $this->prop('story');
        if(!$story) $story = data('story');
        if(!$story) return;

        $this->story = $story;

        $canLinkStory = hasPriv($story->type, 'linkStory');
        if(!$this->hasProp('unlinkBtn'))  $this->setProp('unlinkBtn', $canLinkStory);
        if(!$this->hasProp('newLinkBtn')) $this->setProp('newLinkBtn', $canLinkStory);
        if(!$this->hasProp('storyType'))  $this->setProp('storyType', $story->type == 'story' ? 'requirement' : 'story');
        if(!$this->hasProp('unlinkStoryTip'))
        {
            global $lang;
            $this->setProp('unlinkStoryTip', $story->type == 'story' ? str_replace($lang->SRCommon, $lang->URCommon, $lang->story->unlinkStory) : $lang->story->unlinkStory);
        }
    }

    protected function getItem(object $story, bool $canView, string $storyType = 'story'): array
    {
        global $lang;

        $item      = parent::getItem($story, $canView, $storyType);
        $unlinkBtn = $this->prop('unlinkBtn');

        if($unlinkBtn)
        {
            if(!isset($item['actions'])) $item['actions'] = array();
            $btn = array
            (
                'class'       => 'unlinkStory unlink opacity-0 group-hover:opacity-100 primary',
                'icon'        => 'unlink',
                'data-id'     => $story->id,
                'data-on'     => 'click',
                'data-url'    => createLink('story', 'linkStory', "storyID=$story->id&type=remove&linkedID={$story->id}&browseType=&queryID=0&storyType=$storyType"),
                'data-params' => 'event',
                'data-call'   => 'unlinkStory',
                'hint'        => $lang->story->unlinkStory
            );

            if(is_array($unlinkBtn)) $btn = array_merge($btn, $unlinkBtn);

            $item['actions'][] = $btn;
        }

        return $item;
    }

    protected function build()
    {
        $list = parent::build();

        if($this->prop('unlinkBtn'))
        {
            $list->add(setClass('linked-story-list group'));
            $list->add(setData('unlinkStoryTip', $this->prop('unlinkStoryTip')));
        }

        $newLinkBtn = $this->prop('newLinkBtn');
        if($newLinkBtn)
        {
            global $lang;
            $story = $this->story;
            if(!$story) return $list;

            $btn = new btn
            (
                set::url(createLink('story', 'linkStory', "storyID=$story->id&type=linkStories&linkedID=0&browseType=&queryID=0&storyType=$story->type")),
                set::icon('plus'),
                set::size('sm'),
                set::type('secondary'),
                setClass('mt-2'),
                setData(array('toggle' => 'modal', 'size' => 'lg')),
                setID('linkButton'),
                is_array($newLinkBtn) ? set($newLinkBtn) : null,
                $lang->story->link . ($story->type == 'story' ? $lang->story->requirement : $lang->story->story)
            );
            return array($list, $btn);
        }
        return $list;
    }
}
