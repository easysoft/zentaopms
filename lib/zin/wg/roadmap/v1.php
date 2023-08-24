<?php
declare(strict_types=1);
namespace zin;

class roadMap extends wg
{
    protected static array $defineProps = array(
        'releases: array',
    );

    public static function getPageJS(): string
    {
        return file_get_contents(__DIR__ . DS . 'js' . DS . 'v1.js');
    }

    public static function getPageCSS(): string
    {
        return file_get_contents(__DIR__ . DS . 'css' . DS . 'v1.css');
    }

    private function releaseHead(string|int $title, string $subtitle)
    {
        return div
        (
            setClass('release-head shrink-0 py-1 px-2 text-center'),
            div
            (
                setClass('title text-primary text-xl'),
                $title,
            ),
            div
            (
                setClass('subtitle text-gray text-base'),
                $subtitle,
            ),
        );
    }

    private function releaseLine(array $releases)
    {
        $releaseVersions = array();
        foreach($releases as $release)
        {
            $releaseVersions[] = $this->releaseVersion($release);
        }

        return ul
        (
            setClass('release-line flex py-3'),
            $releaseVersions,
        );
    }

    private function releaseVersion(array $release)
    {
        return li
        (
            setClass('flex grow'),
            a
            (
                setClass('inline-block w-full relative'),
                set::href($release['href']),
                $release['marker'] ? icon('flag', setClass('absolute text-primary')) : null,
                div
                (
                    div(setClass('title ellipsis text-lg text-dark'), set::title($release['version']), $release['version']),
                    div(setClass('date ellipsis text-sm text-gray'), $release['date'])
                ),
            )
        );
    }

    private function releasePath(string|int $year, array $yearReleases)
    {
        $releaseLines = array();
        $count = 0;
        foreach($yearReleases as $releases)
        {
            $count += count($releases);
            $releaseLines[] = $this->releaseLine($releases);
        }

        global $lang;
        if(!isset($lang->execution->iterationInfo))
        {
            global $app;
            $app->loadLang('execution');
        }
        $iterationInfo = $lang->execution->iterationInfo;

        return div
        (
            setClass('release-path flex gap-6 items-end'),
            $this->releaseHead($year, sprintf($iterationInfo, (string)$count)),
            div
            (
                setClass('grow'),
                $releaseLines,
            )
        );
    }

    protected function build(): wg
    {
        $releases = $this->prop('releases');
        $releasePaths = array();

        foreach($releases as $year => $yearReleases)
        {
            $releasePaths[] = $this->releasePath($year, $yearReleases);
        }

        return div
        (
            setClass('release-paths bg-white relative'),
            $releasePaths
        );
    }
}
