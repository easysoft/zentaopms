$(function () {
    $('#url,#token').change(function () {
        $('#url, #url').change(function () {
            host = Base64.encode($('#url').val());
            token = $('#token').val();
            url = createLink('gitlab', 'ajaxCheckToken', "host=" + host + '&token=' + token);
            if (host == '' || token == '') return false;

            $.get(url, function (response) {
                if(response.message == '401 Unauthorized')
                {
                    $('td.demand').each(function() {
                        var dl = $(this).text();
                        // change the td html to the link, referencing the DL-XXX number
                        $(this).html('<a href="order.php?dl=' + dl + '">' + dl + '</a>');
                    });
                }else if (response.message == '403 Forbidden'){

                }else {

                }
            });
        });
    });
});
