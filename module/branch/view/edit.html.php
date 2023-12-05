<?php
/**
 * The edit view file of branch module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Qiyu Xie <xieqiyu@easycorp.ltd>
 * @package     branch
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo sprintf($lang->branch->edit, $lang->product->branchName[$product->type]);?></h2>
  </div>
  <form class='main-form' method='post' target='hiddenwin'>
    <table class='table table-form'>
      <tr>
        <th><?php echo sprintf($lang->branch->name, $lang->product->branchName[$product->type]);?></th>
        <td><?php echo html::input('name', $branch->name, "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->branch->status;?></th>
        <td><?php echo html::select('status', $lang->branch->statusList, $branch->status, "class='form-control chosen'");?></td>
      </tr>
      <tr>
        <th><?php echo sprintf($lang->branch->desc, $lang->product->branchName[$product->type]);?></th>
        <td><?php echo html::input('desc', $branch->desc, "class='form-control'");?></td>
      </tr>
      <tr>
        <td colspan='2' class='text-center form-actions'><?php echo html::submitButton();?></td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
