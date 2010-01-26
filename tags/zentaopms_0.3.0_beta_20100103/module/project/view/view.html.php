<?php
/**
 * The view method view file of project module of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     project
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
?>
<?php include '../../common/header.html.php';?>
<div class='yui-d0'>
  <table align='center' class='table-1'>
   <caption><?php echo $project->name;?></caption>
   <tr>
     <th class='rowhead'><?php echo $lang->project->name;?></th>
     <td><?php echo $project->name;?></td>
   </tr>
   <tr>
     <th class='rowhead'><?php echo $lang->project->code;?></th>
     <td><?php echo $project->code;?></td>
   </tr>
   <tr>
     <th class='rowhead'><?php echo $lang->project->beginAndEnd;?></th>
     <td><?php echo $project->begin . ' ~ ' . $project->end;?></td>
   </tr>
   <tr>
     <th class='rowhead'><?php echo $lang->project->goal;?></th>
     <td><?php echo nl2br($project->goal);?></td>
   </tr>
   <tr>
     <th class='rowhead'><?php echo $lang->project->desc;?></th>
     <td><?php echo nl2br($project->desc);?></td>
   </tr>
   <tr>
     <th class='rowhead'><?php echo $lang->project->status;?></th>
     <td class='<?php echo $project->status;?>'><?php $lang->show($lang->project->statusList, $project->status);?></td>
   </tr>
   <tr>
     <th class='rowhead'><?php echo $lang->project->lblStats;?></th>
     <td><?php printf($lang->project->stats, $project->totalEstimate, $project->totalConsumed, $project->totalLeft, 10)?></td>
   </tr>
   <tr>
     <th class='rowhead'><?php echo $lang->project->products;?></th>
     <td>
       <?php foreach($products as $productID => $productName) echo html::a($this->createLink('product', 'browse', "productID=$productID"), $productName) . '<br />';?>
     </td>
   </tr>
   <tr>
     <td colspan='2' class='a-center'>
     <?php
     common::printLink('project', 'edit',   "projectID=$project->id", $lang->project->edit);
     common::printLink('project', 'delete', "projectID=$project->id", $lang->project->delete, 'hiddenwin');
     ?>
     </td>
   </tr>
 </table>
</div>
<?php include '../../common/footer.html.php';?>
