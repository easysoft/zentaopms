<style>
#module_chosen {width: 50%!important}
</style>
<tr>
  <th class='thWidth'><?php echo $lang->measurement->collectType;?></th>
  <td class='w-400px'>
    <?php echo html::select('collectType', $lang->measurement->collectTypeList, '', "class='form-control chosen'");?>
  </td><td></td>
</tr>
<tr class='actionConfig hidden'>
  <th><?php echo $lang->measurement->actionConfig;?></th>
  <td>
    <?php echo html::select('action', $triggerOptions, '', "class='form-control chosen'");?>
  </td>
</tr>
<tr class='cycleConfig'>
  <th><?php echo $lang->measurement->cycleConfig;?></th>
  <td colspan='2'>
    <ul class="nav nav-tabs">
      <li class='active'><a data-tab data-type='week' href="#week"><?php echo $lang->measurement->cycleWeek;?></a></li>
      <li><a data-tab data-type='month' href="#month"><?php echo $lang->measurement->cycleMonth;?></a></li>
    </ul>
    <div class="tab-content">
      <div class="tab-pane active clearfix" id="week">
        <?php echo html::checkbox('config[week]', $lang->measurement->dayNames)?>
      </div>
      <div class="tab-pane clearfix" id="month">
        <?php
        $days = array();
        for($i = 1; $i <= 10; $i ++) $days[$i] = $i;
        echo html::checkbox('config[month]', $days);
        $days = array();
        for($i = 11; $i <= 20; $i ++) $days[$i] = $i;
        echo html::checkbox('config[month]', $days);
        $days = array();
        for($i = 21; $i <= 31; $i ++) $days[$i] = $i;
        echo html::checkbox('config[month]', $days);
        ?>
      </div>
    </div>
    <div class='input-group'>
      <span class='input-group-addon'><?php echo $lang->measurement->execTime;?></span>
      <?php echo html::input('execTime', '', "class='form-control form-time w-200px'");?>
    </div>
    <?php echo html::hidden('config[type]', 'week')?>
  </td>
</tr>  
<script>
$(function()
{
    $(document).on('change', '#module', function()
    {
        var moduleName = $('#module').val();
        var link = createLink('measurement', 'ajaxGetModuleActions', "module=" + moduleName);
        $.get(link, function(data)
        {
            $('#action').replaceWith(data);
            $('#action_chosen').remove();
            $("#action").chosen();
        });
    })

    $('#collectType').change(function()
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
    });

    $('ul.nav-tabs a').click(function()
    {   
        if($(this).data('type'))$('input[id*=type][id*=config]').val($(this).data('type'));
    });
});
</script>
