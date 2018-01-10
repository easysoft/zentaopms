<?php
/**
 * The view view of company module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     company
 * @version     $Id: edit.html.php 4488 2013-02-27 02:54:49Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div class='container mw-600px'>
  <div id='titlebar'>
    <div class='heading'><?php echo html::icon($lang->icons['company']);?> <?php echo $lang->company->view;?></div>
    <div class='actions'>
      <?php common::printLink('company', 'edit', '', $lang->edit, '', 'id="editCompany" class="btn btn-primary iframe" data-width="580"', true, true);?>
    </div>
  </div>
  <table class='table table-borderless table-data'>
    <tr>
      <th class='w-150px'><?php echo $lang->company->name;?></th>
      <td><?php echo $company->name;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->company->phone;?></th>
      <td><?php echo $company->phone;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->company->fax;?></th>
      <td><?php echo $company->fax;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->company->address;?></th>
      <td><?php echo $company->address;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->company->zipcode;?></th>
      <td><?php echo $company->zipcode;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->company->website;?></th>
      <td><?php echo $company->website;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->company->backyard;?></th>
      <td><?php echo $company->backyard;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->company->guest;?></th>
      <td><?php echo $lang->company->guestOptions[$company->guest];?></td>
    </tr>
    <tr><td colspan='2' class='text-center'></td></tr>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
