<?php
declare(strict_types=1);
namespace zin;

jsVar('method', $this->app->methodName);
jsVar('pageParams', $this->app->params);

featureBar
(
    to::before(select
    (
        setStyle(array('width' => '120px')),
        set(array
        (
            'items' => $userList,
            'value' => $user->id,
            'onchange' => "window.switchAccount(this.value);"
        ))
    )),
    set::items($this->user->getFeatureBarMenus($user)),
);
