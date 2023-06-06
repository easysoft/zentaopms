$(function()
{
    var dataviewSql   = JSON.parse(sessionStorage.getItem('dataviewSql'));
    var fieldSettings = sessionStorage.getItem('fieldSettings');
    var langs         = sessionStorage.getItem('langs');

    $.each(dataviewSql, function(index, value)
    {
        if(value.name == 'sql')
        {
            dataviewSql = value.value;
            return;
        }
    });

    $('#sql').val(dataviewSql);
    $('#fields').val(fieldSettings);
    $('#langs').val(langs);
});

function locate(method, params)
{
    var link = createLink('dataview', method, params);
    window.location.href = link;
}
