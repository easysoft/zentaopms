<?php include '../../common/view/header.html.php';?>
<?php js::set('workingHours', $lang->custom->conceptOptions->hourPoint[0]);?>
<?php js::set('storyPoint', $lang->custom->conceptOptions->hourPoint[1]);?>
<?php js::set('functionPoint', $lang->custom->conceptOptions->hourPoint[2]);?>
<?php js::set('efficiency', $lang->custom->unitList['efficiency']);?>
<?php js::set('convertRelationTitle', $lang->custom->convertRelationTitle);?>
<?php js::set('convertRelationTips', $lang->custom->convertRelationTips);?>
<?php js::set('notempty', sprintf($this->lang->error->notempty, $this->lang->custom->scaleFactor));?>
<?php js::set('notNumber', sprintf($this->lang->error->float, $this->lang->custom->scaleFactor));?>
<?php js::set('unit', $unit);?>
<?php
$lang->custom->object   = array();
$lang->custom->system   = array();
$lang->custom->system[] = 'estimate';
?>
<style>
.unify-padding{width:94px;}
#title{font-weight: 700;}
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
          <td colspan='2' class='text-center'>
            <?php echo html::hidden('scaleFactor', '')?>
            <?php echo html::submitButton();?>
          </td>
        </tr>
      </table>
    </form>
  </div>
</div>
<div class="modal fade" id="convertRelations">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id='title'><?php echo $lang->custom->convertRelationTitle;?></h4>
      </div>
      <div class="modal-body">
        <form>
          <table class='table table-form'>
            <tr>
              <th class='w-80px'><?php echo $lang->custom->oneUnit;?></th>
              <td>
                <div class='input-group w-300px'>
                  <span class='input-group-addon'><?php echo "=";?></span>
                  <?php echo html::input('factor', $scaleFactor, "class='form-control' required");?>
                  <span class='input-group-addon unify-padding'></span>
                </div>
              </td>
            </tr>
            <tr>
              <td colspan='2'><div class='alert alert-info no-margin' id='tips'><?php echo $lang->custom->convertRelationTips;?></div></td>
            </tr>
            <tr>
              <td colspan='2' class='text-center'>
                <?php $gobackURL = $this->createLink('custom', 'estimate');?>
                <?php echo html::commonButton($lang->save, 'onclick = "setScaleFactor();"', 'btn btn-primary btn-wide');?>
                <?php echo html::a($gobackURL, $lang->goback, '', "class='btn btn-back btn-wide'");?>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include '../../common/view/footer.html.php';?>
