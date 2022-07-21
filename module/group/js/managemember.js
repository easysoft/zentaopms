$(function()
{
    $('.check-all input[type=checkbox]').change(function()
    {
        var checked = $(this).prop('checked');
        $(this).parents('tr').find('input[type=checkbox]').prop('checked', checked);
    });

    $('.table-members table tr').selectable(
    {
        selector: 'input',
        listenClick: false,
        select: function(e)
        {
            $('[data-id=' + e.id + ']').prop('checked', true);

            var checked          = true;
            var memberNum        = $('[data-id=' + e.id + ']').closest('td').find('input').length;
            var checkedMemberNum = $('[data-id=' + e.id + ']').closest('td').find('input:checked').length;
            if(memberNum > checkedMemberNum) checked = false;
            $('[data-id=' + e.id + ']').closest('tr').find('.check-all input').prop('checked', checked);
        },
        unselect: function(e)
        {
            $('[data-id=' + e.id + ']').prop('checked', false);

            var checked          = true;
            var memberNum        = $('[data-id=' + e.id + ']').closest('td').find('input').length;
            var checkedMemberNum = $('[data-id=' + e.id + ']').closest('td').find('input:checked').length;
            if(memberNum > checkedMemberNum) checked = false;
            $('[data-id=' + e.id + ']').closest('tr').find('.check-all input').prop('checked', checked);
        }
    });
});
