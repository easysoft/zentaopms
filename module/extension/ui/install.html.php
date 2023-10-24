<?php
declare(strict_types=1);
/**
 * The install view file of extension module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     extension
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader
(
    set::title($title),
    set::titleClass('font-bold mr-2 '),
    isset($license) && $upgrade == 'yes' ? to::suffix(sprintf($lang->extension->upgradeVersion, $this->post->installedVersion, $this->post->upgradeVersion)) : null,
);

$fileItems = array();
foreach($files as $fileName => $md5)
{
    $fileItems[] = li
    (
        $fileName
    );
}

!empty($error) ? div
(
    div
    (
        setClass('mb-2'),
        sprintf($lang->extension->installFailed, $installType)
    ),
    p
    (
        setClass('text-danger mb-3'),
        html($error),
    ),
    btn
    (
        set::type('primary'),
        set('data-load', 'modal'),
        $lang->extension->refreshPage,
    )
) : null;

empty($error) && isset($license) ? div
(
    setClass('text-center'),
    div
    (
        setClass('font-bold mb-2'),
        $lang->extension->license
    ),
    p
    (
        setClass('mb-2'),
        textarea
        (
            set::rows(15),
            set::name('license'),
            set::value($license),
        )
    ),
    btn
    (
        set::type('primary'),
        set('data-load', 'modal'),
        set('url', $agreeLink),
        $lang->extension->agreeLicense,
    )
) : null;

empty($error) && !isset($license) ? div
(
    div
    (
        setClass('mb-2'),
        sprintf($lang->extension->installFinished, $installType)
    ),
    btn
    (
        set::type('success'),
        set('load-url', createLink('extension', 'browse')),
        set::onclick('window.loadParentUrl(this)'),
        $lang->extension->viewInstalled,
    ),
    div
    (
        setClass('alert success-outline mt-3'),
        div
        (
            setClass('alert-content'),
            p 
            (
                $lang->extension->successDownloadedPackage,
            ),
            p 
            (
                $lang->extension->successCopiedFiles,
            ),
            ul
            (
                $fileItems
            ),
            p 
            (
                $lang->extension->successInstallDB,
            )
        )
    )
) : null;

render();
