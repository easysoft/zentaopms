<?php
/**
 * The edit view of company module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     company
 * @version     $Id: edit.html.php 4713 2013-05-02 08:04:38Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'> 
  <div class='main-header'>
    <h2>
      <?php echo $company->name;?>
      <?php if(!isonlybody()):?>
      <small><?php echo $lang->arrow . $lang->company->edit;?></small>
      <?php endif;?>
    </h2>
  </div>
  <form class='main-form' method='post' target='hiddenwin'>
    <table class='table table-form'> 
      <tr>
        <th class='thWidth'><?php echo $lang->company->name;?></th>
        <td><?php echo html::input('name', $company->name, "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->company->phone;?></th>
        <td><?php echo html::input('phone', $company->phone, "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->company->fax;?></th>
        <td><?php echo html::input('fax', $company->fax, "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->company->address;?></th>
        <td><?php echo html::input('address', $company->address, "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->company->zipcode;?></th>
        <td><?php echo html::input('zipcode', $company->zipcode, "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->company->website;?></th>
        <td><?php echo html::input('website', $company->website ? $company->website : 'http://', "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->company->backyard;?></th>
        <td><?php echo html::input('backyard', $company->backyard ? $company->backyard : 'http://', "class='form-control'");?></td>
      </tr>  
      <tr>
        <th><?php echo $lang->company->guest;?></th>
        <td><?php echo html::radio('guest', $lang->company->guestOptions, $company->guest);?></td>
      </tr>  
      <tr><td class='text-center' colspan='2'><?php echo html::submitButton();?></td></tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
