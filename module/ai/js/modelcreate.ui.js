window.testConnection = () =>
{
    $('#model-form').find('button[type="submit"]').attr('disabled', 'disabled');
    $('#test-conn-btn').attr('disabled', 'disabled');
    $.ajax(
    {
        type: 'POST',
        url: $.createLink('ai', 'modelTestConnection'),
        data: $('#model-form').serialize(),
        dataType: 'json',
        success: data =>
        {
            if(data.result == 'success')
            {
                zui.Messager.show({content: data.message, type: 'success'})
            }
            else
            {
                zui.Messager.show({content: data.message, type: 'danger'})
            }
        },
        complete: () =>
        {
            $('#model-form').find('button[type="submit"]').removeAttr('disabled');
            $('#test-conn-btn').removeAttr('disabled');
        }
    });
}

const handleModelTypeChange = () =>
{
    const modelType = $('select[name="type"]').val();
    const vendorList = window.vendorListLang[modelType];
    $('select[name="vendor"]').html('');
    for(const vendor in vendorList) $('select[name="vendor"]').append('<option value="' + vendor + '">' + vendorList[vendor] + '</option>');
    $('select[name="vendor"]').trigger('chosen:updated');
    $('select[name="vendor"]').trigger('change');
}

const handleVendorChange = () =>
{
    const vendor = $('select[name="vendor"]').val();
    const requiredFields = window.vendorList[vendor]['credentials'];
    const vendorTip = window.vendorTipsLang[vendor];
    $('.vendor-tips').html(vendorTip ? vendorTip : '');
    $('.vendor-row').each((_, el) => {$(el).toggle(requiredFields.includes($(el).find('input').attr('name')));});
}
handleVendorChange();

const handleProxyTypeChange = () =>
{
    $('#proxy-addr-container').toggle($('select[name="proxyType"]').val() != '');
}

$(() =>
{
    $('select[name="type"]').on('change', handleModelTypeChange);
    $('select[name="vendor"]').on('change', handleVendorChange);
    $('select[name="proxyType"]').on('change', handleProxyTypeChange);
    $('button[type="submit"]]').on('click', e =>
    {
        $('button[type="submit"]').attr('disabled', 'disabled');
        $('#test-conn-btn').attr('disabled', 'disabled');
        e.preventDefault();

        $.ajax(
        {
            type: 'POST',
            url: $.createLink('ai', 'modelcreate'),
            data: $('#model-form').serialize(),
            dataType: 'json',
            success: data =>
            {
                if(data.result == 'success')
                {
                    zui.Messager.show({content: data.message, type: 'success'});
                    window.location.href = $.createLink('ai', 'models');
                }
                else
                {
                    zui.Messager.show({content: data.message, type: 'danger'});
                }
            },
            complete: () =>
            {
                $('button[type="submit"]').removeAttr('disabled');
                $('#test-conn-btn').removeAttr('disabled');
            }
        });
    });
});
