$(function()
{
    $(document).on('change', '#needNotReview', function()
    {
        toggleReviewer();
    });
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

window.renderChildCell = function(result, info)
{
    if(info.col.name == 'title' && result)
    {
        let html       = '';
        const story    = info.row.data;
        const gradeMap = gradeGroup[story.type] || {};
        let gradeLabel = gradeMap[story.grade];

        if(gradeLabel) html += "<span class='label gray-pale rounded-xl clip'>" + gradeLabel + "</span> ";
        if(html) result.unshift({html});
    }
    return result;
}
