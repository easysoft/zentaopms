<?php js::set('measurementID', $measurementID);?>
<style>
#triggerModal{z-index: 1000000;}
</style>
<div class='panel'>
  <div class='panel-heading'>
    <div class='panel-title'><?php echo $measurement->name . " $lang->colon " . $lang->measurement->setSQL?></div>
  </div>
  <form method='post' action='<?php echo $actionLink?>' id='sqlForm' class='form-ajax'>
    <div style='padding: 10px'>
      <?php echo html::textarea('sql', $sql, "placeholder='{$lang->measurement->placeholder->sql}' class='form-control' $readonly rows='8'")?>
      <div class='row' style='padding-top: 10px'><?php if($hasParams and $step == 3) include 'showsqlparams.html.php'?></div>
    </div>
    <?php if($step == 3):?>
    <div style='padding: 0 10px 10px;'>
      <div class='input-group'>
        <span><?php echo $lang->measurement->queryResult;?></span>
        <?php echo $queryResult;?>
      </div>
    </div>
    <?php endif;?>
    <div style='padding: 0 10px 10px;'>
      <?php
      if($step == 1 or $step == 2)
      {
          $link = $this->createLink('measurement', 'ajaxBuildSQL', '', '', true);
          echo html::a($link, $lang->measurement->callSqlBuilder, '', "class='btn btn-secondary' data-toggle='modal' data-width    ='95%' data-type='iframe'");
          echo html::submitButton($lang->measurement->query, '', 'btn btn-primary');
      }
      ?>
      <?php
      if($step == 3)
      {
          echo html::commonButton($lang->measurement->reDesign, "id='reDesignButton'", 'btn btn-secondary');
          echo html::submitButton($lang->measurement->save, '', 'btn btn-primary');
      }
      ?>
    </div>
  </form>
</div>

<?php include './setparams.html.php';?>
