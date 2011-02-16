<?php
/**
 * The edit view of company module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     company
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='g'><div class='u-1'>
  <form method='post' target='hiddenwin'>
    <table align='center' class='table-5'> 
      <caption><?php echo $lang->company->edit;?></caption>
      <tr>
        <th class='rowhead'><?php echo $lang->company->name;?></th>
        <td><?php echo html::input('name', $company->name, "class='text-1'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->company->phone;?></th>
        <td><?php echo html::input('phone', $company->phone, "class='text-1'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->company->fax;?></th>
        <td><?php echo html::input('fax', $company->fax, "class='text-1'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->company->address;?></th>
        <td><?php echo html::input('address', $company->address, "class='text-1'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->company->zipcode;?></th>
        <td><?php echo html::input('zipcode', $company->zipcode, "class='text-1'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->company->website;?></th>
        <td><?php echo html::input('website', $company->website, "class='text-1'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->company->backyard;?></th>
        <td><?php echo html::input('backyard', $company->backyard, "class='text-1'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->company->pms;?></th>
        <td><?php echo html::input('pms', $company->pms, "class='text-1'");?></td>
      </tr>  
      <tr>
        <th class='rowhead'><?php echo $lang->company->guest;?></th>
        <td><?php echo html::radio('guest', $lang->company->guestList, $company->guest);?></td>
      </tr>  
      <tr><td colspan='2' class='a-center'><?php echo html::submitButton();?></td></tr>
    </table>
  </form>
</div>  
<?php include '../../common/view/footer.html.php';?>
