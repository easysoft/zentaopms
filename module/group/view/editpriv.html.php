<?php
/**
 * The createPrivPackage view file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Ethan wang <wangxuepeng@easycorp.ltd>
 * @package     group
 * @version     $Id: editPriv.html.php 4769 2023-03-0 10:09:21Z ethan.wang $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('moduleViewPairs', $moduleViewPairs);?>
<?php js::set('packageModulePairs', $packageModulePairs);?>
<div id='mainContent' class='main-content'>
  <div class='main-header'>
    <h2><?php echo $lang->group->editPriv;?></h2>
  </div>
  <form method='post' class='form-ajax' id='editPriv'>
    <table align='center' class='table table-form'>
      <tbody>
        <tr>
          <th class='c-name'><?php echo $lang->group->privName;?></th>
          <td class='required'><?php echo html::input('name', $priv->name, "class='form-control'");?></td>
        </tr>
        <?php $isSystem = !empty($priv->system);?>
        <tr>
          <th><?php echo $lang->group->privModuleName;?></th>
          <td class='<?php echo !$isSystem ? 'required' : '';?>'><?php echo !$isSystem ? html::input('moduleName', $priv->moduleName, "class='form-control'") : $priv->moduleName;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->group->privMethodName;?></th>
          <td class='<?php echo !$isSystem ? 'required' : '';?>'><?php echo !$isSystem ? html::input('methodName', $priv->methodName, "class='form-control'") : $priv->methodName;?></td>
        </tr>
        <tr>
          <th><?php echo $lang->group->privView;?></th>
          <td class='required'><?php echo html::select('view', $views, $priv->view, "class='form-control chosen' onchange='loadModuleAndPackage(this.value)' data-drop_direction='down'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->group->privModule;?></th>
          <td class='required'><?php echo html::select('module', $modules, $priv->module, "class='form-control picker-select' onchange='loadPackages(this.value, \"module\")'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->privpackage->belong;?></th>
          <td class='required'><?php echo html::select('package', $packages, $priv->package, "class='form-control picker-select'");?></td>
        </tr>
        <tr>
          <th><?php echo $lang->group->privDesc;?></th>
          <td><?php echo html::textarea('desc', $priv->desc, "rows=5 class=form-control");?></td>
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

