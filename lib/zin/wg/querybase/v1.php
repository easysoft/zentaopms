<?php
declare(strict_types=1);
namespace zin;

class queryBase extends wg
{
    protected static array $defineProps = array(
        'sql?: string',
        'cols?: array',
        'data?: array',
        'error?: string'
    );

    protected function build()
    {
        return null;
    }
}
