<?php
namespace zin;

panel
(
    set::title($version->explain),
    set::shadow(false),
    div
    (
        setID('details'),
        zui::nestedList
        (
            set::items(array(array(
                'title'     => $lang->index->detailed,
                'className' => 'bg-surface-strong text-primary rounded',
                'items'     => array(array('className' => 'bg-gray-100 text-fore', 'content' => array('html' => $version->log)))
            )))
        )
    )
);
