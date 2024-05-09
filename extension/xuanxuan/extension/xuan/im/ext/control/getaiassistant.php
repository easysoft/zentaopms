<?php
helper::import('../../control.php');

class myIm extends im
{
    public function getAiAssistant($modelId, $userID = 0, $version = '', $device = 'desktop')
    {
        $assistants = $this->loadModel('ai')->getAssistantsByModel($modelId);

        $output         = new stdclass();
        $output->method = 'getaiassistant';
        $output->result = 'success';
        $output->data   = $assistants;

        return $this->im->sendOutput($output, 'getaiassistantResponse');
    }
}