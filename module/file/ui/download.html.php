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
        if($encoding != 'UTF-8') $fileContent = helper::convertEncoding($fileContent, $encoding, $config->charset);
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
            select(setID('charset'), set::items($config->file->charset), set::value($charset), set('onchange', 'setCharset(this)')),
        )
    ) : null,
);

if($fileType == 'image')
{
    div
    (
        setID('imageFile'),
        h::img(set::src($this->createLink('file', 'read', "fileID={$file->id}")))
    );
}
else
{
    div
    (
        setID('txtFile'),
        h::pre(set::style(array('background-color' => 'rgba(var(--color-gray-200-rgb), 1)')), $fileContent),
    );
}

h::js
(
<<<JAVASCRIPT
window.setCharset = function(obj)
{
    let charset = $(obj).val();
    let link    = $.createLink('file', 'download', 'fileID={$file->id}&mouse=left');
    link       += link.indexOf('?') >= 0 ? '&' : '?';
    link       += 'charset=' + charset;
    loadModal(link, $(obj).closest('.modal.show').attr('id'));
};
JAVASCRIPT
);

render();
