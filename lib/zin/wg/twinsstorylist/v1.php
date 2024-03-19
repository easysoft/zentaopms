<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'label' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'statuslabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'branchlabel' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'storylist' . DS . 'v1.php';

class twinsStoryList extends storyList
{
    protected static array $defineProps = array
    (
        'story'       => '?object',      // 被关联的需求。
        'relievedBtn' => '?array|bool',  // 取消关联按钮。
        'relievedTip' => '?string',      // 取消关联提示信息。
        'branches'    => '?array'        // 分支数据。
    );

    protected static array $defaultProps = array
    (
        'name' => 'twins-story-list'
    );

    public static function getPageJS(): ?string
    {
        return <<<'JS'
        window.unlinkTwins = function(e)
        {
            const $this    = $(e.target).closest('li').find('.relievedTwins');
            const $ul      = $this.closest('ul');
            const postData = new FormData();
            postData.append('twinID', $this.data('id'));
            zui.Modal.confirm({message: window.relievedTip || $ul.data('relievedTip'), icon:'icon-exclamation-sign', iconClass: 'warning-pale rounded-full icon-2x'}).then((res) =>
            {
                if(!res) return;
                $.post($.createLink('story', 'ajaxRelieveTwins'), postData, function()
                {
                    $this.closest('li').remove();
                    if($ul.find('li').length == 0) $ul.closest('.section').remove();
                });
            });
        };
        JS;
    }

    protected ?object $story = null;

    protected null|array|bool $relievedBtn = null;

    protected ?array $branches = null;

    protected function beforeBuild()
    {
        parent::beforeBuild();

        $story = $this->prop('story');
        if(!$story) $story = data('story');

        $branches = $this->prop('branches');
        if(!$branches) $branches = data('branches');

        $relievedBtn = $this->prop('relievedBtn');
        if($relievedBtn === null)  $relievedBtn = hasPriv('story', 'relieved');

        $this->story       = $story;
        $this->branches    = $branches;
        $this->relievedBtn = $relievedBtn;
    }

    protected function getItem(object $story): array
    {
        global $lang;

        $item        = parent::getItem($story);
        $relievedBtn = $this->relievedBtn;

        if(!isset($item['leading']))    $item['leading'] = array();
        if(isset($this->branches[$story->branch]))
        {
            if(!is_array($item['leading'])) $item['leading'] = array($item['leading']);
            $item['leading'] = array_merge(array(branchLabel::create($story->branch, $this->branches[$story->branch]), $item['leading']));
        }

        $item['content']    = statusLabel::create($story->status, $lang->story->statusList[$story->status], setClass('flex-none'));
        $item['titleClass'] = 'gap-2 items-center min-w-0 text-clip';

        if($relievedBtn)
        {
            $btn = array
            (
                'class'       => ($this->compact ? 'text-primary opacity-0 group-hover:opacity-100' : 'primary-pale'),
                'icon'        => 'unlink',
                'data-id'     => $story->id,
                'data-on'     => 'click',
                'data-params' => 'event',
                'data-call'   => 'unlinkTwins',
                'hint'        => $lang->story->relievedTwins
            );

            if(is_array($relievedBtn)) $btn = array_merge($btn, $relievedBtn);

            if(!isset($item['actions'])) $item['actions'] = array();
            $item['actions'][] = $btn;
        }

        return $item;
    }

    protected function build()
    {
        $list = parent::build();

        if($this->relievedBtn)
        {
            global $lang;
            $relievedTip = $this->prop('relievedTip');
            if($relievedTip === null) $relievedTip = $lang->story->relievedTip;

            $list->add(setData('relievedTip', $relievedTip));
        }

        return $list;
    }
}
