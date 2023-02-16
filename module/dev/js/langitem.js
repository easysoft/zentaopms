$(function()
{
    $(".input-list").on("click", 'input', function(e)
    {
	handleClickItem(e.target.id);
    });
    $(".input-list").on("blur", 'input', function(e)
    {
        removeActive(e.target.id);
    });

    $('.label-list > .input-label').on('click', function(e)
    {
	handleClickItem($(this).attr('labelid'));
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

    function handleClickItem(clickId)
    {
	var clearId = $('.label-list > .text-primary').attr('labelid');
        if(clearId && clearId !== clickId) 
	{
	    removeActive(clearId);
	};

        if(clickId && clickId != clearId)
        {
            addActive(clickId);
        };
    }
})
