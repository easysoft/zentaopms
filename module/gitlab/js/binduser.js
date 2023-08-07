$(document).ready(function()
{
    $('.gitlab-user-bind').change(function()
    {
        var user = zentaoUsers[$(this).val()];
        if(user !== undefined)
        {
            $(this).parent().parent().find('.email').text(user.email)
        }
    });

    $(document).on('click', '.zentao-users .chosen-container', function()
    {
        var $obj  = $(this).prev('select');
        var value = $obj.val();
        if($obj.hasClass('filled')) return false;

        $obj.empty();
        $obj.append($('#userList').html());
        $obj.val(value);
        $obj.addClass('filled');
        $obj.trigger("chosen:updated");
    })
});
