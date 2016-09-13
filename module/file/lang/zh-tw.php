<?php
/**
 * The file module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     file
 * @version     $Id: zh-tw.php 4630 2013-04-10 05:54:08Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->file = new stdclass();
$lang->file->common        = '附件';
$lang->file->uploadImages  = '多圖上傳';
$lang->file->download      = '下載附件';
$lang->file->edit          = '重命名';
$lang->file->inputFileName = '請輸入附件名稱';
$lang->file->delete        = '刪除附件';
$lang->file->label         = '標題：';
$lang->file->maxUploadSize = "<span class='red'>%s</span>";
$lang->file->applyTemplate = "應用模板";
$lang->file->tplTitle      = "模板名稱";
$lang->file->setPublic     = "設置公共模板";
$lang->file->exportFields  = "要導出欄位";
$lang->file->defaultTPL    = "預設模板";
$lang->file->setExportTPL  = "設置";

$lang->file->errorNotExists   = "<span class='red'>檔案夾 '%s' 不存在</span>";
$lang->file->errorCanNotWrite = "<span class='red'>檔案夾 '%s' 不可寫,請改變檔案夾的權限。在linux中輸入指令:sudo chmod -R 777 '%s'</span>";
$lang->file->confirmDelete    = " 您確定刪除該附件嗎？";
$lang->file->errorFileSize    = " 檔案大小已經超過限制，可能不能成功上傳！";
$lang->file->errorFileUpload  = " 檔案上傳失敗，檔案大小可能超出限制";
$lang->file->dangerFile       = " 您選擇的檔案存在安全風險，系統將不予上傳。";
$lang->file->errorSuffix      = '壓縮包格式錯誤，只能上傳zip壓縮包！';
$lang->file->errorExtract     = '解壓縮失敗！可能檔案已經損壞，或壓縮包裡含有非法上傳檔案。';
$lang->file->uploadImagesExplain = <<<EOD
<p>1、上傳檔案為包含圖片的zip壓縮包，程序會以檔案名作為標題，以圖片作為內容。</p>
<p>2、如果檔案名開頭含有 數字+下劃線，以方便排序，程序會將他們忽略。</p>
<p>3、圖片格式：jpg|jpeg|gif|png。</p>
EOD;
