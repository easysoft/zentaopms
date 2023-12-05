<?php
/**
 * The edit view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     doc
 * @version     $Id: edit.html.php 975 2010-07-29 03:30:25Z jajacn@126.com $
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
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2>
        <span class='label label-id'><?php echo $doc->id;?></span>
        <?php echo $doc->title;?>
        <small> <?php echo $lang->arrow . ' ' . $lang->doc->edit;?></small>
      </h2>
    </div>
    <form class='load-indicator main-form form-ajax form-watched' method='post' enctype='multipart/form-data' id='dataform'>
      <table class='table table-form'>
        <?php if(strpos('product|project|execution', $type) !== false):?>
        <tr>
          <th><?php echo $lang->doc->{$type};?></th>
          <td class='required'><?php echo html::select($type, $objects, $objectID, "class='form-control chosen' onchange='loadObjectModules(\"{$type}\", this.value)'");?></td>
        </tr>
        <?php endif;?>
        <tr>
          <th class='w-110px'><?php echo $lang->doc->libAndModule?></th>
          <td colspan='3' class='required'><span id='moduleBox'><?php echo html::select('module', $moduleOptionMenu, $doc->lib . '_' . $doc->module, "class='form-control chosen'");?></span></td>
        </tr>
        <tr>
          <th><?php echo $lang->doc->title;?></th>
          <td colspan='3'><?php echo html::input('title', $doc->title, "class='form-control' required");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->doc->keywords;?></th>
          <td colspan='3'><?php echo html::input('keywords', $doc->keywords, "class='form-control' placeholder='{$lang->doc->keywordsTips}'");?></td>
        </tr>
        <?php if(strpos($config->doc->officeTypes, $doc->type) !== false):?>
        <tr>
          <th><?php echo $lang->doc->files;?></th>
          <td colspan='3'><?php echo $this->fetch('file', 'buildform');?></td>
        </tr>
        <?php endif;?>
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
        <tr id='whiteListBox' class='<?php if($doc->acl == 'open') echo 'hidden';?>'>
          <th><?php echo $lang->doc->whiteList;?></th>
          <td colspan='3'>
            <div class='input-group w-p100'>
              <span class='input-group-addon groups-addon'><?php echo $lang->doclib->group?></span>
              <?php echo html::select('groups[]', $groups, $doc->groups, "class='form-control picker-select' multiple data-drop-direction='top'")?>
            </div>
            <div class='input-group w-p100'>
              <span class='input-group-addon'><?php echo $lang->doclib->user?></span>
              <?php echo html::select('users[]', $users, $doc->users, "class='form-control picker-select' multiple data-drop-direction='top'")?>
              <?php echo $this->fetch('my', 'buildContactLists', "dropdownName=users");?>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan='4' class='text-center form-actions'>
            <?php
            echo html::hidden('contentType', $doc->contentType);
            echo html::hidden('type', $doc->type);
            echo html::hidden('status', $doc->status);
            echo html::hidden('parent', $doc->parent);
            echo html::submitButton();
            ?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php js::set('noticeAcl', $lang->doc->noticeAcl['doc']);?>
<?php include '../../common/view/footer.html.php';?>
