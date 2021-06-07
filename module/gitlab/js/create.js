$(function () {
    $('#token, #url').change(function () {
        //取用户名
        var uid = $("#uid").val();

        //调ajax
        $.ajax({
            url: "uidchuli.php",
            data: {u: uid},
            type: "POST",
            dataType: "TEXT",
            success: function (data) {
                if (data > 0) {
                    $("#ts").html("该应户名已存在");
                    $("#ts").css("color", "red");
                } else {
                    $("#ts").html("该应户名可用");
                    $("#ts").css("color", "green");
                }
            }

        });
    })
});
