<?php
/**
 * The create view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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
<style>
#main {padding: 0;}
.container {padding: 0 !important;}
#mainContent {padding: 0 !important;}
</style>
<?php $backLink = $app->session->docList ? $app->session->docList : $this->createLink('doc', 'view', "docID={$doc->id}") . "#app={$this->app->tab}";?>
<div id="mainContent" class="main-content">
  <form class="load-indicator main-form form-ajax form-watched" id="dataform" method='post' enctype='multipart/form-data'>
    <table class='table table-form'>
      <tbody>
        <tr id='headerBox'>
          <td width='90px'><?php echo html::a($backLink, "<i class='icon icon-back icon-sm'></i> " . $lang->goback, '', "id='backBtn' class='btn btn-secondary'");?></td>
          <td class="doc-title" colspan='3'><?php echo html::input('title', $doc->title, "placeholder='{$lang->doc->titlePlaceholder}'' id='editorTitle' class='form-control' required maxlength='100'");?></td>
          <td class="text-right btn-tools">
            <?php if($doc->status == 'draft'):?>
            <?php echo html::commonButton($lang->doc->saveDraft, "id='saveDraft'", "btn btn-secondary");?>
            <?php echo html::commonButton($lang->release->common, "id='saveRelease'", "btn btn-primary");?>
            <?php else:?>
            <?php echo html::submitButton($lang->release->common, "", "btn btn-primary");?>
            <?php endif;?>
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
                <?php echo html::hidden('status', $doc->status);?>
              </div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <div class='modal fade modal-basic' id='modalBasicInfo'>
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-header'>
            <h2 class='modal-title'>
              <?php echo $lang->doc->basicInfo;?>
              <button type='button' class='close' data-dismiss='modal'>
                <i class="icon icon-close"></i>
              </button>
            </h2>
          </div>
          <div class='modal-body'>
            <table class='table table-form' id="basicInfoBox">
              <tbody>
                <?php if(strpos('product|project|execution', $type) !== false):?>
                <tr>
                  <th><?php echo $lang->doc->{$type};?></th>
                  <td class='required'><?php echo html::select($type, $objects, $objectID, "class='form-control picker-select' onchange='loadObjectModules(\"{$type}\", this.value)'");?></td>
                </tr>
                <?php endif;?>
                <tr>
                  <th class='w-110px'><?php echo $lang->doc->libAndModule?></th>
                  <td colspan='3' class='required'><span id='moduleBox'><?php echo html::select('module', $moduleOptionMenu, $doc->lib . '_' . $doc->module, "class='form-control picker-select'");?></span></td>
                </tr>
                <tr>
                  <th><?php echo $lang->doc->keywords;?></th>
                  <td colspan='3' class='<?php if(strpos($config->doc->edit->requiredFields, 'keywords') !== false) echo 'required'?>'><?php echo html::input('keywords', $doc->keywords, "id='modalKeywords' class='form-control' placeholder='{$lang->doc->keywordsTips}'");?></td>
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
                      echo html::select('mailto[]', $users, $doc->mailto, "multiple class='form-control picker-select' data-drop-direction='top'");
                      echo $this->fetch('my', 'buildContactLists');
                      ?>
                    </div>
                  </td>
                </tr>
                <tr>
                  <th class="th-control text-top"><?php echo $lang->doclib->control;?></th>
                  <td colspan='3' class='aclBox'>
                    <?php echo html::radio('acl', $lang->doc->aclList, $doc->acl, "onchange='toggleAcl(this.value, \"doc\")'")?>
                  </td>
                </tr>
                <?php if($lib->type != 'mine'):?>
                <tr id='whiteListBox' class='<?php if($doc->acl == 'open') echo 'hidden';?>'>
                  <th><?php echo $lang->doc->whiteList;?></th>
                  <td colspan='3'>
                    <div class='input-group'>
                      <span class='input-group-addon groups-addon'><?php echo $lang->doclib->group?></span>
                      <?php echo html::select('groups[]', $groups, $doc->groups, "class='form-control picker-select' multiple data-drop-direction='top'")?>
                    </div>
                    <div class='input-group'>
                      <span class='input-group-addon'><?php echo $lang->doclib->user?></span>
                      <?php echo html::select('users[]', $users, $doc->users, "class='form-control picker-select' multiple data-drop-direction='top'")?>
                      <?php echo $this->fetch('my', 'buildContactLists', "dropdownName=users");?>
                    </div>
                  </td>
                </tr>
                <?php endif;?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan='4' class='text-center'><?php echo html::commonButton($lang->doc->confirm, "data-dismiss='modal'", "btn btn-primary btn-wide");?></td>
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
    /* Automatically save document contents. */
    setInterval("saveDraft()", <?php echo $config->doc->saveDraftInterval;?> * 1000);
    <?php if($otherEditing):?>
    bootbox.confirm(
    {
        message: '<?php echo $lang->doc->confirmOtherEditing;?>',
        callback: function(result){if(!result) location.href='<?php echo $backLink;?>'}
    });
    <?php endif;?>
})
</script>
<?php js::set('needUpdateContent', $doc->content != $doc->draft);?>
<?php js::set('confirmUpdateContent', $lang->doc->confirmUpdateContent);?>
<?php js::set('docID', $doc->id);?>
<?php js::set('draft', $doc->draft);?>
<?php js::set('type', 'doc');?>
<?php js::set('titleNotEmpty', sprintf($lang->error->notempty, $lang->doc->title));?>
<?php include '../../common/view/footer.html.php';?>
