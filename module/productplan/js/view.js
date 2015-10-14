function showLink(planID, type, orderBy, param)
{
    var method = type == 'story' ? 'linkStory' : 'linkBug';
    $.get(createLink('productplan', method, 'planID=' + planID + (typeof(param) == 'undefined' ? '' : param) + (typeof(orderBy) == 'undefined' ? '' : "&orderBy=" + orderBy)), function(data)
    {
        var obj = type == 'story' ? '.tab-pane#stories .linkBox' : '.tab-pane#bugs .linkBox';
        $(obj).html(data);
        $('#' + type + 'List').hide();

        var formID = type == 'story' ? '#unlinkedStoriesForm' : '#unlinkedBugsForm';
        fixTfootAction(formID);
    });
}
$(function()
{
    if(link == 'true') showLink(planID, type, orderBy, param);

    $('.dropdown-menu.with-search .menu-search').click(function(e)
    {
        e.stopPropagation();
        return false;
    }).on('keyup change paste', 'input', function()
    {
        var val = $(this).val().toLowerCase();
        var $options = $(this).parents('ul.dropdown-menu.with-search').find('.option');
        if(val == '') return $options.removeClass('hide');
        $options.each(function()
        {
            var $option = $(this);
            $option.toggleClass('hide', $option.text().toString().toLowerCase().indexOf(val) < 0 && $option.data('key').toString().toLowerCase().indexOf(val) < 0);
        });
    });
})
