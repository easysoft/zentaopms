function confirmChanges()
{
    var inputValue1 = $("#userSelect option:selected").val();
    var spanText    = $("#userSelect option:selected").text();
    var inputName   = $("#saveInput").children('input').eq(0).attr("name");
    setInput(inputName, inputValue1, spanText);
    myModalTrigger.close()
}

function setInput(setName, setValue1, spanText)
{
    $("input[name='" + setName + "']").attr("value", setValue1).prev().html(spanText);
}

var myModalTrigger = new $.zui.ModalTrigger({title:$("#triggerTitle").html(), custom:$('#userList').html(), height:"auto", size:"sm"});

$(function ()
{
    $("#userList").html("");
});

$(".bind").on("click",function()
{
    var inputName = this.getAttribute("data-value");
    $("#saveInput").html();
    $("#saveInput").html("<input type='hidden' name='" + inputName + "' value=''>");
    myModalTrigger.show();
});