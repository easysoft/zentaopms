<?php
/**
 * The upload docs view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Deqing Chai <chaideqing@easycorp.ltd>
 * @package     doc
 * @version     $Id: uploaddocs.html.php 975 2023-10-31 13:49:25Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php $this->app->loadConfig('file');?>
<div id="mainContent" class="main-content">
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->doc->uploadDoc;?></h2>
    </div>
    <form class="load-indicator main-form form-ajax" id="dataform" method='post'>
      <table class='table table-form'>
        <tbody>
          <?php if($objectType == 'project'):?>
          <tr>
            <th><?php echo $lang->doc->project;?></th>
            <td colspan='3' class='required'><?php echo html::select('project', $objects, $objectID, "class='form-control picker-select' onchange=loadExecutions(this.value)");?></td>
            <?php if($this->app->tab == 'doc'):?>
            <th><?php echo $lang->doc->execution?></th>
            <td colspan='3' id='executionBox'><?php echo html::select('execution', $executions, '', "class='form-control picker-select' data-placeholder='{$lang->doc->placeholder->execution}' onchange='loadObjectModules(\"execution\", this.value)'")?></td>
            <?php endif;?>
          </tr>
          <?php elseif($objectType == 'execution'):?>
          <tr>
            <th><?php echo $lang->doc->execution;?></th>
            <td colspan='3' class='required'><?php echo html::select('execution', $objects, $objectID, "class='form-control picker-select' onchange='loadObjectModules(\"execution\", this.value)'");?></td>
          </tr>
          <?php elseif($objectType == 'product'):?>
          <tr>
            <th><?php echo $lang->doc->product;?></th>
            <td colspan='3' class='required'><?php echo html::select('product', $objects, $objectID, "class='form-control picker-select' onchange='loadObjectModules(\"product\", this.value)'");?></td>
          </tr>
          <?php endif;?>
          <tr>
            <th class='w-100px'><?php echo $lang->doc->libAndModule?></th>
            <td colspan='3' class='required'><span id='moduleBox'><?php echo html::select('module', $moduleOptionMenu, $moduleID, "class='form-control picker-select'");?></span></td>
          </tr>
          <tr id='file-upload'>
            <th><?php echo $lang->doc->uploadFile;?></th>
            <td colspan='4'><?php echo $this->fetch('file', 'buildform');?></td>
          </tr>
          <tr id='uploadFormat' style="display: none">
            <th><?php echo $lang->doc->uploadFormat;?></th>
            <td colspan='4'><?php echo html::radio('uploadFormat', $lang->doc->uploadFormatList, 'separateDocs', "onchange='toggleDocTitle(this.value)'");?></td>
          </tr>
          <tr id='docTitle' style='display: none'>
            <th><?php echo $lang->doc->title;?></th>
            <td colspan='3'><?php echo html::input('title', '', "class='form-control' required oninput='titleChanged()'"
);?></td>
          <tr>
            <th><?php echo $lang->doclib->control;?></th>
            <td colspan='3' <?php if($objectType != 'mine') echo "class='aclBox'";?>>
              <?php echo html::radio('acl', $lang->doc->aclList, $objectType == 'mine' ? 'private' : 'open', "onchange='toggleAcl(this.value, \"doc\")'");?>
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
          <tr>
            <td colspan='4' class='text-center form-actions'>
              <?php
              echo html::submitButton();
              echo html::hidden('status', 'normal');
              echo html::hidden('type', $docType);
              echo html::hidden('contentType', 'html');
              ?>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php js::set('textType', $config->doc->textTypes);?>
<?php js::set('objectType', $objectType);?>
<?php js::set('docType', $docType);?>
<?php js::set('holders', $lang->doc->placeholder);?>
<script>
function openEditURL(docID, fileID)
{
    var editUrl = createLink('file', 'download', "fileID=" + fileID + "&mouse=left&edit=1");
    window.open(editUrl);
    parent.location.href = createLink('doc', 'view', "docID=" + docID);
}
</script>
<?php include $app->getModuleRoot() . 'common/view/footer.lite.html.php';?>
