<?php

$page = page(
    button('BUTTON'),
    h('h1', 'hello'),
    btn('哈哈')->primary(),
    div(
        h2('Headings2'),
        h3('Headings3'),
        p('lorem', h5::strong('bold'))
    )
)->title('哈哈哈')->x();
