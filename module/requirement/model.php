<?php
class requirementModel extends model
{
    public static function isClickable(object $data, string $action): bool
    {
        global $app;
        $app->control->loadModel('story');
        return call_user_func_array(array('storyModel', 'isClickable'), array($data, $action));
    }

    public function getToAndCcList(object $story, string $actionType)
    {
        return $this->loadModel('story')->getToAndCcList($story, $actionType);
    }
}
