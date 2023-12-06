<?php
/**
 * The required view file of custom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     custom
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include $app->getModuleRoot() . 'common/view/header.html.php';?>
<div id='mainContent' class='main-row'>
  <?php if(!in_array($module, array('productplan', 'release', 'testsuite', 'testreport', 'caselib', 'doc')) and (!in_array($module, array('project', 'execution')) or (in_array($module, array('project', 'execution')) and $config->vision == 'rnd'))) include 'sidebar.html.php';?>
  <div class='main-col main-content'>
    <form class="load-indicator main-form form-ajax" method='post'>
      <div class='main-header'>
        <div class='heading'>
          <strong><?php echo $lang->custom->required;?></strong>
        </div>
      </div>
      <table class='table table-form mw-800px'>
        <?php $i = 0;?>
        <?php foreach($requiredFields as $method => $requiredField):?>
        <tr>
          <th class='thWidth'>
          <?php
          $fields = $this->custom->getFormFields($module, $method);
          if(empty($fields)) continue;
          if($module == 'caselib' and $method == 'createcase') continue;

          $actionKey = $method . 'Action';
          if(isset($lang->$module->$actionKey))
          {
              echo $lang->$module->$actionKey . $lang->custom->page;
          }
          else
          {
              echo $lang->$module->$method . $lang->custom->page;
          }
          ?>
          </th>
          <td><?php echo html::select("requiredFields[{$method}][]", $fields, $requiredField, "class='form-control chosen' multiple " . ($i == 0 ? "data-placeholder='{$lang->custom->notice->required}'" : ''));?></td>
          <td></td>
        </tr>
        <?php $i++;?>
        <?php endforeach;?>
        <tr>
          <th></th>
          <td colspan='2' class='form-actions'>
          <?php echo html::submitButton();?>
          <?php if(common::hasPriv('custom', 'resetRequired')) echo html::a(inlink('resetRequired', "module=$module"), $lang->custom->restore, 'hiddenwin', "class='btn btn-wide'");?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
