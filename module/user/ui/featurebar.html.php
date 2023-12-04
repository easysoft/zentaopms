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
            set::items($deptUsers),
            set::value($user->id),
            set::required(true),
            set::onChange(jsRaw("(value) => switchAccount(value)"))
        )
    ),
    set::items($this->userZen->getFeatureBarMenus($user))
);
