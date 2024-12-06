function toggleCache()
{
    $('.cache').toggleClass('hidden', $(this).val() != '1');
    toggleDriver.call($('[name=driver]:checked'));
}

function toggleDriver()
{
    const enable = !!$('[name=enable]:checked').val();
    $('.apcu').toggleClass('hidden', !enable || $(this).val() != 'apcu');
    $('.redis').toggleClass('hidden', !enable || $(this).val() != 'redis');
}
