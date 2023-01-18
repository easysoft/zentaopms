<?php

namespace zin;

page
(
    h('h1', 'hello'),
    button('BUTTON'),
    btn('Primary')->primary()->rounded(),
    div
    (
        h2('Headings2'),
        h3('Headings3'),
        p('lorem', strong('bold'))
    )
)->title($title)->x();
