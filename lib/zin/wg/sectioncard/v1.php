<?php
declare(strict_types=1);
namespace zin;

class sectionCard extends wg
{
    protected static $defineBlocks = array(
        'title' => array('map' => 'entityLabel'),
    );

    private function title(string $text): wg
    {
        return div
        (
            setClass('article-h4', 'mb-1'),
            "[$text]"
        );
    }

    public function onBuildItem(item $item)
    {
        return div
        (
            setClass('py-2', 'pl-2'),
            $this->title($item->prop('title')),
            $item->children(),
        );
    }

    protected function build()
    {
        $title = $this->block('title');

        return div
        (
            setClass('section-card', 'border', 'rounded-sm'),
            div
            (
                setClass('h-9', 'flex', 'items-center', 'pl-2'),
                setStyle('background', '#F8F8F8'),
                $title
            ),
            div
            (
                setClass('py-1'),
                $this->children()
            ),
        );
    }
}
