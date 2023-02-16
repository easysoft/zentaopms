$(function()
{
    $(".input-list").on("click", 'input', function(event)
    {
        addActive(event.target.id);
    });
    $(".input-list").on("blur", 'input', function(event)
    {
        removeActive(event.target.id);
    });

    $('.label-list > .input-label').on('click', function(e)
    {
	var clearId = $('.label-list > .text-primary').attr('labelid');
        var clickId = $(this).attr('labelid');
        if(clearId && clearId !== clickId) 
	{
	    removeActive(clearId);
	};

        if(clickId && clickId != clearId)
        {
            addActive(clickId);
        };
    });

    function addActive(id)
    {
        $('[labelid=' + id + ']').addClass('text-primary');
        $('[iconid=' + id + ']').removeClass('hidden');
    }

    function removeActive(id)
    {
        $('[labelid=' + id + ']').removeClass('text-primary');
        $('[iconid=' + id + ']').addClass('hidden');
    }


})
