<?php
/**
 * The create view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     doc
 * @version     $Id: create.html.php 975 2010-07-29 03:30:25Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php if($doc->contentType == 'html')     include '../../common/view/kindeditor.html.php';?>
<?php if($doc->contentType == 'markdown') include '../../common/view/markdown.html.php';?>
<?php js::set('needUpdateContent', $doc->content != $doc->draft);?>
<?php js::set('confirmUpdateContent', $lang->doc->confirmUpdateContent);?>
<?php js::set('docID', $doc->id);?>
<?php js::set('draft', $doc->draft);?>
<?php js::set('holders', $lang->doc->placeholder);?>
<?php js::set('type', 'doc');?>
<style>
#main {padding: 0;}
.container {padding: 0 !important;}
#mainContent {padding: 0 !important;}
.doc-title input {border: unset; font-size: 18px; font-weight: bold; color: #3c4353; padding-left: 16px;}
.doc-title .form-control:focus {border: unset; box-shadow: unset;}
.doc-title input::-webkit-input-placeholder {color: #D8DBDE;}
.doc-title.required:after {top: 4px; right: 0; left: 12px; display: inline-table;}
#submit {margin-right: 16px;}
#headerBox {border-bottom: 1px solid #e3e3e3;}
#headerBox td:last-child {padding-right: 24px;}
#editorContent {padding: 0;}

#contentBox {padding: 0; width: 100%;}
.ke-container {overflow: visible;}
.ke-container, .contenthtml {border: unset; background: #efefef;}
.ke-container.focus {box-shadow: unset; border-color: unset;}
.ke-toolbar {padding-left: 20px; width: 100%; height: 30px;}
.ke-edit {border-top: 1px solid rgb(220, 220, 220)}
.ke-edit, .CodeMirror {margin: 8px 200px 0 200px; background: #fff;}
.kindeditor-ph {padding: 20px 20px 0 20px !important;}
.editor-toolbar {background: #fff; padding-left: 20px; border-right: unset; border-top: unset; height: 30px;}
.CodeMirror {padding: 20px 20px 0 20px;}
.CodeMirror.CodeMirror-wrap {border-left: 0; border-right: 0; border-bottom: 0;}
.ke-statusbar {display: none;}

.article-content {padding: 8px 20px;}

#noticeAcl {margin-left: 10px; vertical-align: middle;}

#basicInfoLink {border: unset;}

#modalBasicInfo .modal-content {overflow-x: hidden; overflow-y: scroll;}
#modalBasicInfo .modal-body {padding-bottom: 10px;}
.modal-title {font-size: 14px !important; font-weight: 700 !important;}
#basicInfoBox tfoot td {padding-bottom: 0;}
</style>
<?php $backLink = $this->createLink('doc', 'objectlibs', "type=$type&objectID=$objectID&libID={$lib->id}&docID={$doc->id}");?>
<div id="mainContent" class="main-content">
  <form class="load-indicator main-form form-ajax" id="dataform" method='post' enctype='multipart/form-data'>
    <table class='table table-form'>
      <tbody>
        <tr id='headerBox'>
          <td width='90px'><?php echo html::backButton("<i class='icon icon-back icon-sm'></i> " . $lang->goback, "id='backBtn'", 'btn btn-secondary');?></td>
          <td class="doc-title" colspan='3'><?php echo html::input('title', $doc->title, "placeholder='{$lang->doc->titlePlaceholder}' class='form-control' required");?></td>
          <td class="text-right">
            <?php echo html::submitButton('', "data-placement='bottom'", 'btn btn-primary');?>
            <?php echo html::a('#modalBasicInfo', "<i class='icon icon-cog-outline'></i> " . $lang->settings, '', "data-toggle='modal' id='basicInfoLink' class='btn'");?>
          </td>
        </tr>
        <tr>
          <td colspan='5' id="editorContent">
            <div class="main-row fade in">
              <div id='contentBox' class="main-col">
                <div class='contenthtml'><?php echo html::textarea('content', htmlSpecialString($doc->content), "style='width:100%;'");?></div>
                <?php echo html::hidden('contentType', $doc->contentType);?>
                <?php echo html::hidden('type', 'text');?>
                <?php echo html::hidden('editedDate', $doc->editedDate);?>
              </div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <div class='modal fade modal-basic' id='modalBasicInfo' data-scroll-inside='true'>
      <div class='modal-dialog'>
        <div class='modal-content with-padding'>
          <div class='modal-header'>
            <h2 class='modal-title'><?php echo $lang->doc->basicInfo;?></h2>
          </div>
          <div class='modal-body'>
            <table class='table table-form' id="basicInfoBox">
              <tr>
                <th class='w-100px'><?php echo $lang->doc->lib;?></th>
                <td colspan="2" class="required"><?php echo html::select('lib', $libs, $doc->lib, "class='form-control chosen' onchange=loadDocModule(this.value)");?></td>
              </tr>
              <tr>
                <th><?php echo $lang->doc->module;?></th>
                <td colspan="2">
                  <span id='moduleBox'><?php echo html::select('module', $moduleOptionMenu, $doc->module, "class='form-control chosen'");?></span>
                </td>
              </tr>
              <tr>
                <th><?php echo $lang->doc->keywords;?></th>
                <td colspan='2'><?php echo html::input('keywords', $doc->keywords, "class='form-control' placeholder='{$lang->doc->keywordsTips}'");?></td>
              </tr>
              <tr id='fileBox'>
                <th><?php echo $lang->doc->files;?></th>
                <td colspan='2'><?php echo $this->fetch('file', 'buildform');?></td>
              </tr>
              <tr>
                <th><?php echo $lang->doc->mailto;?></th>
                <td colspan="2">
                  <div class="input-group">
                    <?php
                    echo html::select('mailto[]', $users, $doc->mailto, "multiple class='form-control picker-select' data-drop-direction='top'");
                    echo $this->fetch('my', 'buildContactLists');
                    ?>
                  </div>
                </td>
              </tr>
              <tr>
                <th class="th-control"><?php echo $lang->doclib->control;?></th>
                <td colspan='2'>
                  <?php $acl = $lib->acl == 'private' ? 'private' : $doc->acl;?>
                  <?php echo html::radio('acl', $lang->doc->aclList, $acl, "onchange='toggleAcl(this.value, \"doc\")'")?>
                  <span class='text-info' id='noticeAcl'><?php echo $lang->doc->noticeAcl['doc'][$acl];?></span>
                </td>
              </tr>
              <tr id='whiteListBox' class='hidden'>
                <th><?php echo $lang->doc->whiteList;?></th>
                <td colspan='2'>
                  <div class='input-group w-p100'>
                    <span class='input-group-addon groups-addon'><?php echo $lang->doclib->group?></span>
                    <?php echo html::select('groups[]', $groups, $doc->groups, "class='form-control picker-select' multiple data-drop-direction='top'")?>
                  </div>
                  <div class='input-group w-p100'>
                    <span class='input-group-addon'><?php echo $lang->doclib->user?></span>
                    <?php echo html::select('users[]', $users, $doc->users, "class='form-control picker-select' multiple data-drop-direction='top'")?>
                  </div>
                </td>
              </tr>
              <tfoot>
                <tr>
                  <td colspan='3' class='text-center'><?php echo html::a('javascript:void(0)', $lang->doc->confirm, '', "class='btn btn-primary btn-wide'");?></td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
<script>
$(function()
{
    var contentHeight = $(document).height() - 100;
    setTimeout(function(){$('.ke-edit-iframe, .ke-edit, .ke-edit-textarea, .CodeMirror').height(contentHeight);}, 100);
    $('#modalBasicInfo .modal-content').css('max-height', contentHeight);

    $(document).on('click', '#modalBasicInfo tfoot .btn', function() {$('#modalBasicInfo').modal('hide');});

    $('iframe.ke-edit-iframe').contents().find('.article-content').css('padding', '20px 20px 0 20px');
})
</script>
<?php js::set('noticeAcl', $lang->doc->noticeAcl['doc']);?>
<?php include '../../common/view/footer.html.php';?>
