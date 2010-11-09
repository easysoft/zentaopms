<?php
/**
 * The browse company view file of admin module of ZenTaoMS.
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
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='yui-d0'>
  <table align='center' class='table-1'>
    <tr class='colhead'>
     <th><?php echo $lang->company->id;?></th>
     <th><?php echo $lang->company->name;?></th>
     <th><?php echo $lang->company->phone;?></th>
     <th><?php echo $lang->company->fax;?></th>
     <th><?php echo $lang->company->address;?></th>
     <th><?php echo $lang->company->zipcode;?></th>
     <th><?php echo $lang->company->website;?></th>
     <th><?php echo $lang->company->backyard;?></th>
     <th><?php echo $lang->company->pms;?></th>
     <th><?php echo $lang->company->guest;?></th>
     <th><?php echo $lang->actions;?></th>
   </tr>
   <?php foreach($companies as $company):?>
   <tr>
     <td class='a-center'><?php echo $company->id;?></td>
     <td><?php echo $company->name;?></td>
     <td><?php echo $company->phone;?></td>
     <td><?php echo $company->fax;?></td>
     <td><?php echo $company->address;?></td>
     <td><?php echo $company->zipcode;?></td>
     <td><?php echo html::a($company->website, $company->website, '_blank');?></td>
     <td><?php echo html::a($company->backyard,$company->backyard, '_blank');?></td>
     <td><?php echo html::a('http://' . $company->pms, $company->pms, '_blank');?></td>
     <td><?php echo $lang->company->guestList[(int)$company->guest];?></td>
     <td>
       <?php echo html::a($this->createLink('company', 'edit',   "companyID=$company->id"), $this->lang->company->edit);?>
       <?php echo html::a($this->createLink('company', 'delete', "companyID=$company->id"), $this->lang->company->delete, "hiddenwin");?>
     </td>
   </tr>
   <?php endforeach;?>
  </table>
</div>  
<?php include '../../common/view/footer.html.php';?>
