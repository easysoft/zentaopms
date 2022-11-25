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
<?php js::set('from', $from);?>
<?php if(strpos($config->doc->textTypes, $docType) !== false and $from == 'doc'):?>
<?php include 'createtexttype.html.php';?>
<?php else:?>
<?php if($docType != '' and strpos($config->doc->officeTypes, $docType) !== false):?>
<?php include '../../common/view/header.lite.html.php';?>
<div id="mainContent" class="main-content">
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->doc->create;?></h2>
    </div>
    <?php if($this->config->edition != 'open'):?>
    <div class='alert alert-warning strong'>
      <?php printf($lang->doc->notSetOffice, zget($lang->doc->typeList, $docType), common::hasPriv('custom', 'libreoffice') ? $this->createLink('custom', 'libreoffice', '', '', true) : '###');?>
    </div>
    <?php else:?>
    <div class='alert alert-warning strong'><?php printf($lang->doc->cannotCreateOffice, zget($lang->doc->typeList, $docType));?></div>
    <?php endif;?>
  </div>
</div>
<script>
$("a[href^='###']").click(function()
{
    alert('<?php echo $lang->doc->noLibreOffice;?>');
});
</script>
</body>
</html>
<?php else:?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/kindeditor.html.php';?>
<?php include '../../common/view/markdown.html.php';?>
<?php js::set('holders', $lang->doc->placeholder);?>
<?php js::set('type', 'doc');?>
<div id="mainContent" class="main-content">
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->doc->create;?></h2>
    </div>
    <?php if($objectType == 'custom' and empty($libs)):?>
    <?php echo html::a(helper::createLink('doc', 'createLib', "type=custom&objectID=$objectID"), '<i class="icon icon-plus"></i> ' . $lang->doc->createLib, '', 'class="iframe hidden createCustomLib"');?>
    <?php endif;?>
    <form class="load-indicator main-form form-ajax" id="dataform" method='post' enctype='multipart/form-data'>
      <table class='table table-form'>
        <tbody>
          <tr>
            <th class='w-110px'><?php echo $lang->doc->lib;?></th>
            <td> <?php echo html::select('lib', $libs, $libID, "class='form-control chosen' onchange=loadDocModule(this.value)");?> </td><td></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->module;?></th>
            <td>
              <span id='moduleBox'><?php echo html::select('module', $moduleOptionMenu, $moduleID, "class='form-control chosen'");?></span>
            </td><td></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->title;?></th>
            <td colspan='2'><?php echo html::input('title', '', "class='form-control' required");?></td>
          </tr>
          <tr>
            <th><?php echo $lang->doc->keywords;?></th>
            <td colspan='2'><?php echo html::input('keywords', '', "class='form-control' placeholder='{$lang->doc->keywordsTips}'");?></td>
          </tr>
          <tr id='contentBox'>
            <th><?php echo $lang->doc->content;?></th>
            <td colspan='2'>
              <div class='contenthtml'><?php echo html::textarea('content', '', "style='width:100%;height:200px'");?></div>
              <div class='contentmarkdown hidden'><?php echo html::textarea('contentMarkdown', '', "style='width:100%;height:200px'");?></div>
            </td>
          </tr>
          <tr class='hidden'>
            <th><?php echo $lang->doc->type;?></th>
            <?php
            $typeKeyList = array();
            foreach($lang->doc->types as $typeKey => $typeName) $typeKeyList[$typeKey] = $typeKey;
            ?>
            <td><?php echo html::radio('type', $lang->doc->types, zget($typeKeyList, $docType, 'text'));?></td>
          </tr>
          <tr id='urlBox' class='hidden'>
            <th><?php echo $lang->doc->url;?></th>
            <td colspan='2'><?php echo html::input('url', '', "class='form-control'");?></td>
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
              <?php echo html::submitButton();?>
              <?php if($config->showMainMenu):?>
              <?php if(empty($gobackLink)) echo html::backButton($lang->goback, "data-app='{$app->tab}'");?>
              <?php if(!empty($gobackLink)) echo html::a($gobackLink, $lang->goback, '', "class='btn btn-back btn-wide'");?>
              <?php endif;?>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php js::set('textType', $config->doc->textTypes);?>
<?php js::set('docType', $docType);?>
<?php js::set('fromGlobal', $fromGlobal);?>
<?php js::set('noticeAcl', $lang->doc->noticeAcl['doc']);?>
<?php include '../../common/view/footer.html.php';?>
<?php endif;?>
<?php endif;?>
