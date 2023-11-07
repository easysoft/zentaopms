window.toggleReviewer = function(obj)
{
    const $this     = $(obj);
    const isChecked = $this.prop('checked');
    const $reviewer = $('#reviewer').zui('picker');

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
