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

if(!empty($error))
{
    div
    (
        setID('errorMessage'),
        $error
    );
}
else
{
    $fileContent = trim(file_get_contents($file->realPath));
    if($charset != $config->charset)
    {
        $fileContent = helper::convertEncoding($fileContent, $charset . "//IGNORE", $config->charset);
    }
    else
    {
        if(extension_loaded('mbstring'))
        {
            $encoding = mb_detect_encoding($fileContent, array('ASCII', 'UTF-8', 'GB2312', 'GBK', 'BIG5'));
            if($encoding != 'UTF-8') $fileContent = helper::convertEncoding($fileContent, (string)$encoding, $config->charset);
        }
        else
        {
            $encoding = 'UTF-8';
            if($config->default->lang == 'zh-cn') $encoding = 'GBK';
            if($config->default->lang == 'zh-tw') $encoding = 'BIG5';
            $fileContent = helper::convertEncoding($fileContent, $encoding, $config->charset);
        }
    }

    modalHeader
    (
        set::title($lang->file->preview),
        $fileType == 'txt' ? to::suffix
        (
            div
            (
                select(setID('charset'), set::name('charset'), set::items($config->file->charset), set::required(true), set::value($charset), set('onchange', 'setCharset(this)'), setClass('ml-2'))
            )
        ) : null
    );

    if($fileType == 'image')
    {
        div
        (
            setID('imageFile'),
            h::img(set::src($this->createLink('file', 'read', "fileID={$file->id}")))
        );
    }
    elseif($fileType == 'video')
    {
        div
        (
            setID('videoFile'),
            h::video(set::src($file->webPath), set::controls(true), set::autoplay(true), set::controlsList('nodownload'), set::onerror('showError()'), set::onloadedmetadata('loadedmetadata()'), set::style(array('width' => '100%'))),
            div
            (
                setClass('playfailed hide'),
                $lang->file->playFailed
            )
        );
    }
    else
    {
        div
        (
            setID('txtFile'),
            h::pre(set::style(array('background-color' => 'rgb(var(--color-gray-200-rgb))')), $fileContent)
        );
    }

$isInModal = isInModal();
h::js
(
<<<JAVASCRIPT
window.setCharset = function(obj)
{
    let charset = $(obj).val();
    let link    = $.createLink('file', 'download', 'fileID={$file->id}&mouse=left');
    link       += link.indexOf('?') >= 0 ? '&' : '?';
    link       += 'charset=' + charset;
    if("{$isInModal}") loadModal(link, $(obj).closest('.modal.show').attr('id'));
    else loadPage(link);
};

function showError()
{
    $('.playfailed').show();
}

function loadedmetadata()
{
    var videoElem      = $('video')[0];
    var metaHeight     = videoElem.videoHeight;
    var parentHeight   = window.parent.innerHeight;
    var videoMaxHeight = parentHeight - 190;
    if(videoMaxHeight < metaHeight)
    {
        $(videoElem).css('height', videoMaxHeight);
    }
}
JAVASCRIPT
);
}

render();
