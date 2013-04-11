<?php
/**
 * The view view of company module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     company
 * @version     $Id: edit.html.php 4488 2013-02-27 02:54:49Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<table align='center' class='table-5'> 
  <caption><?php echo $lang->company->view;?></caption>
  <tr>
    <th class='rowhead'><?php echo $lang->company->name;?></th>
    <td><?php echo $company->name;?></td>
  </tr>  
  <tr>
    <th class='rowhead'><?php echo $lang->company->phone;?></th>
    <td><?php echo $company->phone;?></td>
  </tr>  
  <tr>
    <th class='rowhead'><?php echo $lang->company->fax;?></th>
    <td><?php echo $company->fax;?></td>
  </tr>  
  <tr>
    <th class='rowhead'><?php echo $lang->company->address;?></th>
    <td><?php echo $company->address;?></td>
  </tr>  
  <tr>
    <th class='rowhead'><?php echo $lang->company->zipcode;?></th>
    <td><?php echo $company->zipcode;?></td>
  </tr>  
  <tr>
    <th class='rowhead'><?php echo $lang->company->website;?></th>
    <td><?php echo $company->website;?></td>
  </tr>  
  <tr>
    <th class='rowhead'><?php echo $lang->company->backyard;?></th>
    <td><?php echo $company->backyard;?></td>
  </tr>  
  <tr>
    <th class='rowhead'><?php echo $lang->company->pms;?></th>
    <td><?php echo $company->pms;?></td>
  </tr>  
  <tr>
    <th class='rowhead'><?php echo $lang->company->guest;?></th>
    <td><?php echo $lang->company->guestList[$company->guest];?></td>
  </tr>  
  <tr><td colspan='2' class='a-center'><?php common::printLink('company', 'edit', '', $lang->edit, '', 'id=editCompany', true, true);?></td></tr>
</table>
<?php include '../../common/view/footer.html.php';?>
