<?php
declare(strict_types=1);
namespace zin;

class globalSearch extends wg
{
    protected static array $defineProps = array(
        'commonSearchText: string',
        'commonSearchUrl: string',
        'searchItems: array',
    );

    protected function build(): array
    {
        $input = inputGroup
        (
            input(setID('globalSearchInput')),
            btn
            (
                set::icon('search'),
                set::type('primary'),
            ),
        );
        $input->setProp('data-zin-id', $input->gid);
        $props = array_merge
        (
            $this->props->pick(array('commonSearchText', 'commonSearchUrl', 'searchItems')),
            array('_to' => "[data-zin-id='{$input->gid}']")
        );
        return array(
            $input,
            zui::globalSearch(set($props))
        );
    }
}
