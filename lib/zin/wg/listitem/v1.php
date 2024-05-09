<?php
declare(strict_types=1);
namespace zin;

require_once dirname(__DIR__) . DS . 'icon' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'checkbox' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'avatar' . DS . 'v1.php';
require_once dirname(__DIR__) . DS . 'toolbar' . DS . 'v1.php';

class listItem extends wg
{
    public bool $isLink    = false;
    public bool $multiline = false;

    protected static array $defineProps = array
    (
        'type'          => '?string',     // divider, heading, item
        'tagName'       => '?string="div"',
        'innerTag'      => '?string',
        'innerClass'    => '?string|array',
        'innerAttrs'    => '?array',
        'multiline'     => '?bool',
        'icon'          => '?string|array',
        'toggleIcon'    => '?string|array',
        'selected'      => '?bool',
        'disabled'      => '?bool',
        'checked'       => '?bool|array',
        'active'        => '?bool',
        'divider'       => '?bool',
        'avatar'        => '?array',
        'leading'       => '?mixed',
        'leadingClass'  => '?string|array',
        'url'           => '?string',
        'target'        => '?string',
        'text'          => '?string',
        'textClass'     => '?string|array',
        'title'         => '?string',
        'titleClass'    => '?string|array',
        'titleAttrs'    => '?array',
        'subtitle'      => '?string',
        'subtitleClass' => '?string|array',
        'hint'          => '?string',
        'trailing'      => '?mixed',
        'actions'       => '?array',
        'actionsClass'  => '?array|string',
        'content'       => '?mixed',
        'contentClass'  => '?string|array',
        'contentAttrs'  => '?array',
    );

    protected function buildLeading()
    {
        list($icon, $avatar, $toggleIcon, $leading, $leadingClass, $checked, $checkbox) = $this->prop(array('icon', 'avatar', 'toggleIcon', 'leading', 'leadingClass', 'checked', 'checkbox'));

        $views = array();
        if($toggleIcon)       $views[] = icon::create($toggleIcon);
        if($checked !== null) $views[] = checkbox::create($checked, array('class' => 'item-checkbox'), $checkbox ? set($checkbox) : null);
        if($icon)             $views[] = icon::create($icon, array('class' => 'item-icon'));
        if($avatar)           $views[] = new avatar(setClass('item-avatar'), set($avatar));
        if($leading)          $views[] = $leading;

        if($this->multiline && $views)
        {
            $views = div
            (
                setClass('item-leading', $leadingClass),
                $views
            );
        }
        return $views;
    }

    protected function buildContent()
    {
        list($text, $title, $textClass, $titleClass, $titleAttrs, $subtitle, $subtitleClass, $url, $target, $content, $contentClass, $contentAttrs) = $this->prop(array('text', 'title', 'textClass','titleClass','titleAttrs','subtitle','subtitleClass','url','target','content','contentClass','contentAttrs'));

        if($title === null)
        {
            $title = $text;
            $text = null;
        }
        $titleTag = (!$this->isLink && $url) ? 'a' : 'div';

        return div
        (
            setClass('item-content', $contentClass),
            set($contentAttrs),
            $title ? h::$titleTag
            (
                setClass('item-title', $titleClass),
                set($titleAttrs),
                $titleTag === 'a' ? set(array('href' => $url, 'target' => $target)) : null,
                $title
            ) : null,
            $subtitle ? div
            (
                setClass('item-subtitle', $subtitleClass),
                $subtitle
            ) : null,
            $text ? div
            (
                setClass('item-text text', $textClass),
                $text
            ) : null,
            $content
        );
    }

    protected function buildTrailing()
    {
        list($trailing, $trailingClass, $trailingIcon, $actions, $actionsClass) = $this->prop(array('trailing', 'trailingClass', 'trailingIcon', 'actions', 'actionsClass'));

        $views = array();
        if($trailingIcon) $views[] = icon::create($trailingIcon, array('class' => 'item-trailing-icon'));
        if($actions)      $views[] = toolbar::create($actions, set::size('sm'), $actionsClass ? setClass($actionsClass) : null);
        if($trailing)     $views[] = $trailing;

        if($this->multiline && $views)
        {
            $views = div
            (
                setClass('item-trailing', $trailingClass),
                $views
            );
        }
        return $views;
    }

    protected function buildInner()
    {
        list($innerTag, $innerClass, $innerAttrs, $active, $disabled, $selected, $divider, $checked, $hint, $url, $target) = $this->prop(array('innerTag', 'innerClass', 'innerAttrs', 'active', 'disabled', 'selected', 'divider', 'checked', 'hint', 'url', 'target'));

        $isLink = $this->isLink;
        if(!$innerTag) $innerTag = $isLink ? 'a' : 'div';

        return h::$innerTag
        (
            setClass('listitem', array('active' => $active, 'disabled' => $disabled, 'selected' => $selected, 'has-divider' => $divider, 'checked' => $checked, 'multiline' => $this->multiline), $innerClass),
            set::title($hint),
            $isLink ? set(array('href' => $url ? $url : 'javascript:;', 'target' => $target)) : null,
            set($innerAttrs),
            $this->buildLeading(),
            $this->buildContent(),
            $this->buildTrailing()
        );
    }

    protected function build()
    {
        list($tagName, $innerTag, $url, $multiline, $title, $subtitle) = $this->prop(array('tagName', 'innerTag', 'url', 'multiline', 'title', 'subtitle'));

        $this->isLink    = $url && (!$innerTag || $innerTag === 'a');
        $this->multiline = is_null($multiline) ? ($subtitle && $title) : $multiline;

        return h::$tagName
        (
            setClass('item'),
            set($this->getRestProps()),
            $this->buildInner()
        );
    }
}
