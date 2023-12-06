<?php
declare(strict_types=1);
/**
 * The editor view file of dir module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     editor
 * @version     $Id$
 * @link        https://www.zentao.net
 */
namespace zin;
set::zui(true);

if(empty($filePath)) return;

$jsRoot = $config->webRoot . 'js/';
h::importJs($jsRoot . 'monaco-editor/min/vs/loader.js');

$showFileBox = ($action and $action != 'edit' and $action != 'newPage' and $action != 'override' and $action != 'extendControl');
$fileSuffix  = $lang->editor->examplePHP;
if($action == 'newHook') $fileSuffix = $lang->editor->exampleHook;
if($action and $action == 'extendOther' and strpos(basename($filePath), '.js') !== false or $action == 'newJS')   $fileSuffix = $lang->editor->exampleJs;
if($action and $action == 'extendOther' and strpos(basename($filePath), '.css') !== false or $action == 'newCSS') $fileSuffix = $lang->editor->exampleCss;

jsVar('jsRoot', $jsRoot);
jsVar('clientLang', $app->clientLang);
jsVar('isShowContent', !empty($showContent));
jsVar('showContent', !empty($showContent) ? $showContent : '');
jsVar('fileContent', $fileContent);
jsVar('language', $fileExtension == 'js' ? 'javascript' : $fileExtension);

formPanel
(
    set::actions(false),
    set::url(inlink('save', "filePath=$safeFilePath&action=$action")),
    div
    (
        setClass('heading'),
        icon('edit'),
        $filePath ? span(span(setClass('font-bold'), $lang->editor->filePath), h::code($filePath)) : null
    ),
    empty($showContent) ? null : div(p(setClass('font-bold'), $lang->editor->sourceFile), div(setID('showContentEditor'))),
    div(div(setID('fileContentEditor')), input(setID('fileContent'), set::name('fileContent'), set::type('hidden'))),
    $showFileBox ? div
    (
        setID('fileNameBox'),
        inputControl
        (
            set::prefix($lang->editor->fileName),
            input(set::name('fileName')),
            set::suffix($fileSuffix)
        )
    ) : input(setID('fileName'), set::name('fileName'), set::type('hidden'), set::value(basename($filePath))),
    div
    (
        setClass('flex items-center form-actions'),
        btn(set('btnType', 'submit'), setClass('primary'), set::onclick('syncFileContent()'), $lang->save),
        ($action and $action != 'edit' and $action != 'newPage') ? checkbox(set::name('override'), set::value(1), set::text($lang->editor->isOverride)) : null
    )
);

render('pagebase');
