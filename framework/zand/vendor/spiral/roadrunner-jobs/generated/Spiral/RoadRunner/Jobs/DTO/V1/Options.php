<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: jobs.proto

namespace Spiral\RoadRunner\Jobs\DTO\V1;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>jobs.v1.Options</code>
 */
class Options extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>int64 priority = 1;</code>
     */
    protected $priority = 0;
    /**
     * Generated from protobuf field <code>string pipeline = 2;</code>
     */
    protected $pipeline = '';
    /**
     * Generated from protobuf field <code>int64 delay = 3;</code>
     */
    protected $delay = 0;

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type int|string $priority
     *     @type string $pipeline
     *     @type int|string $delay
     * }
     */
    public function __construct($data = NULL) {
        \GPBMetadata\Jobs::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>int64 priority = 1;</code>
     * @return int|string
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Generated from protobuf field <code>int64 priority = 1;</code>
     * @param int|string $var
     * @return $this
     */
    public function setPriority($var)
    {
        GPBUtil::checkInt64($var);
        $this->priority = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string pipeline = 2;</code>
     * @return string
     */
    public function getPipeline()
    {
        return $this->pipeline;
    }

    /**
     * Generated from protobuf field <code>string pipeline = 2;</code>
     * @param string $var
     * @return $this
     */
    public function setPipeline($var)
    {
        GPBUtil::checkString($var, True);
        $this->pipeline = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>int64 delay = 3;</code>
     * @return int|string
     */
    public function getDelay()
    {
        return $this->delay;
    }

    /**
     * Generated from protobuf field <code>int64 delay = 3;</code>
     * @param int|string $var
     * @return $this
     */
    public function setDelay($var)
    {
        GPBUtil::checkInt64($var);
        $this->delay = $var;

        return $this;
    }

}
