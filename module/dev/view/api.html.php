<?php include 'header.html.php';?>
<div id='mainContent' class='main-row'>
  <div class='side-col' id='sidebar'>
    <div class='cell'>
      <div class="panel panel-sm with-list">
        <div class='panel-heading'><i class='icon-list'></i> <strong><?php echo $lang->dev->moduleList?></strong></div>
        <?php foreach($lang->dev->groupList as $group => $groupName):?>
        <div class='modulegroup'><?php echo $groupName?></div>
        <?php foreach($modules[$group] as $module):?>
        <?php
        $active     = ($module == $selectedModule) ? 'active' : '';
        $moduleName = zget($lang->dev->tableList, $module, $module);
        ?>
        <?php echo html::a(inlink('api', "module=$module"), $moduleName, '', "class='$active'");?>
        <?php endforeach;?>
        <?php endforeach;?>
      </div>
    </div>
  </div>
  <div class='main-col main-content'>
    <?php if($selectedModule):?>
    <?php foreach($apis as $api):?>
    <div class='detail'>
      <?php
      $params = array();
      if(isset($api['param']))
      {
          foreach($api['param'] as $param) $params[] = "{$param['var']}=[{$param['var']}]";
      }
      $params = implode('&', $params);
      ?>
      <div class='detail-title'>
        <?php
        echo $api['post'] ? 'GET/POST' : 'GET';
        echo '&nbsp;&nbsp;' . $this->createLink($selectedModule, $api['name'], $params, 'json');
        ?>
      </div>
      <div class='detail-content'>
        <?php echo $api['desc'];?>
        <table class='table table-bordered'>
          <tr>
            <td><?php echo $lang->dev->params?></td>
            <td><?php echo $lang->dev->type?></td>
            <td><?php echo $lang->dev->desc?></td>
          </tr>
          <?php if(isset($api['param'])):?>
          <?php foreach($api['param'] as $param):?>
          <tr>
            <td><?php echo $param['var']?></td>
            <td><?php echo $param['type']?></td>
            <td><?php echo $param['desc']?></td>
          </tr>
          <?php endforeach;?>
          <?php else:?>
          <tr><td colspan="3"><?php echo $lang->dev->noParams?></td></tr>
          <?php endif;?>
        </table>
      </div>
    </div>
    <?php endforeach;?>
    <?php endif;?>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
