$().ready(function()
{
    $(':input[name*=people]').change(function()
    {
        var peopleNumber = 0;
        $(':input[name*=people]').each(function()
        {
            var people = $(this).val();
            if(people) peopleNumber += parseInt(people);
        });
        $('#totalStaff').html(peopleNumber);
    });

    $(':input[name*=workload]').change(function()
    {
        rate = parseFloat($(this).val());
        workload = estimation.scale * rate / 100;
        $(this).parents('td').next().find(':input').val(workload);
        totalWorkload = 0;
        $(':input[name*=workload]').each(function()
        {
            rate = parseFloat($(this).val());
            workload = estimation.scale * rate / 100;
            totalWorkload += workload;
        });
        $('#totalWorkload').html(totalWorkload.toFixed(2));
    });


    $(':input').change(function()
    {
        var that = $(this);
        projectID    = project.id;
        stage        = $(this).parents('tr').find('[name*=stage]').val();
        workload     = $(this).parents('tr').find('[name*=workload]').val();
        worktimeRate = $(this).parents('tr').find('[name*=worktimeRate]').val();
        people       = $(this).parents('tr').find('[name*=people]').val();
        startDate    = $(this).parents('tr').find('[name*=startDate]').val();
        if(startDate) startDate = startDate.replace(/-/g, '_');

        if(isNaN(workload) || isNaN(worktimeRate) || isNaN(people) || startDate == '') return false;
        url = createLink('durationestimation', 'ajaxGetDuration', 'projectID=' + projectID + '&stage=' + stage + '&workload=' + workload + '&worktimeRate=' + worktimeRate  + '&people=' + people + '&startDate=' + startDate),
        $.getJSON(
            url,
            function(response)
            {
              if(response.result == 'success')
              {
                that.parents('tr').find('[name*=endDate]').val(response.endDate);
              }
            }
          );
    });
    $(':input').change();
})
