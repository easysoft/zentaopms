<?php
/**
 * The create text view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2022 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     doc
 * @version     $Id: createtext.html.php 975 2022-07-14 13:49:25Z $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../common/view/markdown.html.php';?>
<?php if($objectType == 'custom' and empty($libs)):?>
<?php echo html::a(helper::createLink('doc', 'createLib', "type=custom&objectID=$objectID"), '<i class="icon icon-plus"></i> ' . $lang->doc->createLib, '', 'class="iframe hidden createCustomLib"');?>
<?php endif;?>
<?php $backLink = $this->createLink('doc', 'tableContents', "type=$linkType&objectID=$objectID&libID=$libID");?>
<div id="mainContent" class="main-content">
  <form class="load-indicator main-form form-ajax" id="dataform" method='post' enctype='multipart/form-data'>
    <table class='table table-form'>
      <tbody>
        <tr id='headerBox'>
          <td width='90px'><?php echo html::a($backLink, '<i class="icon icon-back icon-sm"></i> ' . $lang->goback, '', "class='btn btn-secondary' id='backBtn'");?></td>
          <td class="doc-title" colspan='3'><?php echo html::input('title', '', "placeholder='{$lang->doc->titlePlaceholder}' id='editorTitle' class='form-control' required");?></td>
          <td class="text-right">
            <?php echo html::a('#modalBasicInfo', $lang->release->common, '', "data-toggle='modal' id='basicInfoLink' class='btn btn-primary'");?>
          </td>
        </tr>
        <tr>
          <td colspan='5' id="editorContent">
            <div class="main-row fade in">
              <div id='contentBox' class="main-col">
                <div class='contenthtml'><?php echo html::textarea('content', '', "style='width:100%;'");?></div>
                <div class='contentmarkdown <?php if(strpos($config->doc->create->requiredFields, 'content') !== false) echo 'required'?> hidden'><?php echo html::textarea('contentMarkdown', '', "style='width:100%;'");?></div>
                <?php echo html::hidden('contentType', 'html');?>
                <?php echo html::hidden('type', 'text');?>
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
            <button type='button' class='close' data-dismiss='modal'>
              <i class="icon icon-close"></i>
            </button>
          </div>
          <div class='modal-body'>
            <table class='table table-form' id="basicInfoBox">
              <tbody>
                <tr><th class='w-100px'></th><td></td><th class='w-100px'></th><td></td></tr>
                <tr>
                  <th><?php echo $lang->doc->title?></th>
                  <td colspan='3' class='required'><?php echo html::input('copyTitle', '', "placeholder='{$lang->doc->titlePlaceholder}' class='form-control' disabled");?></td>
                </tr>
                <?php if($objectType == 'project'):?>
                <tr>
                  <th><?php echo $lang->doc->project;?></th>
                  <td class='required'><?php echo html::select('project', $objects, $objectID, "class='form-control chosen' onchange=loadExecutions(this.value)");?></td>
                  <th><?php echo $lang->doc->execution?></th>
                  <td id='executionBox'><?php echo html::select('execution', $executions, '', "class='form-control chosen' data-placeholder='{$lang->doc->placeholder->execution}' onchange='loadModules(this.value, \"execution\")'")?></td>
                </tr>
                <?php endif;?>
                <tr>
                  <th class='w-100px'><?php echo $lang->doc->libAndModule?></th>
                  <td><span id='moduleBox' class='required'><?php echo html::select('module', $moduleOptionMenu, $moduleID, "class='form-control chosen'");?></span></td>
                </tr>
                <tr>
                  <th><?php echo $lang->doc->keywords;?></th>
                  <td colspan='3'><?php echo html::input('keywords', '', "class='form-control' placeholder='{$lang->doc->keywordsTips}'");?></td>
                </tr>
                <tr id='fileBox'>
                  <th><?php echo $lang->doc->files;?></th>
                  <td colspan='3'><?php echo $this->fetch('file', 'buildform');?></td>
                </tr>
                <tr>
                  <th><?php echo $lang->doc->mailto;?></th>
                  <td colspan="3">
                    <div class="input-group">
                      <?php
                      echo html::select('mailto[]', $users, '', "multiple class='form-control picker-select' data-drop-direction='top'");
                      echo $this->fetch('my', 'buildContactLists');
                      ?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class="th-control text-top"><?php echo $lang->doclib->control;?></th>
                  <td colspan='3' class='aclBox'>
                    <?php $aclList = $lang->doc->aclList;?>
                    <?php if($objectType == 'project') $aclList = $lang->doc->projectAclList;?>
                    <?php echo html::radio('acl', $aclList, 'open', "onchange='toggleAcl(this.value, \"doc\")'");?>
                  </td>
                </tr>
                <tr id='whiteListBox' class='hidden'>
                  <th><?php echo $lang->doc->whiteList;?></th>
                  <td colspan='3'>
                    <div class='input-group'>
                      <span class='input-group-addon groups-addon'><?php echo $lang->doclib->group?></span>
                      <?php echo html::select('groups[]', $groups, '', "class='form-control picker-select' multiple data-drop-direction='top'")?>
                    </div>
                    <div class='input-group'>
                      <span class='input-group-addon'><?php echo $lang->doclib->user?></span>
                      <?php echo html::select('users[]', $users, '', "class='form-control picker-select' multiple data-drop-direction='top'")?>
                      <?php echo $this->fetch('my', 'buildContactLists', "dropdownName=users");?>
                    </div>
                  </td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan='4' class='text-center'>
                    <?php echo html::submitButton($lang->release->common);?>
                    <?php echo html::commonButton($lang->cancel, "data-dismiss='modal'", "btn btn-wide");?>
                  </td>
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
    var contentHeight = $(document).height() - 92;
    setTimeout(function(){$('.ke-edit-iframe, .ke-edit, .ke-edit-textarea').height(contentHeight);}, 100);
    setTimeout(function(){$('.CodeMirror').height($(document).height() - 112);}, 100);
    $('#modalBasicInfo .modal-content').css('max-height', contentHeight);

    $('#contentBox #content').attr('id', 'contentHTML');
    $('#modalBasicInfo').on('show.zui.modal', function(){$('#modalBasicInfo #copyTitle').val($('.doc-title #editorTitle').val())});

    $('iframe.ke-edit-iframe').contents().find('.article-content').css('padding', '20px 20px 0 20px');
})
</script>
<?php js::set('textType', $config->doc->textTypes);?>
<?php js::set('docType', $docType);?>
<?php js::set('fromGlobal', $fromGlobal);?>
<?php js::set('holders', $lang->doc->placeholder);?>
<?php js::set('type', 'doc');?>
<?php js::set('requiredFields', ',' . $config->doc->create->requiredFields . ',');?>
<?php js::set('libNotEmpty', sprintf($lang->error->notempty, $lang->doc->lib));?>
<?php js::set('keywordsNotEmpty', sprintf($lang->error->notempty, $lang->doc->keywords));?>
<?php js::set('from', $from);?>
<?php include '../../common/view/footer.lite.html.php';?>
