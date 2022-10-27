$(function()
{
    var heightType = $("[name='heightType']:checked").val();
    setCardCount(heightType);
    handleKanbanWidthAttr();
});
