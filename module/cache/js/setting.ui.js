function toggleCache()
{
    $('.cache').toggleClass('hidden', $(this).val() != '1');
}

function toggleDriver()
{
    $('.apcu').toggleClass('hidden', $(this).val() != 'apcu');
    $('.redis').toggleClass('hidden', $(this).val() != 'redis');
}
