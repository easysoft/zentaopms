<?php
namespace zin;

page
(
    prop('title', $title),
    h('h1', 'hello'),
    button('BUTTON'),
    btn('Primary')->primary()->rounded(),
    div
    (
        setclass('primary-pale'),
        h2('Headings2'),
        h3('Headings3'),
        html('<div>test</div>'),
        p('lorem', strong('bold'))
    )
)->x();
