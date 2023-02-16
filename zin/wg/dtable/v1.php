<?php

namespace zin;

class dtable extends wg
{
    protected static $defineProps = 'data,height,plugins,responsive';

    protected function build()
    {
        $id = $this->prop('id');
        $options = json_encode($this->props->skip('id'));
        return h::div(
            setId($id),
            h::js(<<<END
            var options = $options;
            console.log(options);
            const dtable = new zui.DTable('#$id', options);
            END)
        );
    }
}
