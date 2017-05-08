<?php
/**
 * The create view of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     doc
 * @version     $Id: create.html.php 975 2010-07-29 03:30:25Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/ueditor.html.php';?>
<?php include '../../common/view/markdown.html.php';?>
<?php js::set('holders', $lang->doc->placeholder);?>
<?php js::set('type', 'doc');?>
<div class='container mw-1400px'>
  <div id='titlebar'>
    <div class='heading'>
      <strong> <?php echo $lang->doc->create;?></strong>
    </div>
  </div>
  <form class='form-condensed' method='post' enctype='multipart/form-data' target='hiddenwin' id='dataform'>
    <table class='table table-form'> 
      <tr>
        <th class='w-80px'><?php echo $lang->doc->module;?></th>
        <td class='w-400px'>
          <?php echo html::hidden('lib', $libID);?>
          <span id='moduleBox'><?php echo html::select('module', $moduleOptionMenu, $moduleID, "class='form-control chosen'");?></span>
        </td>
        <td></td>
      </tr>  
      <tr>
        <th><?php echo $lang->doc->title;?></th>
        <td colspan='2'><?php echo html::input('title', '', "class='form-control' autocomplete='off'");?></td>
      </tr> 
      <tr>
        <th><?php echo $lang->doc->keywords;?></th>
        <td colspan='2'><?php echo html::input('keywords', '', "class='form-control' autocomplete='off'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->doc->type;?></th>
        <td><?php echo html::radio('type', $lang->doc->types, 'text');?></td>
      </tr> 
      <tr id='contentBox'>
        <th><?php echo $lang->doc->content;?></th>
        <td colspan='2'>
          <div class='contenthtml'><?php echo html::textarea('content', '', "style='width:100%;height:200px'");?></div>
          <div class='contentmarkdown hidden'><?php echo html::textarea('contentMarkdown', '', "style='width:100%;height:200px'");?></div>
          <?php echo html::hidden('contentType', 'html');?>
        </td>
      </tr>
      <tr id='urlBox' class='hidden'>
        <th><?php echo $lang->doc->url;?></th>
        <td colspan='2'>
          <?php echo html::input('url', '', "class='form-control' autocomplete='off'");?>
        </td>
      </tr>
      <tr id='fileBox'>
        <th><?php echo $lang->doc->files;?></th>
        <td colspan='2'><?php echo $this->fetch('file', 'buildform');?></td>
      </tr>
      <tr>
        <th><?php echo $lang->doclib->control;?></th>
        <td><?php echo html::radio('acl', $lang->doc->aclList, 'open', "onchange='toggleAcl(this.value)'")?></td>
      </tr>
      <tr id='whiteListBox' class='hidden'>
        <th><?php echo $lang->doc->whiteList;?></th>
        <td colspan='2'>
          <div class='input-group'>
            <span class='input-group-addon groups-addon'><?php echo $lang->doclib->group?></span>
            <?php echo html::select('groups[]', $groups, '', "class='form-control chosen' multiple")?>
          </div>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->doclib->user?></span>
            <?php echo html::select('users[]', $users, '', "class='form-control chosen' multiple")?>
          </div>
        </td>
      </tr>
      <tr>
        <td></td>
        <td><?php echo html::submitButton() . html::backButton() . html::hidden('lib', $libID);?></td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
