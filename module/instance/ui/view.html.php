<?php
declare(strict_types=1);

/**
 * The appview view file of instance module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ke Zhao<zhaoke@easycorp.ltd>
 * @package     instance
 * @link        http://www.zentao.net
 */

namespace zin;

$setting = usePager('pager');

$dynamicArticlesWd = array();
foreach($seniorAppList as $article)
{
    $dynamicArticlesWd[] = h::tr(h::td(a($article->title, set::href($article->url), set::target('_bland'))));
}

detailHeader(
    to::prefix(''),
    to::title(''),
);
detailBody
(
    sectionList
    (
        section
        (
            div
            (
                setClass('flex justify-between'),
                div
                (
                    setClass('flex'),
                    img(set::src($instance->logo), setStyle(array('width' => '50px', 'height' => '50px'))),
                    div
                    (
                        setClass('ml-3 flex col gap-y-1'),
                        div
                        (
                            $instance->name, setClass('text-xl'),
                            span($cloudApp->app_version, setClass('ml-3 label lighter rounded-full'))
                        ),
                        div
                        (
                            setClass('flex progress-container'),
                            icon('cog-outline text-warning'),
                            $lang->instance->cpuUsage,
                            div
                            (
                                setClass('progress rounded-lg'),
                                setStyle('background', 'var(--color-warning-50)'),
                                div
                                (
                                    setClass('progress-bar warning'),
                                    set::role('progressbar'),
                                    setStyle('width', $instanceMetric->cpu->rate . '%')
                                )
                            ),
                            icon('desktop text-primary'),
                            $lang->instance->memUsage,
                            span
                            (
                                setClass('text-gray'),
                                sprintf($lang->instance->memTotal, ceil($instanceMetric->memory->limit/1024/1024/1024)),
                            ),
                            div
                            (
                                setClass('progress rounded-lg'),
                                setStyle('background', 'var(--color-primary-50)'),
                                div
                                (
                                    setClass('progress-bar primary'),
                                    set::role('progressbar'),
                                    setStyle('width', $instanceMetric->memory->rate . '%')
                                )
                            )
                        ),
                    ),
                ),
                btn($lang->instance->setting, setClass('btn ghost'), set::icon('backend'))
            ),
        ),
        section
        (
            set::title($lang->instance->baseInfo),
            set::useHtml(true),
            h::table
            (
                setStyle('min-width', '700px'),
                setClass('table w-auto max-w-full bordered mt-4'),
                h::tr
                (
                    h::th($lang->instance->status),
                    h::th($lang->instance->source),
                    h::th($lang->instance->appTemplate),
                    h::th($lang->instance->installAt),
                    h::th($lang->instance->runDuration),
                    $defaultAccount ? h::th($lang->instance->defaultAccount) : null,
                    $defaultAccount ? h::th($lang->instance->defaultPassword) : null,
                ),
                h::tr
                (
                    h::td
                    (
                        span
                        (
                            setClass('label label-dot mr-1 ' . zget($this->lang->instance->htmlStatusesClass, $instance->status, ''))
                        ),
                        zget($this->lang->instance->statusList, $instance->status, ''), setClass('text-' . zget($this->lang->instance->htmlStatusesClass, $instance->status, ''))
                    ),
                    h::td(zget($lang->instance->sourceList, $instance->source, '')),
                    h::td(a(set::href($this->createLink('store', 'appView', "id=$instance->appID")), $instance->appName)),
                    h::td(substr($instance->createdAt, 0, 16)),
                    h::td(common::printDuration($instance->runDuration)),
                    $defaultAccount ? h::th($defaultAccount->username) : null,
                    $defaultAccount ? h::th($defaultAccount->password) : null,
                )
            )
        ),
        // section
        // (
        //     set::title($lang->instance->appDynamic),
        //     set::content(empty($dynamicArticles) ? $lang->instance->noDynamicArticle : ''),
        //     empty($dynamicArticles) ? '' : div
        //     (
        //         setClass('border pb-5 pt-5'),
        //         h::table
        //         (
        //             setID('dynamicTable'),
        //             setClass('table borderless table-hover mb-3'),
        //             ...$dynamicArticlesWd,
        //         ),
        //         pager(
        //             setID('dynamicPager'),
        //             set::page($setting['page']),
        //             set::recTotal($setting['recTotal']),
        //             set::recPerPage($setting['recPerPage']),
        //             set::linkCreator($setting['linkCreator']),
        //             set::items($setting['items']),
        //             set::gap($setting['gap']),
        //         ),
        //     )
        // )
    ),
    history()
);

