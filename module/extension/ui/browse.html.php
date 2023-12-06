<?php
declare(strict_types=1);
/**
 * The browse view file of extension module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     extension
 * @link        https://www.zentao.net
 */
namespace zin;

$extensionItems = array();
$i = 1;
foreach($extensions as $extension)
{
    $expiredDate = $this->extension->getExpireDate($extension);

    $extensionInfo = array();
    $extensionInfo[] = $lang->extension->version . ': ';
    $extensionInfo[] = h::i($extension->version);
    $extensionInfo[] = ' ' . $lang->extension->author . ': ';
    $extensionInfo[] = h::i($extension->author);
    if(!empty($expiredDate))
    {
        $extensionInfo[] = ' ' . $lang->extension->expiredDate . ': ';
        $extensionInfo[] = $expiredDate != 'life' ? h::i($expiredDate) : h::i($lang->extension->life);
    }

    $btnItems = array();
    if(isset($extension->viewLink))       $btnItems[] = array('text' => $lang->extension->view,  'url' => $extension->viewLink, 'data-toggle' => 'modal', 'data-type' => 'iframe', 'data-size' => array('width' => 1024, 'height' => 600));
    if($extension->status == 'installed') $btnItems[] = array('text' => $lang->extension->structure,  'url' => createLink('extension', 'structure', "extension={$extension->code}"),  'data-toggle' => 'modal');
    if($extension->status == 'installed' && !empty($extension->upgradeLink)) $btnItems[] = array('text' => $lang->extension->upgrade,  'url' => $extension->upgradeLink, 'data-toggle' => 'modal');
    if($extension->type != 'patch')
    {
        if($extension->status == 'installed')   $btnItems[] = array('text' => $lang->extension->deactivate, 'url' => createLink('extension', 'deactivate', "extension={$extension->code}"), 'data-toggle' => 'modal');
        if($extension->status == 'deactivated') $btnItems[] = array('text' => $lang->extension->activate,   'url' => createLink('extension', 'activate', "extension={$extension->code}"),   'data-toggle' => 'modal');
        if($extension->status == 'available')   $btnItems[] = array('text' => $lang->extension->install,    'url' => createLink('extension', 'install', "extension={$extension->code}"),    'data-toggle' => 'modal');
        if($extension->status == 'available')   $btnItems[] = array('text' => $lang->extension->erase,      'url' => createLink('extension', 'erase', "extension={$extension->code}"),      'data-toggle' => 'modal');
        if($extension->status == 'installed' || $extension->status == 'deactivated') $btnItems[] = array('text' => $lang->extension->uninstall,  'url' => createLink('extension', 'uninstall', "extension={$extension->code}"),  'data-toggle' => 'modal');
    }
    $btnItems[] = array('text' => $lang->extension->site, 'url' => $extension->site, 'target' => '_blank');

    $extensionItems[] = div
    (
        setClass('mb-2'),
        div
        (
            setClass('font-bold mb-2'),
            $extension->name
        ),
        div
        (
            setClass('mb-2'),
            $extension->desc
        ),
        div
        (
            $extensionInfo,
            div
            (
                setClass('pull-right'),
                btnGroup
                (
                    set::items($btnItems)
                )
            )
        ),
        $i < count($extensions) ? hr() : null
    );

    $i++;
}

featurebar
(
    set::current($tab),
    set::linkParams("status={key}")
);

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

div
(
    setClass('flex col gap-y-1 p-5 bg-white'),
    $extensionItems
);

render();
