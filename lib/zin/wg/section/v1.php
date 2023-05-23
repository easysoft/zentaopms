<?php
declare(strict_types=1);
namespace zin;

class section extends wg
{
    protected static $defineProps = array(
        'title: string',          // 标题
        'content?: string|array', // 内容
        'useHtml?: bool=false'    // 内容是否解析 HTML 标签
    );

    protected static $defineBlocks = array(
        'subTitle' => array()
    );

    protected function onAddChild(mixed $child): mixed
    {
        if(is_string($child) && !$this->props->has('content'))
        {
            $this->props->set('content', $child);
            return false;
        }
    }

    private function title(): wg
    {
        $title = $this->prop('title');

        return div
        (
            setClass('article-h2', 'mb-3'),
            $title
        );
    }

    private function headingTag(string $text): wg
    {
        return div
        (
            setClass('article-h4', 'my-1'),
            "[$text]"
        );
    }

    private function content(string $text): wg
    {
        $useHtml = $this->prop('useHtml');

        return div
        (
            setClass('article-content'),
            $useHtml ? html($text) : $text,
        );

    }

    private function buildContent(): wg|array
    {
        $content = $this->prop('content');
        if(is_string($content)) return $this->content($content);
        return array_map(function($x)
        {
            return div
            (
                setClass('my-4'),
                $this->headingTag($x['title']),
                $this->content($x['content'])
            );
        }, $content);
    }

    protected function build(): wg
    {
        return div
        (
            setClass('section pt-6 px-6 pb-4'),
            set($this->props->skip(array_keys(static::getDefinedProps()))),
            $this->title(),
            $this->block('subTitle'),
            $this->buildContent(),
            $this->children()
        );
    }
}
