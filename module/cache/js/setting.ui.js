function toggleCache()
{
    $('.cache').toggleClass('hidden', $(this).val() != '1');
    toggleDriver.call($('[name=driver]:checked'));
}

function toggleDriver()
{
    $('.apcu').toggleClass('hidden', $(this).val() != 'apcu');
    $('.redis').toggleClass('hidden', $(this).val() != 'redis');
}
