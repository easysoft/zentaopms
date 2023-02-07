function changeParentStage(stageID)
{
    $.get(createLink('programplan', 'ajaxGetAttribute', 'stageID=' + stageID), function(attribute)
    {
        $('#attributeType td').html(attribute);
        $("#attribute" + "_chosen").remove();
        $("#attribute").next('.picker').remove();
        $("#attribute").chosen();
    })
}
