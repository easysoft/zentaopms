<?php
foreach($lang->admin->menuList->feature['tabMenu']['project'] as $key => $value)
{
    if(!in_array($key, array('project', 'execution', 'story')))
    {
        unset($lang->admin->menuList->feature['tabMenu']['project'][$key]);
    }
}

foreach($lang->admin->menuList->feature['tabMenu']['menuOrder']['project'] as $key => $value)
{
    if(!in_array($key, array('project', 'execution', 'story')))
    {
        unset($lang->admin->menuList->feature['tabMenu']['menuOrder']['project'][$key]);
    }
}