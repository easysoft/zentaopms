<?php
declare(strict_types=1);
/**
 * The obtain view file of extension module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     extension
 * @link        https://www.zentao.net
 */
namespace zin;

$searchForm = div
(
    setClass('mb-5'),
    inputGroup
    (
        input
        (
            set::name('key'),
            set::value($this->post->key ? $this->post->key : ''),
            set::placeholder($lang->extension->bySearch)
        ),
        span
        (
            setClass('input-group-btn'),
            btn
            (
                icon('search'),
                on::click('searchExtension')
            )
        )
    )
);

$menuItems = array();
foreach(array('byUpdatedTime', 'byAddedTime', 'byDownloads') as $listType)
{
    $active = (strtolower($listType) == $type) ? 'active' : '';
    $menuItems[] = li
    (
        setClass('menu-item'),
        a
        (
            setClass($active),
            set::href(createLink('extension', 'obtain', "type=$listType")),
            $lang->extension->$listType
        )
    );
}

$featureItems = array();
foreach($lang->extension->featureBar['browse'] as $browseType => $browseLabel)
{
    $featureItems[] = li
    (
        setClass('nav-item'),
        a
        (
            set('href', createLink('extension', 'browse', "type=$browseType")),
            set('data-id', $browseType),
            $browseLabel
        )
    );
}

featurebar($featureItems);

toolbar
(
    hasPriv('extension', 'upload') ? item(set(array
    (
        'icon' => 'cog',
        'text' => $lang->extension->upload,
        'class' => 'ghost',
        'url' => createLink('extension', 'upload'),
        'data-toggle' => 'modal'
    ))) : null,
    hasPriv('extension', 'obtain') ? item(set(array
    (
        'icon'  => 'download-alt',
        'text'  => $lang->extension->obtain,
        'class' => 'primary',
        'url'   => createLink('extension', 'obtain')
    ))) : null
);

foreach($moduleTree as $module) $module->url = createLink('extension', 'obtain', "type=bymodule&param=" . base64_encode($module->id));

sidebar
(
    setClass('pb-5'),
    div
    (
        setClass('fast-menu bg-white p-4 shadow-sm rounded rounded-sm'),
        $searchForm,
        menu
        (
            setClass('p-0'),
            $menuItems
        )
    ),
    moduleMenu
    (
        setClass('module-menu'),
        set::title($lang->extension->byCategory),
        set::modules($moduleTree),
        set::activeKey($moduleID),
        set::closeLink(createLink('extension', 'obtain')),
        set::showDisplay(false)
    )
);

$extensionItems = array();
$extensionCount = count($extensions);

$i = 1;
foreach($extensions as $extension)
{
    $currentRelease = $extension->currentRelease;
    $latestRelease  = isset($extension->latestRelease) ? $extension->latestRelease : '';

    $labelClass = $extension->offcial ? 'secondary' : 'warning';

    $extensionInfo = array();
    $extensionInfo[] = $lang->extension->author . ': ';
    $extensionInfo[] = html($extension->author);
    $extensionInfo[] = ' ' . $lang->extension->downloads . ': ' . $extension->downloads;
    $extensionInfo[] = ' ' . $lang->extension->compatible . ': ' . $lang->extension->compatibleList[$currentRelease->compatible];
    if(!empty($currentRelease->depends))
    {
        $extensionInfo[] = ' ' . $lang->extension->depends . ': ';
        foreach(json_decode($currentRelease->depends, true) as $code => $limit)
        {
            $extensionInfo[] .= $code;
            if($limit != 'all')
            {
                $extensionInfo[] .= '(' . !empty($limit['min']) . '>= v' . $limit['min'];
                $extensionInfo[] .= !empty($limit['max']) . '<= v' . $limit['max'] . ') ';
            }
        }
    }

    $btnItems = array();
    $btnItems[] = array('text' => $lang->extension->view, 'data-url' => $extension->viewLink, 'data-toggle' => 'modal', 'data-type' => 'iframe', 'data-size' => array('width' => 1024, 'height' => 600));
    if($currentRelease->public)
    {
        if($extension->type != 'computer' && $extension->type != 'mobile')
        {
            if(isset($installeds[$extension->code]))
            {
                if($installeds[$extension->code]->version != $extension->latestRelease->releaseVersion && $this->extension->checkVersion($extension->latestRelease->zentaoCompatible))
                {
                    $upgradeLink = inlink('upgrade',  "extension=$extension->code&downLink=" . helper::safe64Encode($currentRelease->downLink) . "&md5=$currentRelease->md5&type=$extension->type");
                    $btnItems[] = array('url' => $upgradeLink, 'text' => $lang->extension->upgrade, 'data-toggle' => 'modal');
                }
                else
                {
                    $btnItems[] = array('url' => 'javascript', 'text' => $lang->extension->upgrade, 'disabled' => 'disabled', 'class' => 'text-success');
                }
            }
        }
    }
    $btnItems[] = array('text' => $lang->extension->downloadAB, 'url' => $currentRelease->downLink, 'target' => '_blank');
    $btnItems[] = array('text' => $lang->extension->site, 'url' => $extension->site, 'target' => '_blank');

    $stars = html::printStars($extension->stars, false);

    $extensionItems[] = div
    (
        setClass('pb-2' . ($i < $extensionCount ? ' border-b' : '') . ($i > 1 ? ' mt-5' : '')),
        div
        (
            setClass('font-bold mb-2'),
            $extension->name . "($currentRelease->releaseVersion)",
            span
            (
                setClass("label $labelClass size-sm font-medium ml-2"),
                $lang->extension->obtainOfficial[$extension->offcial]
            ),
            $latestRelease && $latestRelease->releaseVersion != $currentRelease->releaseVersion ? div
            (
                setClass('pull-right text-sm text-warning'),
                html(sprintf($lang->extension->latest, $latestRelease->viewLink, $latestRelease->releaseVersion, $latestRelease->zentaoCompatible))
            ) : null
        ),
        div
        (
            setClass('mb-2'),
            $extension->abstract
        ),
        div
        (
            div
            (
                setclass('mb-2'),
                $extensionInfo
            ),
            span
            (
                $lang->extension->grade . ': ',
                html($stars)
            ),
            div
            (
                setClass('pull-right'),
                btnGroup
                (
                    set::items($btnItems)
                )
            )
        )
    );
    $i ++;
}

if($pager->recTotal)
{
    $extensionItems[] = pager
    (
        set::style(array('float' => 'right')),
        set::type('full'),
        set::page($pager->pageID),
        set::recTotal($pager->recTotal),
        set::recPerPage($pager->recPerPage)
    );
}

div
(
    setClass('flex col gap-y-1 p-5 bg-white'),
    $extensions ? $extensionItems : div
    (
        setClass('alert ghost text-danger flex items-center'),
        icon
        (
            'exclamation-sign',
            set::size('2x'),
            set('class', 'alert-icon')
        ),
        div
        (
            div
            (
                setClass('font-bold text-lg'),
                $lang->extension->errorOccurs
            ),
            p
            (
                html($lang->extension->errorGetExtensions)
            )
        )
    )
);

render();

