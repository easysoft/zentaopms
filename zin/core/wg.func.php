<?php
namespace zin;

require_once 'wg.class.php';
require_once 'directive.func.php';

function extractArgs()
{

}

function inherit($item)
{
    if(!($item instanceof wg)) $item = new wg($item);
    return array
    (
        set($item->props),
        $item->children()
    );
}
