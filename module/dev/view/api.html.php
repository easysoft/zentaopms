<?php include 'header.html.php';?>
<div class='side'>
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
<div class='main'>
  <?php if($selectedModule):?>
    <?php foreach($apis as $api):?>
    <table class='table table-condensed table-striped table-bordered tablesorter table-fixed active-disabled' id="api">
      <?php 
      $params = array();
      if(isset($api['param']))
      {
          foreach($api['param'] as $param) $params[] = "{$param['var']}=[{$param['var']}]"; 
      }
      $params = implode('&', $params);
      ?>
      <tr>
        <th colspan="3">
        <?php 
        echo $api['post'] ? 'GET/POST' : 'GET';
        echo '&nbsp;&nbsp;' . $this->createLink($selectedModule, $api['name'], $params, 'json');
        ?>
        </th>
      </tr>
      <tr><td colspan="3"><?php echo $api['desc'];?></td></tr>
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
    <?php endforeach;?>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
