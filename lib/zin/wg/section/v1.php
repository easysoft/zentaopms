<?php
declare(strict_types=1);
namespace zin;

class section extends wg
{
    protected static array $defineProps = array(
        'title?: string',         // 标题
        'content?: string|array', // 内容
        'useHtml?: bool=false',   // 内容是否解析 HTML 标签
    );

    protected static array $defineBlocks = array(
        'subtitle' => array(),
        'actions'  => array(),
    );

    protected function onAddChild($child)
    {
        if(is_string($child) && !$this->props->has('content'))
        {
            $this->props->set('content', $child);
            return false;
        }
    }

    private function title(): wg
    {
        $title       = $this->prop('title');
        $actionsView = $this->block('actions');

        if(empty($actionsView)) return div(setClass('article-h1', 'mb-2'), $title);

        return div
        (
            setClass('flex', 'items-center', 'mb-2'),
            div(setClass('article-h1'), $title),
            $actionsView,
        );
    }

    private function content(string|wg $content): wg
    {
        $useHtml = $this->prop('useHtml') === true && is_string($content);

        return div
        (
            setClass('article-content'),
            $useHtml ? html($content) : $content,
        );

    }

    private function buildContent(): wg|array|null
    {
        $content = $this->prop('content');
        if(!isset($content)) return null;

        return $this->content($content);
    }

    protected function build(): wg
    {
        return div
        (
            setClass('section'),
            set($this->getRestProps()),
            $this->title(),
            $this->block('subtitle'),
            $this->buildContent(),
            $this->children()
        );
    }
}
