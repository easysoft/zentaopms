$(function()
{
    $object = $('.btn-toolbar.pull-left');
    while(true)
    {
        if($object.children(':first-child').hasClass('divider'))
        {
            $object.children(':first-child').remove();
            continue;
        }
        if($object.children(':last-child').hasClass('divider'))
        {
            $object.children(':last-child').remove();
            continue;
        }

        break;
    }
})
