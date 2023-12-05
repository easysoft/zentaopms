<?php
/**
 * The view view of company module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Congzhi Chen <congzhi@cnezsoft.com>
 * @package     company
 * @version     $Id: edit.html.php 4488 2013-02-27 02:54:49Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->company->view;?></h2>
    <div class='btn-toolbar pull-right'>
      <?php common::printLink('company', 'edit', '', $lang->edit, '', 'id="editCompany" class="btn btn-wide btn-primary iframe" data-width="580"', true, true);?>
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
