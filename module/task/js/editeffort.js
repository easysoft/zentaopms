$(function()
{
    $("#submit").click(function(e, confirmed)
    {
        if(confirmed) return true;

        var $this = $(this);
        var $left = $('#left');
        var left  = $.trim($left.val());
        if(!$left.prop('readonly') && left == 0)
        {
            e.preventDefault();
            bootbox.confirm(confirmRecord, function(result)
            {
                if(result) $this.trigger('click', true);
            });
        }
    });
})
