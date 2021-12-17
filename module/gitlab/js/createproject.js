$("#name").bind("input propertychange", function(event){
    $("#path").val($(this).val().toLowerCase());
});