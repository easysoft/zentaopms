$(function()
{
    $('.result-item').click(function()
    {
        var $this = $(this);
        if($this.data('status') == 'running')
        {
            return;
        }
        $this.toggleClass('show-detail');
        var show = $this.hasClass('show-detail');
        $this.next('.result-detail').toggleClass('hide', !show);
        $this.find('.collapse-handle').toggleClass('icon-angle-down', !show).toggleClass('icon-angle-top', show);;
    });

    $(".step-group input[type='checkbox']").click(function()
    {
        var $next  = $(this).closest('tr').next();
        while($next.length && $next.hasClass('step-item'))
        {
            var isChecked = $(this).prop('checked');
            $next.find("input[type='checkbox']").prop('checked', isChecked);
            $next = $next.next();
        }
    });

    $('#casesResults table caption .result-tip').html($('#resultTip').html());
    if($('tr:first').length == 0) return false;

    if($('tr:first').data('status') == 'ready')
    {
        $('tr:first').click();
    }
    else
    {
        var times = 0;
        var id    = $('tr:first').data('id')
        var link  = createLink('testtask', 'ajaxGetResult', 'resultID=' + id);

        var resultInterval = setInterval(() => {
            times++;
            if(times > 600)
            {
                clearInterval(resultInterval);
            }

            $.get(link, function(task)
            {
                task = JSON.parse(task);
                task = task.data;
                if(task.ZTFResult != '')
                {
                    clearInterval(resultInterval);
                    window.location.reload();
                }
            });
        }, 1000);
    }

    $('#casesResults').click(function(event)
    {
        if(event.target.id.indexOf('checkAll') !== -1)
        {
            var checkAll  = document.getElementById(event.target.id);
            var checkAll  = $(checkAll);
            var isChecked = checkAll.prop('checked');

            checkAll.closest('tbody').children('tr').find('input[type=checkbox]').prop('checked', isChecked);
        }
    });
});
