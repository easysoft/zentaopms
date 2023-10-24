$(function()
{
    $('ul.tree:first > li').addClass('open').addClass('in');
    $('ul.tree:first > li > ul > li').addClass('open').addClass('in');
    $('#module').change(function()
    {
        if($(this).val() == '') return;
        $('.treeBox').load(createLink('group', 'ajaxGetPrivTree', 'privIdList=' + privIdList + '&module=' + $(this).val() + '&type=' + type), function()
        {
            $('ul.tree').tree();
            $('ul.tree:first > li').addClass('open').addClass('in');
            $('ul.tree:first > li > ul > li').addClass('open').addClass('in');
        });
    })
})
