<?php
/**
 * The create basic info view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Fangzhou Hu <hufangzhou@easycorp.ltd>
 * @package     doc
 * @version     $Id: createbasicinfo.html.php 2022-08-02 13:49:25Z $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('holders', $lang->doc->placeholder);?>
<?php js::set('type', 'doc');?>
<?php js::set('requiredFields', ',' . $config->doc->create->requiredFields . ',');?>
<?php js::set('libNotEmpty', sprintf($lang->error->notempty, $lang->doc->lib));?>
<?php js::set('titleNotEmpty', sprintf($lang->error->notempty, $lang->doc->title));?>
<?php js::set('keywordsNotEmpty', sprintf($lang->error->notempty, $lang->doc->keywords));?>
<div id="mainContent" class="main-content">
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->doc->create;?></h2>
    </div>
    <?php if($objectType == 'custom' and empty($libs)):?>
    <?php echo html::a(helper::createLink('doc', 'createLib', "type=custom&objectID=$objectID"), '<i class="icon icon-plus"></i> ' . $lang->doc->createLib, '', 'class="iframe hidden createCustomLib"');?>
    <?php endif;?>
    <form class="load-indicator main-form form-ajax form-watched" id="dataform" method='post' enctype='multipart/form-data'>
      <table class='table table-form'>
        <tbody>
          <tr>
            <th class='w-110px'><?php echo $lang->doc->lib;?></th>
            <td> <?php echo html::select('lib', $libs, $libID, "class='form-control chosen' onchange=loadDocModule(this.value)");?> </td><td></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->module;?></th>
            <td>
              <span id='moduleBox'><?php echo html::select('module', $moduleOptionMenu, $moduleID, "class='form-control chosen' data-drop_direction='down'");?></span>
            </td><td></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->title;?></th>
            <td colspan='2' class='required'><?php echo html::input('title', '', "class='form-control'");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->keywords;?></th>
            <?php $required = strpos(",{$config->doc->create->requiredFields},", ',keywords,') !== false ? 'required' : '';?>
            <td colspan='2' class="<?php echo $required;?>"><?php echo html::input('keywords', '', "class='form-control' placeholder='{$lang->doc->keywordsTips}'");?></td>
          </tr>
          <tr class='hidden'>
            <th><?php echo $lang->doc->type;?></th>
            <?php
            $typeKeyList = array();
            foreach($lang->doc->types as $typeKey => $typeName) $typeKeyList[$typeKey] = $typeKey;
            ?>
            <td><?php echo html::radio('type', $lang->doc->types, zget($typeKeyList, $docType, 'text'));?></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->mailto;?></th>
            <td colspan="2">
              <div class="input-group">
                <?php
                echo html::select('mailto[]', $users, '', "multiple class='form-control picker-select' data-drop-direction='top'");
                echo $this->fetch('my', 'buildContactLists');
                ?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->doclib->control;?></th>
            <td colspan='2'>
              <?php $acl = $lib->acl == 'default' ? 'open' : $lib->acl;?>
              <?php $acl = ($lib->type == 'project' and $acl == 'private') ? 'open' : $acl;?>
              <?php echo html::radio('acl', $lang->doc->aclList, $acl, "onchange='toggleAcl(this.value, \"doc\")'");?>
              <span class='text-info' id='noticeAcl'><?php echo $lang->doc->noticeAcl['doc'][$acl];?></span>
            </td>
          </tr>
          <tr id='whiteListBox' class='hidden'>
            <th><?php echo $lang->doc->whiteList;?></th>
            <td colspan='2'>
              <div class='input-group'>
                <span class='input-group-addon groups-addon'><?php echo $lang->doclib->group?></span>
                <?php echo html::select('groups[]', $groups, '', "class='form-control picker-select' multiple data-drop-direction='top'")?>
              </div>
              <div class='input-group'>
                <span class='input-group-addon'><?php echo $lang->doclib->user?></span>
                <?php echo html::select('users[]', $users, '', "class='form-control picker-select' multiple data-drop-direction='top'")?>
              </div>
            </td>
          </tr>
          <tr>
            <td colspan='3' class='text-center form-actions'>
              <?php echo html::hidden('contentType', 'html');?>
              <?php echo html::commonButton($lang->doc->nextStep, "id='saveBtn'", "btn btn-wide btn-primary");?>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php js::set('objectType', $objectType);?>
<?php js::set('objectID', $objectID);?>
<?php js::set('docType', $docType);?>
<?php js::set('noticeAcl', $lang->doc->noticeAcl['doc']);?>
<?php include '../../common/view/footer.html.php';?>
