<?php
declare(strict_types=1);
/**
 * The view view file of system module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     system
 * @link        https://www.zentao.net
 */
namespace zin;
global $app;
if(isset($system->latestDeployStatus)) $app->loadLang('deploy');
$latestDeploy = zget($system, 'latestDeploy', '');
detailHeader(to::title(entityLabel(set(array('entityID' => $system->id, 'level' => 1, 'text' => $system->name, 'className' => 'clip', 'title' => $system->name)))));
div
(
    setClass('flex flex-normal gap-x-5 justify-center'),
    div
    (
        setClass('flex-none w-2/3'),
        setID('systemInfoContainer'),
        detailBody
        (
            sectionList
            (
                section
                (
                    set::title($lang->basicInfo),
                    h::table
                    (
                        setClass('table w-full max-w-full bordered mt-4 text-center'),
                        h::thead
                        (
                            h::tr
                            (
                                h::th(set::width('200px'), $lang->system->product),
                                h::th(set::width('200px'), $lang->system->latestRelease),
                                isset($system->latestDeployStatus) ? h::th(set::width('200px'), $lang->system->latestDeployStatus) : null,
                                h::th($lang->system->appDesc),
                            )
                        ),
                        h::tr
                        (
                            h::td($product->name),
                            h::td(zget($releases, $system->latestRelease, '')),
                            isset($system->latestDeployStatus) ? h::td(zget($lang->deploy->statusList, zget($system, 'latestDeployStatus', ''), '')) : null,
                            h::td(zget($system, 'desc', ''))
                        )
                    )
                ),
                $latestDeploy ? section
                (
                    set::title($lang->system->deployInfo),
                    h::table
                    (
                        setClass('table w-full max-w-full bordered mt-4 text-center'),
                        h::thead
                        (
                            h::tr
                            (
                                h::th(set::width('120px'), $lang->system->deployType),
                                h::th(set::width('180px'), $lang->system->env),
                                h::th($lang->system->release),
                                h::th(set::width('200px'), $lang->system->deployDate),
                                h::th(set::width('120px'), $lang->system->deployStatus),
                            )
                        ),
                        h::tr
                        (
                            h::td(zget($lang->deploy->typeList, zget($latestDeploy, 'type', ''), '')),
                            h::td(zget($envPairs, zget($latestDeploy, 'env', ''), '')),
                            h::td(zget($releases, zget($latestDeploy, 'release', ''), '')),
                            h::td(zget($latestDeploy, 'createdDate', '')),
                            h::td(zget($lang->deploy->statusList, zget($latestDeploy, 'status', ''), ''))
                        )
                    )
                ) : null
            )
        )
    ),
    div
    (
        setClass('w-1/3'),
        history
        (
            set::commentUrl(createLink('action', 'comment', array('objectType' => 'system', 'objectID' => $system->id)))
        )
    )
);
