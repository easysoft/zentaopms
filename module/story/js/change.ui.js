$(function()
{
    $(document).on('change', '#needNotReview', function()
    {
        toggleReviewer();
    });
    toggleReviewer();
});

function toggleReviewer()
{
    var $this     = $('#needNotReview');
    var isChecked = $this.prop('checked');
    var $reviewer = $('#reviewer').zui('picker');

    if(isChecked)
    {
        $('#needNotReview').val(1);
        $('input[name=needNotReview]').val(1);
        $reviewer.render({disabled: true});
    }
    else
    {
        $('#needNotReview').val(0);
        $('input[name=needNotReview]').val(0);
        $reviewer.render({disabled: false});
    }
}
