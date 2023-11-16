window.waitDom('.picker-box [name^=team]', function()
{
    disableMembers();
});

window.clickSubmit = function()
{
    let memberCount = '';
    let error       = false;

    $('.picker-box [name^=team]').each(function()
    {
        if($(this).val() == '') return;

        memberCount++;

        let $tr      = $(this).closest('tr');
        let account  = $tr.find('.picker-single-selection').text();
        let estimate = parseFloat($tr.find('[name^=teamEstimate]').val());

        if($tr.hasClass('member-wait') && (isNaN(estimate) || estimate <= 0))
        {
            zui.Modal.alert(account + ' ' + estimateNotEmpty);
            error = true;
            return false;
        }

        let $left = $tr.find('[name^=teamLeft]');
        let left  = parseFloat($left.val());
        if(!$left.prop('readonly') && $tr.hasClass('member-wait') && (isNaN(left) || left <= 0))
        {
            zui.Modal.alert(account + ' ' + leftNotEmpty);
            error = true;
            return false;
        }
    });

    if(error) return false;

    if(memberCount < 2)
    {
        zui.Modal.alert(teamMemberError);
        return false;
    }
}
