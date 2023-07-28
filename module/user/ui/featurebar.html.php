<?php
declare(strict_types=1);
namespace zin;

jsVar('method', $this->app->methodName);
jsVar('pageParams', $this->app->params);

featureBar
(
    to::before
    (
        picker
        (
            width('120px'),
            set::items($userList),
            set::value($user->id),
            set::required(true),
            on::change('switchAccount'),
        )
    ),
    set::items($this->user->getFeatureBarMenus($user)),
);
