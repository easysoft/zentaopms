<?php
declare(strict_types=1);
/**
 * The upload view file of extension module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Tingting Dai <daitingting@easycorp.ltd>
 * @package     extension
 * @link        https://www.zentao.net
 */
namespace zin;

$app->loadLang('file');
if(!empty($error))
{
    div
    (
        html($error),
        btn
        (
            set('data-load', 'modal'),
            set('url', createLink('extension', 'upload')),
            set::type('primary'),
            $lang->extension->refreshPage
        )
    );
}
else
{
    modalHeader
    (
        set::title($lang->extension->upload),
        set::titleClass('font-bold')
    );

    form
    (
        formGroup
        (
            upload
            (
                set::multiple(false),
                set::limitSize($maxUploadSize . 'B'),
                set::exceededSizeHint(sprintf($lang->file->errorFileSize, $maxUploadSize))
            )
        ),
        set::submitBtnText($lang->extension->install)
    );
}

render();
