<?php
/**
 * The setting view file of message module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     message
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include './header.html.php';?>
<div id='mainContent' class='main-content'>
  <form class='load-indicator main-form form-ajax' method='post'>
    <table class='table table-bordered'>
      <thead>
        <th></th>
        <?php foreach($lang->message->typeList as $type => $typeName):?>
        <th>
          <div class='checkbox-primary'>
            <input type='checkbox' class='messageType' id='<?php echo 'type-' . $type?>' />
            <label for='<?php echo 'type-' . $type?>'><?php echo $typeName;?></label>
          </div>
        </th>
        <?php endforeach;?>
      </thead>
      <tbody>
        <?php foreach($config->message->objectTypes as $objectType => $actions):?>
        <tr>
          <td class='w-90px'>
            <div class='checkbox-primary'>
              <input type='checkbox' class='objectType' id='<?php echo 'objectType-' . $objectType;?>' />
              <label for='<?php echo 'objectType-' . $objectType;?>'><?php echo $objectTypes[$objectType];?></label>
            </div>
          </td>
          <?php $messageSetting = is_string($config->message->setting) ? json_decode($config->message->setting, true) : $config->message->setting;?>
          <?php foreach($lang->message->typeList as $type => $typeName):?>
          <?php if(isset($config->message->available[$type][$objectType])):?>
          <?php
          $availableActions = array();
          foreach($config->message->available[$type][$objectType] as $action)
          {
              if(!isset($objectActions[$objectType][$action])) continue;
              $availableActions[$action] = $objectActions[$objectType][$action];
          }
          ?>
          <td>
          <?php
          $selected = isset($messageSetting[$type]['setting'][$objectType]) ? join(',', $messageSetting[$type]['setting'][$objectType]) : '';
          foreach($availableActions as $key => $value)
          {
              $checked = strpos(",$selected,", ",{$key},") !== false ? "checked='checked'" : '';
              echo "<div class='checkbox-primary' title='$value'>";
              echo "<input type='checkbox' name='messageSetting[$type][setting][$objectType][]' value='$key' $checked id='messageSetting{$type}{$objectType}{$key}' />";
              echo "<label for='messageSetting{$type}{$objectType}{$key}'>$value</label></div>";
          }
          if(isset($config->message->condition[$type][$objectType]))
          {
              $moduleName = $objectType == 'case' ? 'testcase' : $objectType;
              $this->app->loadLang($moduleName);
              foreach(explode(',', $config->message->condition[$type][$objectType]) as $condition)
              {
                  $listKey = $condition . 'List';
                  $list = isset($this->lang->$moduleName->$listKey) ? $this->lang->$moduleName->$listKey : $users;
                  echo html::select("messageSetting[$type][condition][$objectType][$condition][]", $list,  isset($messageSetting[$type]['condition'][$objectType][$condition]) ? join(',', $messageSetting[$type]['condition'][$objectType][$condition]) : '', "class='form-control chosen' multiple data-placeholder='{$this->lang->$moduleName->$condition}'");
              }
          }
          ?>
          </td>
          <?php else:?>
          <td></td>
          <?php endif;?>
          <?php endforeach;?>
        </tr>
        <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan='<?php echo count($lang->message->typeList) + 1?>' class='text-center form-actions'><?php echo html::submitButton();?> <?php echo html::backButton();?></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
