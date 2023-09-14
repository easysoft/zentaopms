window.testMetric = function()
{
    let formData = $('.params-form').serialize();

    const postData = {};
    const params = new URLSearchParams(formData);

    for(const[key, value] of params.entries())
    {
        const decodedKey = decodeURIComponent(key);
        const decodedValue = decodeURIComponent(value);

        if(!(decodedKey in postData)) postData[decodedKey] = [];
        postData[decodedKey].push(decodedValue);
    }

    let url = $.createLink('metric', 'view', 'id=' + metricID);
    $.post(url, postData, function(response){
        response = JSON.parse(response);

        if(response.result == 'success')
        {
            $('.response-box').removeClass('text-danger').html(response.queryResult);
        }
        else
        {
             $('.response-box').addClass('text-danger').html(response.errors);
        }
    })
}
