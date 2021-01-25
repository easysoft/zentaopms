<?php include '../../common/view/header.html.php';?>
<?php js::set('workingHours', $lang->custom->conceptOptions->hourPoint[0]);?>
<?php js::set('storyPoint', $lang->custom->conceptOptions->hourPoint[1]);?>
<?php js::set('functionPoint', $lang->custom->conceptOptions->hourPoint[2]);?>
<?php js::set('efficiency', $lang->custom->unitList['efficiency']);?>
<?php js::set('unit', $unit);?>
<?php
$lang->custom->object   = array();
$lang->custom->system   = array();
$lang->custom->system[] = 'estimate';
?>
<style>
.unify-padding{width:94px;}
</style>
<div id='mainContent' class='main-row'>
  <div class='main-col main-content'>
    <form class="load-indicator main-form form-ajax" method='post'>
      <!-- div class='main-header'>
        <div class='heading'>
          <strong><?php echo $lang->custom->estimate . $lang->arrow . $lang->custom->estimateConfig;?></strong>
        </div>
      </div -->
      <table class='table table-form mw-900px'>
        <tr>
          <th class='thWidth'><?php echo $lang->custom->estimateUnit;?></th>
          <td class='w-350px'><?php echo html::radio('hourPoint', $lang->custom->conceptOptions->estimateUnit, $unit);?></td>
          <td></td>
        </tr>
        <tr class='hidden' id='convertRelations'>
          <th class='w-150px'><?php echo $lang->custom->one . $lang->custom->conceptOptions->hourPoint[$unit];?></th>
          <td>
            <div class='input-group w-300px'>
              <span class='input-group-addon'><?php echo "=";?></span>
              <?php echo html::input('scaleFactor', $scaleFactor, "class='form-control' required");?>
              <span class='input-group-addon unify-padding'></span>
            </div>
          </td>
          <td></td>
        </tr>
        <?php $hidden = $unit == 0 ? 'hidden' : '';?>
        <tr class="efficiency <?php echo $hidden;?>">
          <th class='w-150px'><?php echo $lang->custom->estimateEfficiency;?></th>
          <td>
            <div class='input-group w-300px'>
              <?php
              echo html::input('efficiency', $efficiency, "class='form-control'");
              ?>
              <span class='input-group-addon unify-padding'>
                <?php
                if($unit == 0)
                {
                    echo $lang->custom->conceptOptions->hourPoint[0];
                }
                else
                {
                    echo $lang->custom->unitList['efficiency'] . $lang->custom->conceptOptions->hourPoint[$unit];
                }
                ?>
              </span>
            </div>
          </td>
          <td></td>
        </tr>
        <tr>
          <th class='w-150px'><?php echo $lang->custom->estimateCost;?></th>
          <td>
            <div class='input-group w-300px'>
              <?php echo html::input('cost', $cost, "class='form-control'");?>
              <span class='input-group-addon unify-padding'>
              <?php echo $lang->custom->unitList['cost'];?>
              </span>
            </div>
          </td>
          <td></td>
        </tr>
        <tr>
          <th class='w-150px'><?php echo $lang->custom->estimateHours;?></th>
          <td>
            <div class='input-group w-300px'>
              <?php echo html::input('defaultWorkhours', $hours, "class='form-control'");?>
              <span class='input-group-addon unify-padding'>
              <?php echo $lang->custom->unitList['hours'];?>
              </span>
            </div>
          </td>
          <td></td>
        </tr>
        <tr>
          <th class='w-150px'><?php echo $lang->custom->estimateDays;?></th>
          <td>
            <div class='input-group w-300px'>
              <?php echo html::input('days', $days, "class='form-control'");?>
              <span class='input-group-addon unify-padding'>
              <?php echo $lang->custom->unitList['days'];?>
              </span>
            </div>
          </td>
          <td></td>
        </tr>
        <tr>
          <td colspan='2' class='text-center'><?php echo html::submitButton();?></td>
        </tr>
      </table>
    </form>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
