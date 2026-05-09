<?php
/**
 * The zai promote zentao skill view file of zai module of ZenTaoPMS.
 * @copyright   Copyright 2026 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Hao Sun<sunhao@chandao.com>
 * @package     zai
 * @link        https://www.zentao.net
 */
namespace zin;

set::bodyClass('p-0');

div
(
    setClass('group sticky top-0 backdrop-blur z-10 overflow-hidden shadow'),
    style::backgroundImage('linear-gradient(to bottom right, transparent 0%, #D9EAFF 80%), repeating-radial-gradient(circle at 0 0, transparent 0, #fff 27px), repeating-linear-gradient(#D9EAFF55, #D9EAFF)'),
    div
    (
        setClass('absolute right-0 top-0 pointer-events-none group-hover:scale-110 transition-transform duration-500'),
        style::width(328)->height(154),
        style::background('no-repeat url(' . $config->webRoot . 'static/images/zentao-cli-skill.png) right 48px top 30px'),
        style::backgroundSize('203px')
    ),
    div
    (
        setClass('py-6 px-8'),
        style::minHeight('148px'),
        p(setClass('text-lg'), $lang->zai->zentaoSkillLeading),
        h3(setClass('text-aurora mt-2'), style::animationDuration('10s'), $lang->zai->zentaoSkillTitle),
        p(setClass('text-gray mt-4'), $lang->zai->zentaoSkillSubtitle)
    )
);

div
(
    setClass('px-8 py-6'),
    setCssVar('--article-p-space', '0.5rem'),
    css(
        '.markdown-content h4{color:var(--color-primary-500)}',
        '.markdown-content .markdown-code-block{max-height:200px}'
    ),
    markdown($lang->zai->zentaoSkillGuide)
);
