<?php
namespace zin;

page
(
    set    ('title', $title),
    h      ('h1', 'hello2')->setClass('text-danger'),
    btn
    (
        'Primary',
        set('.', 'primary'),
        set('active', true),
        set('icon', 'flag'),
    ),
    icon('project'),
    div
    (
        icon('star'),
        setClass ('primary-pale'),
        h2       ('Headings2'),
        h3       ('Headings3'),
        html     ('<div>test</div>'),
        p
        (
            'lorem',
            strong('bold'),
        )
    )
);
