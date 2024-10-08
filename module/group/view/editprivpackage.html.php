<?php
/**
 * The editPrivPackage view file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     group
 * @version     $Id: editprivpackage.html.php 4769 2023-03-07 10:09:21Z liumengyi $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->group->editPrivPackage;?></h2>
  </div>
  <form method='post' class='form-ajax' id='editPrivPackage'>
    <table align='center' class='table table-form'>
      <tbody>
        <tr>
          <th class='c-name'><?php echo $lang->privpackage->name;?></th>
          <td class='required'><?php echo html::input('value', $privPackage->name, "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->privpackage->module;?></th>
          <td class='required'><?php echo html::select('parent', $modules, $privPackage->parent, "class='form-control picker-select'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->privpackage->desc;?></th>
          <td><?php echo html::textarea('desc', $privPackage->desc, "rows=5 class=form-control");?></td>
        </tr>
        <tr>
          <td colspan='2' class='text-center'><?php echo html::submitButton();?></td>
        </tr>
      </tbody>
    </table>
  </form>
  <hr class='small' />
  <div class='main'><?php include '../../common/view/action.html.php';?></div>
</div>
<?php include '../../common/view/footer.html.php';?>

