$(function()
{
    $('.result-item').click(function()
    {
        var $this = $(this);
        $this.toggleClass('show-detail');
        var show = $this.hasClass('show-detail');
        $this.next('.result-detail').toggleClass('hide', !show);
        $this.find('.collapse-handle').toggleClass('icon-chevron-down', !show).toggleClass('icon-chevron-up', show);;
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

    $('tr:first').addClass("show-detail");
    $('#tr-detail_1').removeClass("hide");
});
