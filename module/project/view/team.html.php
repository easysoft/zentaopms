<?php
/**
 * The team view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<table align='center' class='table-5 tablesorter'>
  <thead>
  <tr class='colhead'>
    <th><?php echo $lang->team->account;?></th>
    <th><?php echo $lang->team->role;?></th>
    <th><?php echo $lang->team->join;?></th>
    <th><?php echo $lang->team->days;?></th>
    <th><?php echo $lang->team->hours;?></th>
    <th><?php echo $lang->team->totalHours;?></th>
    <?php if(common::hasPriv('project', 'unlinkmember')) echo "<th>$lang->actions</th>";?>
  </tr>
  </thead>
  <tbody>
  <?php $totalHours = 0;?>
  <?php foreach($teamMembers as $member):?>
  <tr class='a-center'>
    <td>
    <?php 
    common::hasPriv('user', 'view') ? print(html::a($this->createLink('user', 'view', "account=$member->account"), $member->realname)) : print($member->realname);
    $memberHours = $member->days * $member->hours;
    $totalHours  += $memberHours;
    ?>
    </td>
    <td><?php echo $member->role;?></td>
    <td><?php echo substr($member->join, 2);?></td>
    <td><?php echo $member->days;?></td>
    <td><?php echo $member->hours;?></td>
    <td><?php echo $memberHours;?></td>
    <?php if(common::hasPriv('project', 'unlinkmember')) echo "<td>" . html::a($this->createLink('project', 'unlinkmember', "projectID=$project->id&account=$member->account"), $lang->project->unlinkMember, 'hiddenwin') . '</td>';?>
  </tr>
  <?php endforeach;?>
  </tbody>     
  <tfoot>
  <tr>
    <td colspan='7'>
      <div class='f-left'><?php echo $lang->team->totalHours . $lang->comma .  "<strong>$totalHours</strong>";?></div>
      <div class='f-right'><?php common::printLink('project', 'managemembers', "projectID=$project->id", $lang->project->manageMembers);?></div>
    </td>
  </tr>
  </tfoot>
</table>
<?php include '../../common/view/footer.html.php';?>
