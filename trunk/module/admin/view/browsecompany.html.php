<?php
/**
 * The browse company view file of admin module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
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
<?php include '../../common/view/footer.html.php';?>
