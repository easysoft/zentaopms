<?php
class serverroomTest
{
    public function __construct()
    {
        global $tester;
        $this->objectModel = $tester->loadModel('serverroom');
    }

    /**
     * Test create method.
     *
     * @param  object $roomData
     * @access public
     * @return array|bool
     */
    public function createTest(object $roomData): array|bool
    {
        $result = $this->objectModel->create($roomData);
        if($result) return true;

        return dao::getError();
    }

    /**
     * Test update method.
     *
     * @param  int    $roomID
     * @param  object $roomData
     * @access public
     * @return array|bool
     */
    public function updateTest(int $roomID, object $roomData): array|bool
    {
        $result = $this->objectModel->update($roomID, $roomData);
        if($result) return $result;

        return dao::getError();
    }
}
