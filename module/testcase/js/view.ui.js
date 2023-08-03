function toggleStepsView(event)
{
    $('.step-change-view .icon-table-large').parent().toggleClass('text-primary');
    $('.step-change-view .icon-tree').parent().toggleClass('text-primary');
    $('#stepsTable').toggleClass('hidden');
    $('#stepsView').toggleClass('hidden');
}
