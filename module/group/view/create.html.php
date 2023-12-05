<?php
/**
 * The create view of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: create.html.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->group->create;?></h2>
  </div>
  <form method='post' target='hiddenwin' id='dataform'>
    <table align='center' class='table table-form'>
      <tr>
        <th><?php echo $lang->group->name;?></th>
        <td class='required'><?php echo html::input('name', '', "class='form-control'");?></td>
      </tr>
      <tr>
        <th><?php echo $lang->group->desc;?></th>
        <td><?php echo html::textarea('desc', '', "rows=5 class=form-control");?></td>
      </tr>
      <?php if($this->app->tab != 'project'):?>
      <tr>
        <th><?php echo $lang->group->limited;?></th>
        <td><?php echo html::checkbox('limited', '');?></td>
      </tr>
      <?php endif;?>
      <tr>
        <td colspan='2' class='text-center'><?php echo html::submitButton();?></td>
      </tr>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
