<?php if($sysData->type == 'basic'):?>
<div id='<?php echo $controlID?>'>
  <table class='table table-form'>
    <?php foreach($params as $param):?>
    <tr>
      <th class='w-120px'><?php echo $param['showName'];?></td>
      <td>
      <?php
      $defaultValue = isset($param['defaultValue']) ? $param['defaultValue'] : '';
      echo $this->measurement->buildParamControl($param['varName'], $param['varType'], $defaultValue, $param['options'], $controlID);
      ?>
      </td>
    </tr>
    <?php endforeach;?>
  </table>
</div>
<?php endif;?>

<?php if($sysData->type == 'derive'):?>
<?php foreach($params as $index => $basicParams):?>
<div id='<?php echo $controlID?>'>
  <table class='table table-form'>
    <thead>
      <th colspan='2'></th>
    </thead>
    <?php foreach($basicParams as $param):?>
    <tr>
      <th class='w-120px'><?php echo $param['showName'];?></td>
      <td>
      <?php
      $defaultValue = isset($param['defaultValue']) ? $param['defaultValue'] : '';
      echo $this->sysData->buildParamControl($param['varName'], $param['varType'], $defaultValue, $param['options'], $controlID . $index);
      ?>
      </td>
    </tr>
    <?php endforeach;?>
  </table>
</div>
<?php endforeach;?>
<?php endif;?>

<?php if($sysData->type == 'report'):?>
<div id='<?php echo $controlID?>'>
  <table class='table table-form'>
    <?php foreach($params as $param):?>
    <tr>
      <th class='w-120px'><?php echo $param['showName'];?></td>
      <td>
      <?php
      $defaultValue = isset($param['defaultValue']) ? $param['defaultValue'] : '';
      echo $this->measurement->buildParamControl($param['varName'], $param['varType'], $defaultValue, $param['options'], $controlID);
      ?>
      </td>
    </tr>
    <?php endforeach;?>
  </table>
</div>
<?php endif;?>
