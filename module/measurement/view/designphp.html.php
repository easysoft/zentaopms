<?php include '../../common/view/codeeditor.html.php';?>
<?php js::set('measurementID', $measurementID);?>
<style>
#triggerModal{z-index: 1000000;}
</style>
<div class='panel'>
  <div class='panel-heading'>
    <div class='panel-title'>
      <?php echo $lang->measurement->setPHP?>
      <span class='text-sm text-important'><?php echo $lang->measurement->placeholder->php;?></span>
    </div>
  </div>
  <form method='post' action="<?php echo $actionLink;?>" id='phpForm' class='form-ajax'>
    <div style='padding: 10px'>
      <?php echo html::textarea('code', $code, "class='form-control' rows='16'")?>
      <?php if($step == 3 and !empty($params)):?>
      <div class='row' style='padding-top: 10px'>
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
      <?php endforeach;?>
      </div>
    </div>
    <?php endif;?>
    <?php if($step == 3):?>
    <div style='padding: 0 10px 10px;'>
      <div class='input-group'>
        <span><?php echo $lang->measurement->queryResult;?></span>
        <?php echo $queryResult;?>
      </div>
    </div>
    <?php endif;?>

    <div style='padding: 0 10px 10px;'>
      <?php echo html::submitButton($lang->measurement->call);?>
    </div>
  </form>
</div>
<script>
$().ready(function()
{
    $('#execBtn').click(function()
    {
        if($('#code').val() == '') return false;
        $()
    });
});
</script>
<?php include './setparams.html.php';?>
