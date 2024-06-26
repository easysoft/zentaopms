<?php
declare(strict_types=1);
namespace zin;

class sectionCard extends wg
{
    protected static array $defineBlocks = array(
        'title' => array('map' => 'entityLabel')
    );

    private function title(string $text): node
    {
        return div
        (
            setClass('h4', 'mb-1'),
            "[$text]"
        );
    }

    public function onBuildItem(item $item): node
    {
        return div
        (
            setClass('py-2', 'pl-3'),
            $this->title($item->prop('title')),
            $item->children()
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
                setClass('h-9', 'flex', 'items-center', 'pl-3'),
                setStyle('background', 'var(--color-gray-100)'),
                $title
            ),
            div
            (
                setClass('py-1'),
                $this->children()
            )
        );
    }
}
