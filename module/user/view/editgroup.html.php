<?php
/**
 * The edit group view of user module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Congzhi Chen <congzhi@chen.com>
 * @package     user
 * @version     $Id: edit.html.php 4644 2013-04-11 07:15:43Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<form method='post' target='hiddenwin'>
  <table align='center' class='table-1 tablesorter'>
    <caption class='caption-tl pb-10px'>
      <div class='f-left'><?php echo $lang->user->editGroup;?></div>
      <div class='f-right'><?php common::printIcon('group', 'create');?></div>
    </caption>
    <thead>
    <tr class='colhead'>
     <th class='w-id'><?php echo $lang->group->id;?></th>
     <th class='w-100px'><?php echo $lang->group->name;?></th>
     <th><?php echo $lang->group->desc;?></th>
    </tr>
    </thead>
    <tbody>
    <?php foreach($groups as $group):?>
    <tr class='a-left'>
      <td class='strong'>  <?php echo html::checkbox('groups', array($group->id => $group->id), in_array($group->id, $userGroups) ? $group->id : '', "class='ml-10px'");?></td>
      <td class='a-center'><?php echo $group->name;?></td>
      <td class='a-left'>  <?php echo $group->desc;?></td>
      </tr>
    <?php endforeach;?>
    </tbody>
    <tfoot>
    <tr><td colspan='4' class='a-center'><?php echo html::submitButton() . html::gobackButton();?></td></tr>
    </tfoot>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
