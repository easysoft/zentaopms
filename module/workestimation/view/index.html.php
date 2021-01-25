<?php
/**
 * The index of workestimation module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     workestimation
 * @version     $Id: index.html.php 4903 2013-06-26 05:32:59Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<style>.unify-padding{width:94px;}</style>
<div id='mainContent' class='main-content'>
  <div class='center-block'>
    <div class='main-header'>
      <h2><?php echo $lang->workestimation->common;?></h2>
    </div>
    <form class='load-indicator main-form form-ajax' id='dataform' method='post' enctype='multipart/form-data'>
      <table class='table table-form'> 
        <tbody>
          <tr>
            <th class='w-100px'><?php echo $lang->workestimation->scale;?></th>
            <td class='w-300px'>
              <div class='input-group'>
                <?php echo html::input('scale', zget($budget, 'scale', ''), "class='form-control' required");?>
                <span class='input-group-addon unify-padding'><?php echo $lang->hourCommon;?></span>
              </div>
            </td>
            <td><?php if(isset($budget->duration) and $budget->duration) printf($lang->workestimation->scaleTip, $scale);?></td>
            <td></td>
            <td></td>
          </tr>
          <?php $hidden = $hourPoint == 0 ? 'hidden' : '';?>
          <tr class="efficiency <?php echo $hidden?>">
            <th><?php echo $lang->workestimation->productivity;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::input('productivity', zget($budget, 'productivity', ''), "class='form-control'");?>
                <span class='input-group-addon unify-padding'>
                <?php
                  if($hourPoint == 0) echo $lang->hourCommon;
                  if($hourPoint == 1) echo $lang->custom->unitList['efficiency'] . $lang->hourCommon;
                  if($hourPoint == 2) echo $lang->custom->unitList['manhour'] . $lang->custom->unitList['loc'];
                ?>
                </span>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->workestimation->duration;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::input('duration', zget($budget, 'duration', ''), "class='form-control'");?>
                <span class='input-group-addon unify-padding'><?php echo $lang->workestimation->hour;?></span>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->workestimation->unitLaborCost;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::input('unitLaborCost',  zget($budget, 'unitLaborCost', ''), "class='form-control'");?>
                <span class='input-group-addon unify-padding'><?php echo $lang->custom->unitList['cost'];?></span>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->workestimation->totalLaborCost;?></th>
            <td>
              <div class='input-group'>
                <?php echo html::input('totalLaborCost', zget($budget, 'totalLaborCost', ''), "class='form-control'");?>
              </div>
            </td>
          </tr>
          <tr>
            <th><?php echo $lang->workestimation->dayHour;?></th>
            <td><?php echo html::input('dayHour', zget($budget, 'dayHour', ''), "class='form-control'");?></td>
          </tr>
          <tr>
            <td></td>
            <td colspan='2' class='text-left form-actions'>
              <?php echo html::submitButton();?>
              <?php echo html::backButton();?>
            </td>
          </tr>
        </tbody>
      </table>
      <div class='alert alert-info mg-0'> <?php echo $lang->workestimation->tips;?></div>
    </form>  
  </div>
</div>
<script>
$(function()
{
    $('#useScale').click(function()
    {
        $('#scale').val('<?php echo $scale;?>').keyup();
    });

    $(':input').keyup(function()
    {
        duration = parseFloat($('#scale').val()) * parseFloat($('#productivity').val()).toFixed(2);
        duration = duration.toFixed(2);
        if(!isNaN(duration)) $('#duration').val(duration);
        if(isNaN(duration)) $('#duration').val('');

        totalLaborCost = parseFloat($('#unitLaborCost').val()) * parseFloat($('#duration').val());
        totalLaborCost = totalLaborCost.toFixed(2);
        if(!isNaN(totalLaborCost)) $('#totalLaborCost').val(totalLaborCost);
        if(isNaN(totalLaborCost)) $('#totalLaborCost').val('');
    });
    $('#scale').change();
});
</script>
<?php include '../../common/view/footer.html.php';?>
