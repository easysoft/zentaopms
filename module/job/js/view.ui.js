$('#jobCases').html("<iframe id='jobTaskResult' src='" + $.createLink('testtask', 'unitCases', 'taskID=' + $('#jobCases').data('task') + '&orderBy=id_desc&onlybody=yes') + "' width='100%' height='0' scrolling='no'></iframe>");
setTimeout(() => {
    var height = $('#jobTaskResult').contents().find(".main-table").height();
    $('#jobTaskResult').contents().find("#main").css('min-width', 'auto');
    $('#jobTaskResult').css('background', 'white');
    $('#jobTaskResult').contents().find("#mainMenu").css('display', 'none');
    $('#jobTaskResult').contents().find("a").attr('target', '__blank');
    $('#jobTaskResult').contents().find(".main-table").css('padding-top', '0');
    $('#jobTaskResult').css('height', height + 40 + 'px');
}, 1000);