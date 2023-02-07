<?php
/**
 * The required view file of custom module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     custom
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include 'header.html.php';?>
<div id='mainContent' class='main-row'>
  <div class='side-col' id='sidebar'>
    <div class='cell'>
      <div class='list-group'>
        <?php
        foreach($lang->custom->{$moduleName}->fields as $key => $value)
        {
            $method = $key == 'required' ? 'required' : 'set';
            $params = $key == 'required' ? "module=$moduleName" : "module=$moduleName&field=$key";
            $active = $key == 'required' ? 'active' : '';
            echo html::a(inlink($method, $params), $value, '', "class='$active'");
        }
        ?>
      </div>
    </div>
  </div>
  <div class='main-col main-content'>
    <form class="load-indicator main-form form-ajax" method='post'>
      <div class='main-header'>
        <div class='heading'>
          <strong><?php echo $lang->custom->object[$moduleName] . $lang->arrow . $lang->custom->$moduleName->fields['required']?></strong>
        </div>
      </div>
      <table class='table table-form mw-800px'>
        <?php $i = 0;?>
        <?php foreach($requiredFields as $method => $requiredField):?>
        <tr>
          <th class='thWidth'>
          <?php
          $fields = $this->custom->getFormFields($moduleName, $method);
          if(empty($fields)) continue;
          if($moduleName == 'caselib' and $method == 'createcase') continue;

          $actionKey = $method . 'Action';
          if(isset($lang->$moduleName->$actionKey))
          {
              echo $lang->$moduleName->$actionKey . $lang->custom->page;
          }
          else
          {
              echo $lang->$moduleName->$method . $lang->custom->page;
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
          <?php if(common::hasPriv('custom', 'resetRequired')) echo html::a(inlink('resetRequired', "module=$moduleName"), $lang->custom->restore, 'hiddenwin', "class='btn btn-wide'");?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
