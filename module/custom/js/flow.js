$(document).ready(function()
{ 
    $('#mainMenu #flowTab').addClass('btn-active-text');

    toggleStoryOrRequirement($("[name='storyRequirement']:checked").val());
    $("[name='storyRequirement']").change(function(){ toggleStoryOrRequirement($(this).val())});
});

function toggleStoryOrRequirement(value)
{
    $('#requirementpoint').toggle(value == 0);
    $('#storypoint').toggle(value != 0);
    $('#hourPoint1').closest('.radio-inline').toggle(value != 0);
    $('#hourPoint2').closest('.radio-inline').toggle(value == 0);
}
