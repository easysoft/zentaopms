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

h::css
(
<<<CSS
.file {padding-top: 2px;}
ul.files-list {margin-bottom: unset}
.files-list>li>a {display: inline; word-wrap: break-word;}
.files-list>li>.right-icon {opacity: 1;}
.fileAction {color: #0c64eb !important;}
.renameFile {display: flex;}
.renameFile .input-group {margin-left: 10px;}
.renameFile .icon {margin-top: 8px;}
.renameFile .input-group-addon {width: 60px;}
.backgroundColor {background: #eff5ff; }
.icon.icon-file-text {padding-left: 7px}
.right-icon .btn {padding: 0 6px;}
CSS
);

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

    $fileSize = 0;
    /* Show size info. */
    if($file->size < 1024)
    {
        $fileSize = $file->size . 'B';
    }
    elseif($file->size < 1024 * 1024)
    {
        $file->size = round($file->size / 1024, 2);
        $fileSize = $file->size . 'K';
    }
    elseif($file->size < 1024 * 1024 * 1024)
    {
        $file->size = round($file->size / (1024 * 1024), 2);
        $fileSize = $file->size . 'M';
    }
    else
    {
        $file->size = round($file->size / (1024 * 1024 * 1024), 2);
        $fileSize = $file->size . 'G';
    }

    $downloadLink  = $this->createLink('file', 'download', "fileID=$file->id");
    $downloadLink .= strpos($downloadLink, '?') === false ? '?' : '&';
    $downloadLink .= $sessionString;

    $objectType = zget($this->config->file->objectType, $file->objectType);

    /* Determines whether the file supports preview. */
    if($file->extension == 'txt')
    {
        $extension = 'txt';
        if(($postion = strrpos($file->title, '.')) !== false) $extension = substr($file->title, $postion + 1);
        if($extension != 'txt') $mode = 'down';
        $file->extension = $extension;
    }

    $canPreview = false;
    if(stripos('txt|jpg|jpeg|gif|png|bmp', $file->extension) !== false) $canPreview = true;
    if(isset($this->config->file->libreOfficeTurnon) and $this->config->file->libreOfficeTurnon == 1)
    {
        $officeTypes = 'doc|docx|xls|xlsx|ppt|pptx|pdf';
        if(stripos($officeTypes, $file->extension) !== false) $canPreview = true;
    }

    if(strrpos($file->title, '.') !== false)
    {
        /* Fix the file name exe.exe */
        $title     = explode('.', $file->title);
        $extension = end($title);
        if($file->extension == 'txt' && $extension != $file->extension) $file->extension = $extension;
        array_pop($title);
        $file->title = implode('.', $title);
    }

    $liItems[]  = h::li
    (
        setClass('file'),
        set::title($uploadDate),
        span
        (
            setID("fileTitle{$file->id}"),
            icon('file-text'), 
            $fileTitle, 
            span(setClass('text-gray'), $fileSize),
        ),
        common::hasPriv($objectType, 'view', $object) ? span
        (
            setClass('right-icon hidden'),
            ($canPreview) ? a(set::style(array('color', '#0c64eb')), set::href($downloadLink), set('onclick', "return downloadFile({$file->id}, '{$file->extension}', $imageWidth, '{$file->title}')"), set::title($lang->file->preview), icon('eye')) : null,
            common::hasPriv('file', 'download') ? a(set::style(array('color', '#0c64eb')), set::href(helper::createLink('file', 'download', "fileID={$file->id}")), set::target('_blank'), set::title($lang->file->downloadFile), icon('download')) : null,
            common::hasPriv($objectType, 'edit', $object) && $showEdit && common::hasPriv('file', 'edit')   ? a(set::style(array('color', '#0c64eb')), set::href('###'), setClass('edit'), setID("renameFile{$file->id}"), set::title($lang->file->edit), set('onclick', "showRenameBox{$file->id}"), icon('pencil-alt')) : null,
            common::hasPriv($objectType, 'edit', $object) && $showDelete && common::hasPriv('file', 'delete') ? a(set::style(array('color', '#0c64eb')), set::href('###'), set::title($lang->delete), set('onclick', "deleteFile($file->id, this)"), icon('trash')) : null,
        ) : null,
        on::mouseover('showFileActions'),
        on::mouseout('hideFileActions'),
    );
    $liItems[]  = h::li
    (
        setClass('file hidden'),
        div
        (
            setClass('renameFile'),
            setID("renameBox{$file->id}"),
            icon('file-text'),
            inputGroup
            (
                input(setID("fileName{$file->id}"), setValue($file->title)),
                input(set::type('hidden'), setID("extension{$file->id}"), setValue($file->extension)),
                h::strong(setClass('input-group-addon'), ".{$file->extension}"),
            ),
            div
            (
                setClass('input-group-btn'),
                btn(setClass('success file-name-confirm'), set::style(array('border-radius' => '0px 2px 2px 0px', 'border-left-color' => 'transparent')), set('onclick', "setFileName($file->id)"), icon('check')),
                btn(setClass('gray file-name-cancel'),     set::style(array('border-radius' => '0px 2px 2px 0px', 'border-left-color' => 'transparent')), set('onclick', "showFile($file->id)"), icon('close')),
            )

        )
    );
}

if($fieldset == 'true')
{
    sectionList
    (
        set::title($lang->file->common),
        set::content
        (
            h::ul
            (
                setClass('files-list'),
                $liItems,
            )
        )
    );
}
else
{
    h::ul
    (
        setClass('files-list'),
        $liItems,
    );
}

$deleteUseForm = ($showDelete && $method == 'edit');
$isInModal     = isInModal();
h::js
(
<<<JAVASCRIPT
window.showFileActions = function(e)
{
    $(e.target).find('span.right-icon').removeClass("hidden");
    $(e.target).addClass('backgroundColor');
};

window.hiddenFileActions = function(e)
{
    $(e.target).find('span.right-icon').addClass("hidden");
    $(e.target).removeClass('backgroundColor');
};

window.deleteFile = function(fileID, obj)
{
    if(!fileID) return;

    if({$deleteUseForm})
    {
        $('<input />').attr('type', 'hidden').attr('name', 'deleteFiles[' + fileID + ']').attr('value', fileID).appendTo('ul.files-list');
        $(obj).closest('li.file').addClass('hidden');
    }
    else
    {
        $.ajaxSubmit($.createLink('file', 'delete', 'fileID=' + fileID));
    }
};

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

    if(fileTypes.indexOf(extension) >= 0 && checkExtension && {$isInModal})
    {
        $('<a>').modalTrigger({url: url, type: 'iframe', width: width}).trigger('click');
    }
    else
    {
        window.open(url, '_blank');
    }
    return false;
};

window.showRenameBox = function(fileID)
{
    $('#renameFile' + fileID).closest('li').addClass('hidden');
    $('#renameBox' + fileID).closest('li').removeClass('hidden');
};

window.showFile = function(fileID)
{
    $('#renameBox' + fileID).closest('li').addClass('hidden');
    $('#renameFile' + fileID).closest('li').removeClass('hidden');
}

window.setFileName = function(fileID)
{
    let fileName  = $('#fileName' + fileID).val();
    let extension = $('#extension' + fileID).val();
    let postData  = {'fileName' : fileName, 'extension' : extension};
    $.post($.createLink('file', 'edit', 'fileID=' + fileID), postData, function(data)
    {
        data = JSON.parse(data);
        $('#fileTitle' + fileID).html("<i class='icon icon-file-text'></i> &nbsp;" + data['title']);
        $('#renameFile' + fileID).closest('li').removeClass('hidden');
        $('#renameBox' + fileID).closest('li').addClass('hidden');
    })
}
JAVASCRIPT
);

render();
