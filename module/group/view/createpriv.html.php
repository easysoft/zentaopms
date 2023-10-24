<?php
/**
 * The createpriv view file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     group
 * @version     $Id: createpriv.html.php 4769 2023-03-07 10:09:21Z liumengyi $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('moduleViewPairs', $moduleViewPairs);?>
<?php js::set('packageModulePairs', $packageModulePairs);?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->group->createPriv;?></h2>
  </div>
  <form method='post' class='form-ajax' id='createpriv'>
    <table align='center' class='table table-form'>
      <tbody>
        <tr>
          <th class='c-name'><?php echo $lang->group->privName;?></th>
          <td class='required'><?php echo html::input('name', '', "class='form-control'");?></td>
          <td></td>
        </tr>
        <tr>
          <th><?php echo $lang->group->privModuleName;?></th>
          <td class='required'><?php echo html::input('moduleName', '', "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->group->privMethodName;?></th>
          <td class='required'><?php echo html::input('methodName', '', "class='form-control'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->group->privView;?></th>
          <td class='required'><?php echo html::select('view', $views, '', "class='form-control chosen' onchange='loadModuleAndPackage(this.value)' data-drop_direction='down'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->group->privModule;?></th>
          <td class='required'><?php echo html::select('module', $modules, '', "class='form-control picker-select' onchange='loadPackages(this.value, \"module\")'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->privpackage->belong;?></th>
          <td><?php echo html::select('package', $packages, '', "class='form-control picker-select' onchange='changeViewAndModule(this.value)'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->group->privDesc;?></th>
          <td colspan='2'><?php echo html::textarea('desc', '', "rows=5 class=form-control");?></td>
        </tr>
        <tr>
          <td colspan='3' class='text-center'><?php echo html::submitButton();?></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>

