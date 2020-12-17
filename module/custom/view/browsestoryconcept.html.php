<?php
/**
 * The html template file of setstoryconcept method of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2020 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Guangming Sun<sunguangming@cnezsoft.com>
 * @package     ZenTaoPMS
 * @version     $Id: browsestoryconcept.html.php 4129 2020-09-01 01:58:14Z sgm $
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>
    .table-form>tbody>tr>th{text-align: center}
</style>
<div id='mainMenu' class='clearfix'>
  <div class='pull-right'>
    <?php if(common::hasPriv('custom', 'setstoryconcept')) echo html::a($this->createLink('custom', 'setstoryconcept', '', '', true), $lang->custom->setStoryConcept, '', "class='btn btn-primary iframe' data-toggle='modal'");?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content main-table'>
      <table class='table table-form'>
        <tr>
          <th class='text-left'><?php echo $lang->custom->URConcept;?> </th>
          <th class='text-left'><?php echo $lang->custom->SRConcept;?> </th>
          <th><?php echo $lang->custom->buildin;?> </th>
          <th><?php echo $lang->custom->isDefault;?> </th>
          <th class='w-60px text-left'><?php echo $lang->actions;?> </th>
        </tr>
        <?php foreach($URSRList as $key => $URSR):?>
        <tr>
          <td class='text-left'><?php echo $URSR['URName'];?></td>
          <td class='text-left'><?php echo $URSR['SRName'];?></td>
          <td class='text-center'><?php echo zget($lang->custom->tipRangeList, $URSR['system'], '');?></td>
          <td class='text-center'><?php if($key == $config->custom->URSRCommon) echo "<i class='icon icon-check'></i>";?></td>
          <td class='c-actions'>
            <?php $disabled = $URSR['system'] ? "disabled=disabled" : '';?>
            <?php if(common::hasPriv('custom', 'setDefaultConcept')) echo html::a($this->createLink('custom', 'setDefaultConcept', "id=$key"), "<i class='icon icon-hand-right'></i>", 'hiddenwin', "class='btn' title={$lang->custom->setDefaultConcept}");?>
            <?php if(common::hasPriv('custom', 'deleteStoryConcept')) echo html::a($this->createLink('custom', 'deleteStoryConcept', "id=$key"), "<i class='icon icon-trash'></i>", 'hiddenwin', "class='btn' $disabled title={$lang->delete}");?>
          </td>
        </tr>
        <?php endforeach;?>
      </table>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
