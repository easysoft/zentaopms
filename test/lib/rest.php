<?php
include dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'lib/requests/requests.class.php';
/**
 * The rest class.
 *
 * @package test
 */
class rest
{
    /**
     * The base url of request.
     *
     * @var string
     * @access public
     */
    public $base;

    /**
     * Construct.
     *
     * @param  string $base
     * @access public
     * @return void
     */
	public function __construct($base)
    {
        $this->base = $base;
    }

    /**
     * Get method.
     *
     * @param  string $url
     * @param  array  $headers
     * @access public
     * @return object
     */
	public function get($url, $headers = array())
    {
        $headers['Accept'] = 'application/json';
        $resp = requests::get($this->base . $url, $headers, array());
        try
        {
            $resp->body = json_decode($resp->body);
        }
        catch(Exception $e)
        {
        }

        return $resp;
    }

    /**
     * Post method.
     *
     * @param  string $url
     * @param  array  $data
     * @param  array  $headers
     * @access public
     * @return object
     */
	public function post($url, $data = array(), $headers = array())
    {
        $headers['Accept']       = 'application/json';
        $headers['Content-Type'] = 'application/json';
        $data = json_encode($data);

        $resp = requests::post($this->base . $url, $headers, $data, array());
        try
        {
            $resp->body = json_decode($resp->body);
        }
        catch(Exception $e)
        {
        }

        return $resp;
    }

    /**
     * Put method.
     *
     * @param  string $url
     * @param  array  $data
     * @param  array  $headers
     * @access public
     * @return object
     */
	public function put($url, $data = array(), $headers = array())
    {
        $headers['Accept']       = 'application/json';
        $headers['Content-Type'] = 'application/json';

        $resp = requests::put($this->base . $url, $headers, $data, $options = array());
        try
        {
            $resp->body = json_decode($resp->body);
        }
        catch(Exception $e)
        {
        }

        return $resp;
    }

    /**
     * Delete method.
     *
     * @param  string $url
     * @param  array  $headers
     * @param  array  $options
     * @access public
     * @return object
     */
	public function delete($url, $headers = array())
    {
        $headers['Accept'] = 'application/json';
        return requests::delete($this->base . $url, $headers, array());
    }
}
