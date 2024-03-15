<?php
declare(strict_types=1);
/**
* The uploadimages file of file module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     file
* @link        https://www.zentao.net
*/

namespace zin;

$selectorID    = uniqid('uploader');
$uploadOptions = array();
$uploadOptions['uploadUrl']        = createLink('file', 'uploadImages', "module=$module&params=$params&uid=$uid");
$uploadOptions['locateUrl']        = createLink('file', 'uploadImages', "module=$module&params=$params&uid=$uid&locate=true");
$uploadOptions['errorUploadEmpty'] = $lang->file->errorUploadEmpty;
$uploadOptions['uploadingImages']  = $lang->file->uploadingImages;
$uploadOptions['chunkSize']        = 1024 * 1024;

set::title($lang->uploadImages);
set::titleClass('flex-none');
to::header(span(setClass('text-gray text-sm font-normal'), $lang->uploadImagesTip));

imageSelector
(
    set::_id($selectorID),
    set::className('surface'),
    set::name('uploader'),
    set::tip(false),
    set::onChange(jsCallback('event')->do('$(event.target).closest(".modal-dialog").find(".btn-upload").toggleClass("disabled", !event.target.files.length)')),
);

toolbar
(
    btn
    (
        setClass('primary btn-upload'),
        set::disabled(true),
        on::click()->call('uploadImages', "#$selectorID", $uploadOptions, jsRaw('$this')),
        $lang->file->beginUpload,
        span(setClass('as-progress'))
    )
);
