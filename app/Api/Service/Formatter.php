<?php

namespace App\Api\Service;

use Illuminate\Support\Facades\Request;

class Formatter
{
    /**
     * This variable is used to set data count
     *
     * @var
     */
    protected $count;

    /**
     * @var
     */
    protected $total;

    /**
     * @var
     */
    protected $method;

    /**
     * @var
     */
    protected $message;

    /**
     * @var array
     */
    protected $meta = [];

    /**
     * @var int
     */
    protected $limit = 30;

    /**
     * @var int
     */
    protected $success = 1;

    /**
     * @var int
     */
    protected $status = 200;

    /**
     * @var null
     */
    protected $time = null;
    /**
     * @var
     */
    public static $instance;


    protected $endpoint = null;


    /**
     * Singleton
     *
     * @return Formatter
     */
    public static function factory()
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
            $method = self::$instance->getMethod();
            if ($method === 'post') {
                self::$instance->status = 201;
            }
        }

        return self::$instance;
    }

    public function make($data)
    {
        $response = $this->defaultFormat();

        $response["data"] = $data;
        return $response;
    }


    public function authResponse(string $token, $data = [])
    {
        $response = $this->defaultFormat();
        $response["token"] = $this->tokenInfo($token);
        if (!empty($data)) {
            $response["data"] = $data;
        }

        return response()->json($response);
    }

    /**
     * @param $token
     * @return array
     */
    public function tokenInfo($token)
    {
        return [
            "type" => "Bearer",
            "access_token" => $token,
            "expired_at" => null
        ];
    }

    /**
     * @return string
     */
    protected function getMethod()
    {
        $method = Request::method();
        $this->method = $method;
        return strtolower($method);
    }


    /**
     * @return string
     */
    protected function getEndpoint()
    {
        $endpoint = Request::path();
        $this->endpoint = $endpoint;
        return $endpoint;
    }


    /**
     * @return array
     */
    protected function defaultFormat()
    {
        $format = [
            "success" => $this->success,
            "status" => $this->status,
            "meta" => $this->getMeta(),
        ];

        return $format;
    }

    public function getMeta()
    {
        return [
            'method' => $this->getMethod(),
            'endpoint' => $this->getEndpoint(),
        ];
    }

    /**
     * @param $exception
     * @param $code
     * @return array
     */
    public function makeErrorException($exception, $code)
    {
        $this->success = 0;
        $this->status = 500;
        if (max((int)$code, 0)) :
            $this->status = (int) $code;
        endif;

        $response = $this->defaultFormat();
        $error = json_decode($exception);

        if (is_string($exception) && json_last_error() == JSON_ERROR_NONE) :
            $response["errors"] = $error;
        else :
            $response["errors"] = [
                "message" => $exception
            ];
        endif;

        return $response;
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status)
    {
        $this->status = $status;

        return $this;
    }


    public function extraField($fields =  [])
    {
        $i = 0;
        foreach ($fields as $key => $value) {
            $this->fieldName[$i] = $key;
            $this->fieldValue[$i] = $value;
            $i++;
        }
        return $this;
    }

    protected function __clone()
    {
    }

    protected function __construct()
    {
    }
}
