<?php
/**
 * Get assign to me params.
 *
 * @access public
 * @return json
 */
public function getAssignToMeParams()
{
    $params = new stdclass();
    $params->todoCount['name']    = $this->lang->block->todoCount;
    $params->todoCount['default'] = 20;
    $params->todoCount['control'] = 'input';

    $params->taskCount['name']    = $this->lang->block->taskCount;
    $params->taskCount['default'] = 20;
    $params->taskCount['control'] = 'input';

    return json_encode($params);
}
