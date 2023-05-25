<style>
#module_chosen {width: 50%!important}
</style>
<tr>
  <th class='thWidth'><?php echo $lang->measurement->collectType;?></th>
  <td class='w-400px'>
    <?php echo html::select('collectType', $lang->measurement->collectTypeList, $measurement->collectType, "class='form-control chosen'");?>
  </td><td></td>
</tr>
<tr class='actionConfig <?php if($measurement->collectType == 'crontab') echo "hidden";?>'>
  <th><?php echo $lang->measurement->actionConfig;?></th>
  <td>
    <?php $collectAction = isset($measurement->collectConf->action) ? $measurement->collectConf->action : '';?>
    <?php echo html::select('action', $triggerOptions, $collectAction, "class='form-control chosen'");?>
  </td>
</tr>
<?php
$weekTabActive  = '';
$monthTabActive = '';
if($measurement->collectType == 'crontab')
{
    $weekTabActive  = zget($measurement->collectConf, 'type', '') == 'week' ? 'active' : '';
    $monthTabActive = zget($measurement->collectConf, 'type', '') == 'month' ? 'active' : '';
}
?>
<tr class='cycleConfig'>
  <th><?php echo $lang->measurement->cycleConfig;?></th>
  <td colspan='2'>
    <ul class="nav nav-tabs">
      <li class='<?php echo $weekTabActive?>'><a data-tab data-type='week' href="#week"><?php echo $lang->measurement->cycleWeek;?></a></li>
      <li class='<?php echo $monthTabActive?>'><a data-tab data-type='month' href="#month"><?php echo $lang->measurement->cycleMonth;?></a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane clearfix <?php echo $weekTabActive?>" id="week">
        <?php echo html::checkbox('config[week]', $lang->measurement->dayNames, zget($measurement->collectConf, 'week'))?>
      </div>
      <div class="tab-pane clearfix <?php echo $monthTabActive?>" id="month">
        <?php
        $days = array();
        for($i = 1; $i <= 10; $i ++) $days[$i] = $i;
        echo html::checkbox('config[month]', $days, zget($measurement->collectConf, 'month'));
        $days = array();
        for($i = 11; $i <= 20; $i ++) $days[$i] = $i;
        echo html::checkbox('config[month]', $days, zget($measurement->collectConf, 'month'));
        $days = array();
        for($i = 21; $i <= 31; $i ++) $days[$i] = $i;
        echo html::checkbox('config[month]', $days, zget($measurement->collectConf, 'month'));
        ?>
      </div>
    </div>
    <div class='input-group'>
      <span class='input-group-addon'><?php echo $lang->measurement->execTime;?></span>
      <?php echo html::input('execTime', $measurement->execTime, "class='form-control form-time w-200px'");?>
    </div>
    <?php if($measurement->collectType == 'crontab') echo html::hidden('config[type]', $measurement->collectConf->type)?>
    <?php if($measurement->collectType == 'action')  echo html::hidden('config[type]', 'week')?>
  </td>
</tr>  
<script>
$(function()
{
    $(document).on('change', '#collectType', function()
    {
        var collectType = $('#collectType').val();
        if(collectType == 'crontab')
        {
            $('.cycleConfig').removeClass('hidden');
            $('.actionConfig').addClass('hidden');
        }
        else
        {
            $('.actionConfig').removeClass('hidden');
            $('.cycleConfig').addClass('hidden');
        }
    })

    $('#collectType').change();

    $('ul.nav-tabs a').click(function()
    {   
        if($(this).data('type'))$('input[id*=type][id*=config]').val($(this).data('type'));
    });
    
});
</script>
