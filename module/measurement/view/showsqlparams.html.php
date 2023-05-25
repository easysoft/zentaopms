<?php include $app->getModuleRoot() . 'common/view/datepicker.html.php';?>
<?php foreach($params as $varName => $param):?>
<?php echo html::hidden('params[]', json_encode($param));?>
<div class='col-md-3 col-sm-6' style='width:220px'>
  <div class='input-group'>
    <?php $paramName = isset($param['showName']) ? $param['showName'] : $varName?>
    <span class='input-group-addon text-ellipsis' style='max-width: 140px' title='<?php echo $paramName;?>'><?php echo $paramName;?></span>
    <?php
    $defaultValue = isset($param['defaultValue']) ? $param['defaultValue'] : '';
    $value        = zget($param, 'queryValue', $defaultValue);
    echo $this->measurement->buildParamControl('vars', $param['varType'], $value, $param['options']);
    ?>
  </div>
</div>
<script>
$('input').attr('readonly', 'readonly');
$('select').attr('disabled', 'disabled');
</script>
<?php endforeach;?>
