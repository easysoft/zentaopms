<?php
declare(strict_types=1);

/**
 * The appview view file of store module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     store
 * @link        https://www.zentao.net
 */

namespace zin;

$screenshots = array_filter($cloudApp->screenshot_urls);
if(!empty($screenshots) && count($screenshots)%3) $screenshots = array_merge($screenshots, array_fill(0, count($screenshots)%3, ''));
$screenshotsWg = array();
foreach($screenshots as $screenshot)
{
    $screenshotsWg[] = div
    (
        setClass('flex-1 img-thumbnail'),
        $screenshot ? img
        (
            set::src($screenshot),
            setClass('state'),
            on::click('window.open("' . $screenshot . '")')
        ) : null
    );
}

$dynamicArticlesWd = array();
foreach($dynamicArticles as $article)
{
    $dynamicArticlesWd[] = h::tr(h::td(a($article->title, set::href($article->url), set::target('_bland'))));
}

$dropMenus = array();
$dropMenus[] = array('text' => $lang->store->gitUrl,        'icon' => 'github', 'id' => 'git',    'target' => '_blank', 'url' => zget($cloudApp, 'git_url', '#'));
$dropMenus[] = array('text' => $lang->store->dockerfileUrl, 'icon' => 'docker', 'id' => 'docker', 'target' => '_blank', 'url' => zget($cloudApp, 'dockerfile_url', '#'));
$dropMenus[] = array('text' => $lang->store->forumUrl,      'icon' => 'forum',  'id' => 'forum',  'target' => '_blank', 'url' => 'https://www.qucheng.com/forum/usage.html');

detailHeader(
    to::prefix
    (
        backBtn
        (
            set::icon('back'),
            set::type('secondary'),
            set::url(inLink('browse')),
            $lang->goback
        )
    ),
    to::title(''),
    to::suffix
    (
        div
        (
            setID('store-detail-action'),
            dropdown
            (
                set::staticMenu(true),
                btn
                (
                    setClass('ghost text-black mr-2'),
                    set::icon('info-sign'),
                    $lang->store->support,
                ),
                set::items($dropMenus)
            ),
            backBtn
            (
                $lang->store->install,
                setClass('primary btn install-btn w-20'),
                set::type('primary'),
                set::url($this->createLink('space', 'createApplication', "id={$cloudApp->id}")),
                setData('toggle', 'modal')
            )
        )
    )
);
detailBody
(
    sectionList
    (
        section
        (
            div
            (
                setClass('flex'),
                img(set::src($cloudApp->logo), setStyle(array('width' => '50px', 'height' => '50px'))),
                div(setClass('app-name-container'),div($cloudApp->alias, setClass('app-name')))
            )
        ),
        section
        (
            set::title($lang->store->appBasicInfo),
            set::content($cloudApp->desc),
            set::useHtml(true),
            h::table
            (
                setStyle('min-width', '600px'),
                setClass('table w-auto max-w-full bordered mt-4'),
                h::tr
                (
                    h::th($lang->store->appVersion),
                    h::th($lang->store->appType),
                    h::th($lang->store->author),
                    h::th($lang->store->releaseDate)
                ),
                h::tr
                (
                    h::td($cloudApp->app_version),
                    h::td(trim(implode('/', helper::arrayColumn($cloudApp->categories, 'alias')), '/')),
                    h::td($cloudApp->author),
                    h::td((new \DateTime($cloudApp->publish_time))->format('Y-m-d'))
                )
            )
        ),
        section
        (
            set::title($lang->store->screenshots),
            div
            (
                setClass('flex gap-5 flex-wrap'),
                empty($screenshotsWg) ? div($lang->store->noScreenshot, setClass('errorBox')) : '',
                ...$screenshotsWg
            )
        ),
        section
        (
            set::title($lang->store->appDynamic),
            set::content(empty($dynamicArticles) ? $lang->store->noDynamicArticle : ''),
            empty($dynamicArticles) ? '' : div
            (
                setClass('border pb-5 pt-5'),
                h::table
                (
                    setID('dynamicTable'),
                    setClass('table borderless table-hover mb-3'),
                    ...$dynamicArticlesWd,
                ),
                pager(set::props(array('id' => 'dynamicPager')))
            )
        )
    )
);

