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
<div id='mainMenu' class='clearfix'>
  <div class='pull-right'>
    <?php if(common::hasPriv('custom', 'setstoryconcept')) echo html::a($this->createLink('custom', 'setstoryconcept', '', '', true), $lang->custom->setStoryConcept, '', "class='btn btn-primary iframe' data-toggle='modal'");?>
  </div>
</div>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content main-table'>
      <table class='table table-form mw-800px'>
        <tr>
          <th class='text-left'><?php echo $lang->custom->URConcept;?> </th>
          <th class='text-left'><?php echo $lang->custom->SRConcept;?> </th>
          <th class='w-60px text-left'><?php echo $lang->actions;?> </th>
        </tr>
        <?php foreach($URSRList as $key => $URSR):?>
        <tr>
          <td><?php echo $URSR['URName'];?></td>
          <td><?php echo $URSR['SRName'];?></td>
          <td class='c-actions'>
            <?php if(common::hasPriv('custom', 'deleteStoryConcept')) echo html::a($this->createLink('custom', 'deleteStoryConcept', "id=$key"), "<i class='icon icon-trash'></i>", 'hiddenwin', 'class="btn"');?>
          </td>
        </tr>
        <?php endforeach;?>
      </table>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
