<?php
/**
 * The editlib file of doc module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Jia Fu <fujia@cnezsoft.com>
 * @package     doc
 * @version     $Id: editlib.html.php 975 2010-07-29 03:30:25Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/chosen.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['doclib']);?></span>
    <strong><?php echo $lang->doc->editLib;?></strong>
  </div>
</div>
<div class='main'>
  <form method='post' target='hiddenwin' class='form-condensed' style='margin: 20px 0px 200px'>
    <table class='table table-form'>
      <?php if(!empty($lib->product)):?>
      <tr>
        <th><?php echo $lang->doc->product?></th>
        <td><?php echo $product->name?></td>
      </tr>
      <?php endif;?>
      <?php if(!empty($lib->project)):?>
      <tr>
        <th><?php echo $lang->doc->project?></th>
        <td><?php echo $project->name?></td>
      </tr>
      <?php endif;?>
      <tr>
        <th class='w-80px'><?php echo $lang->doc->libName?></th>
        <td><?php echo html::input('name', $lib->name, "class='form-control' autocomplete='off'")?></td>
      </tr>
      <tr>
        <th><?php echo $lang->doclib->control;?></th>
        <td><?php echo html::radio('acl', $lang->doc->aclList, $lib->acl, "onchange='toggleAcl(this.value)'")?></td>
      </tr>
      <tr id='whiteListBox' class='hidden'>
        <th><?php echo $lang->doc->whiteList?></th>
        <td>
          <div class='input-group'>
            <span class='input-group-addon groups-addon'><?php echo $lang->doclib->group?></span>
            <?php echo html::select('groups[]', $groups, $lib->groups, "class='form-control chosen' multiple")?>
          </div>
          <div class='input-group'>
            <span class='input-group-addon'><?php echo $lang->doclib->user?></span>
            <?php echo html::select('users[]', $users, $lib->users, "class='form-control chosen' multiple")?>
          </div>
        </td>
      </tr>
      <tr>
        <td></td>
        <td><?php echo html::submitButton();?></td>
      </tr>
    </table>
  </form>
</div>
<script>
$(function(){toggleAcl($('#acl').val())});
</script>
<?php include '../../common/view/footer.lite.html.php';?>
