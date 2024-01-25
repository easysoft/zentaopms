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

jsVar('uploadUrl',   createLink('file', 'uploadImages', "module=$module&params=$params&uid=$uid"));
jsVar('locateUrl',   createLink('file', 'uploadImages', "module=$module&params=$params&uid=$uid&locate=true"));
jsVar('uploadEmpty', $lang->file->errorUploadEmpty);
jsVar('uploadingImages', $lang->file->uploadingImages);

set::title(array('html' => div(setClass('uploadTitle'), span($lang->uploadImages), span(set::className('text-gray text-sm font-normal'), $lang->uploadImagesTip))));
uploadImgs(set::name('uploader'), set::tip($lang->file->uploadImagesTip));
div(btn(setClass('primary uploadBtn'), set('onclick', 'uploadImages()'), $lang->file->beginUpload));

render();
