<?php
/**
 * The create text view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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
<style>
#main {padding: 0;}
.container {padding: 0 !important;}
#mainContent {padding: 0 !important;}
</style>
<?php if($objectType == 'custom' and empty($libs)):?>
<?php echo html::a(helper::createLink('doc', 'createLib', "type=custom&objectID=$objectID"), '<i class="icon icon-plus"></i> ' . $lang->doc->createLib, '', 'class="iframe hidden createCustomLib"');?>
<?php endif;?>
<div id="mainContent" class="main-content">
  <form class="load-indicator main-form form-ajax form-watched" id="dataform" method='post' enctype='multipart/form-data'>
    <table class='table table-form'>
      <tbody>
        <tr id='headerBox'>
          <td width='90px'><?php echo html::backButton("<i class='icon icon-back icon-sm'></i> " . $lang->goback, "id='backBtn'", 'btn btn-secondary');?></td>
          <td class="doc-title" colspan='3'><?php echo html::input('title', '', "placeholder='{$lang->doc->titlePlaceholder}' id='editorTitle' class='form-control' required maxlength='100'");?></td>
          <td class="text-right btn-tools">
            <?php echo html::commonButton($lang->doc->saveDraft, "id='saveDraft' data-placement='bottom'", "btn btn-secondary");?>
            <?php echo html::a('#modalBasicInfo', $lang->release->common, '', "data-toggle='modal' id='basicInfoLink' class='btn btn-primary'");?>
          </td>
        </tr>
        <tr>
          <td colspan='5' id="editorContent">
            <div class="main-row fade in">
              <div id='contentBox' class="main-col">
                <?php $contentRequired = strpos($config->doc->create->requiredFields, 'content') !== false ? 'required' : '';?>
                <div class='contenthtml <?php echo $contentRequired?>'><?php echo html::textarea('content', '', "style='width:100%;'");?></div>
                <div class='contentmarkdown <?php echo $contentRequired;?> hidden'><?php echo html::textarea('contentMarkdown', '', "style='width:100%;'");?></div>
                <?php echo html::hidden('contentType', 'html');?>
                <?php echo html::hidden('type', 'text');?>
                <?php echo html::hidden('status', 'normal');?>
              </div>
            </div>
          </td>
        </tr>
      </tbody>
    </table>

    <div class='modal fade modal-basic' id='modalBasicInfo'>
      <div class='modal-dialog'>
        <div class='modal-content'>
          <div class='modal-body'>
            <button type='button' class='close' data-dismiss='modal'>
              <i class="icon icon-close"></i>
            </button>
            <table class='table table-form' id="basicInfoBox">
              <tbody>
                <tr><th class='w-110px'></th><td></td><th class='w-110px'></th><td></td><td class='w-30px'></td></tr>
                <tr>
                  <th><?php echo $lang->doc->title?></th>
                  <td colspan='3' id='copyTitle'></td>
                </tr>
                <?php if($linkType == 'project'):?>
                <tr>
                  <th><?php echo $lang->doc->project;?></th>
                  <td class='required'><?php echo html::select('project', $objects, isset($execution) ? $execution->project : $objectID, "class='form-control picker-select' onchange=loadExecutions(this.value)");?></td>
                  <?php if($this->app->tab == 'doc' and $config->vision == 'rnd'):?>
                  <th><?php echo $lang->doc->execution?></th>
                  <td id='executionBox'><?php echo html::select('execution', $executions, isset($execution) ? $objectID : '', "class='form-control chosen' onchange='loadObjectModules(\"execution\", this.value)'")?></td>
                  <td class='pl-0px'><i class='icon icon-help' title='<?php echo $lang->doc->placeholder->execution;?>'></i></td>
                  <?php endif;?>
                </tr>
                <?php elseif($linkType == 'execution'):?>
                <tr>
                  <th><?php echo $lang->doc->execution;?></th>
                  <td class='required'><?php echo html::select('execution', $objects, $objectID, "class='form-control picker-select' onchange='loadObjectModules(\"execution\", this.value)'");?></td>
                </tr>
                <?php elseif($linkType == 'product'):?>
                <tr>
                  <th><?php echo $lang->doc->product;?></th>
                  <td class='required'><?php echo html::select('product', $objects, $objectID, "class='form-control picker-select' onchange='loadObjectModules(\"product\", this.value)'");?></td>
                </tr>
                <?php endif;?>
                <tr>
                  <th class='w-110px'><?php echo $lang->doc->libAndModule?></th>
                  <td colspan='3' class='required'><span id='moduleBox'><?php echo html::select('module', $moduleOptionMenu, $moduleID, "class='form-control picker-select'");?></span></td>
                </tr>
                <tr>
                  <th><?php echo $lang->doc->keywords;?></th>
                  <td colspan='3' class='<?php if(strpos($config->doc->create->requiredFields, 'keywords') !== false) echo 'required'?>'><?php echo html::input('keywords', '', "id='modalKeywords' class='form-control' placeholder='{$lang->doc->keywordsTips}'");?></td>
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
                    <?php echo html::radio('acl', $lang->doc->aclList, $objectType == 'mine' ? 'private' : 'open', "onchange='toggleAcl(this.value, \"doc\")'");?>
                  </td>
                </tr>
                <?php if($objectType != 'mine'):?>
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
                <?php endif;?>
              </tbody>
              <tfoot>
                <tr>
                  <td colspan='4' class='text-center'>
                    <?php echo html::commonButton($lang->release->common, "id='releaseBtn'", 'btn btn-primary btn-wide');?>
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
<?php js::set('textType', $config->doc->textTypes);?>
<?php js::set('docType', $docType);?>
<?php js::set('objectType', $objectType);?>
<?php js::set('type', 'doc');?>
<?php js::set('requiredFields', ',' . $config->doc->create->requiredFields . ',');?>
<?php js::set('libNotEmpty', sprintf($lang->error->notempty, $lang->doc->lib));?>
<?php js::set('keywordsNotEmpty', sprintf($lang->error->notempty, $lang->doc->keywords));?>
<?php js::set('from', $from);?>
<?php js::set('titleNotEmpty', sprintf($lang->error->notempty, $lang->doc->title));?>
<?php js::set('contentNotEmpty', sprintf($lang->error->notempty, $lang->doc->content));?>
<?php include '../../common/view/footer.lite.html.php';?>
