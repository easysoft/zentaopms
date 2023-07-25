<?php
declare(strict_types=1);
/**
* The UI file of file module of ZenTaoPMS.
*
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Wangy Yidong <yidong@easycorp.ltd>
* @package     file
* @link        https://www.zentao.net
*/

namespace zin;

$sessionString = session_name() . '=' . session_id();

$liItems = array();
foreach($files as $file)
{
    if(!common::hasPriv('file', 'download')) continue;

    $uploadDate = $lang->file->uploadDate . substr($file->addedDate, 0, 10);
    $fileTitle  = $file->title;
    if(strpos($file->title, ".{$file->extension}") === false && $file->extension != 'txt') $fileTitle .= ".{$file->extension}";

    $imageWidth = 0;
    if(stripos('jpg|jpeg|gif|png|bmp', $file->extension) !== false)
    {
        $imageSize  = $this->file->getImageSize($file);
        $imageWidth = $imageSize[0];
    }

    $fileSize = $this->file->convertFileSize($file->size);

    $downloadLink  = $this->createLink('file', 'download', "fileID=$file->id");
    $downloadLink .= strpos($downloadLink, '?') === false ? '?' : '&';
    $downloadLink .= $sessionString;

    $objectType = zget($this->config->file->objectType, $file->objectType);

    $liItems[]  = h::li
    (
        set::title($uploadDate),
        a(set::href($downloadLink), set('onclick', "return downloadFile({$file->id}, '{$file->extension}', $imageWidth, '{$file->title}')"), icon('file-text'), $fileTitle, span(setClass('text-gray'), $fileSize)),
        common::hasPriv($objectType, 'edit', $object) ? span
        (
            setClass('right-icon'),
            common::hasPriv('file', 'edit')   ? a(set::style(array('color', '#0c64eb')), set::href(helper::createLink('file', 'edit', "fileID={$file->id}")), set::title($lang->file->edit), set('data-toggle', 'modal'), set('data-size', 'sm'), $lang->file->edit) : null,
            common::hasPriv('file', 'delete') ? a(set::style(array('color', '#0c64eb')), set::href(helper::createLink('file', 'delete', "fileID={$file->id}")), set::title($lang->delete), setClass('ajax-submit'), $lang->delete) : null,
        ) : null,
    );
}

h::ul
(
    setClass('files-list'),
    $liItems,
);

h::js
(
<<<JAVASCRIPT
window.downloadFile = function(fileID, extension, imageWidth, fileTitle)
{
    if(!fileID) return;
    let fileTypes      = 'txt,jpg,jpeg,gif,png,bmp';
    let windowWidth    = $(window).width();
    let width          = (windowWidth > imageWidth) ? ((imageWidth < windowWidth * 0.5) ? windowWidth * 0.5 : imageWidth) : windowWidth;
    let checkExtension = fileTitle.lastIndexOf('.' + extension) == (fileTitle.length - extension.length - 1);

    let url = $.createLink('file', 'download', 'fileID=' + fileID + '&mouse=left');
    url    += url.indexOf('?') >= 0 ? '&' : '?';
    url    += '{$sessionString}';

    if(fileTypes.indexOf(extension) >= 0 && checkExtension && config.onlybody != 'yes')
    {
        $('<a>').modalTrigger({url: url, type: 'iframe', width: width}).trigger('click');
    }
    else
    {
        window.open(url, '_blank');
    }
    return false;
};
JAVASCRIPT
);

render();
