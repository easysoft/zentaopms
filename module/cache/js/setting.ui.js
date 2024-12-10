function toggleCache()
{
    $('.cache').toggleClass('hidden', $(this).val() != '1');
    toggleDriver.call($('[name=driver]:checked'));
}

function toggleDriver()
{
    const enable = $('[name=enable]:checked').val();
    $('.apcu').toggleClass('hidden', enable == 0 || $(this).val() != 'apcu');
    $('.redis').toggleClass('hidden', enable == 0|| $(this).val() != 'redis');
}
