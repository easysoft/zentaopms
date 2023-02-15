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
    $('.label-list').on('click', '.input-label', function(e)
    {
	var clearId = $('.label-list > .text-primary').attr('labelid');
        var clickId = $(e.target).attr('labelid');
	if (clearId && clearId != clickId)
	{
            removeActive(clearId);
	}
	if (clickId && clickId != clearId)
	{
            addActive(clickId);
	    $('#' + clickId).focus();
	}
    });
    function addActive(id)
    {
        $('[labelid=' + id + ']').addClass('text-primary');
        $('[iconid=' + id + ']').removeClass('hidden');
    };
    function removeActive(id)
    {
        $('[labelid=' + id + ']').removeClass('text-primary');
        $('[iconid=' + id + ']').addClass('hidden');
    };
})
