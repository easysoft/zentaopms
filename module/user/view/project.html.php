<?php
/**
 * The project view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<table class='table-1 tablesorter a-center'>
  <thead>
  <tr class='colhead'>
    <th class='w-id'><?php echo $lang->idAB;?></th>
    <th class='w-80px'><?php echo $lang->project->code;?></th>
    <th><?php echo $lang->project->name;?></th>
    <th class='w-date'><?php echo $lang->project->begin;?></th>
    <th class='w-date'><?php echo $lang->project->end;?></th>
    <th class='w-status'><?php echo $lang->statusAB;?></th>
    <th class='w-user'><?php echo $lang->team->role;?></th>
    <th class='w-date'><?php echo $lang->team->joinDate;?></th>
    <th class='w-date'><?php echo $lang->team->workingHour;?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($projects as $project):?>
  <?php $projectLink = $this->createLink('project', 'browse', "projectID=$project->id");?>
  <tr>
    <td><?php echo html::a($projectLink, $project->id);?></td>
    <td><?php echo $project->code;?></td>
    <td><?php echo html::a($projectLink, $project->name);?></td>
    <td><?php echo $project->begin;?></td>
    <td><?php echo $project->end;?></td>
    <td><?php echo $lang->project->statusList[$project->status];?></td>
    <td><?php echo $project->role;?></td>
    <td><?php echo $project->joinDate;?></td>
    <td><?php echo $project->workingHour;?></td>
  </tr>
  <?php endforeach;?>
  </tbody>
</table> 
<?php include '../../common/view/footer.html.php';?>
