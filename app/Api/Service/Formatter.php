<?php

namespace App\Api\Service;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Request;

class Formatter
{

    /**
     * @var int
     */
    protected int $count;

    /**
     * @var int
     */
    protected int $total;

    /**
     * @var string
     */
    protected string $method;

    /**
     * @var string
     */
    protected string $message;

    /**
     * @var array
     */
    protected array $meta = [];

    /**
     * @var int
     */
    protected int $limit = 30;

    /**
     * @var int
     */
    protected int $success = 1;

    /**
     * @var int
     */
    protected int $status = 200;

    /**
     * @var string|null
     */
    protected ?string $time = null;

    /**
     * @var
     */
    public static $instance;


    /**
     * @var string|null
     */
    protected ?string $endpoint = null;


    /**
     * Singleton
     *
     * @return Formatter
     */
    public static function factory(): Formatter
    {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self();
            $method = self::$instance->getMethod();
            if ($method === 'post') {
                self::$instance->status = 200;
            }
        }

        return self::$instance;
    }

    /**
     * @param $data
     * @param $status
     * @return JsonResponse
     */
    public function make($data, $status = null): JsonResponse
    {
        $response = $this->defaultFormat();

        $response["data"] = $data;

        return response()
        ->json($response, $status ?? $this->status);
    }


    /**
     * @param string $token
     * @param $data
     * @return JsonResponse
     */
    public function authResponse(string $token, $data = []): JsonResponse
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
    public function tokenInfo($token): array
    {
        return [
            "type" => "Bearer",
            "access_token" => $token,
            "expired_at" => config('sanctum.expiration') * 60
        ];
    }

    /**
     * @return string
     */
    protected function getMethod(): string
    {
        $method = Request::method();
        $this->method = $method;
        return strtolower($method);
    }


    /**
     * @return string
     */
    protected function getEndpoint(): string
    {
        $endpoint = Request::path();
        $this->endpoint = $endpoint;
        return $endpoint;
    }


    /**
     * @return array
     */
    protected function defaultFormat(): array
    {
        return [
            "success" => $this->success,
            "status" => $this->status,
            "meta" => $this->getMeta(),
        ];

    }

    /**
     * @return array
     */
    public function getMeta(): array
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
    public function makeErrorException($exception, $code): array
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
    public function setStatus(int $status): static
    {
        $this->status = $status;
        return $this;
    }


    /**
     * @param $fields
     * @return $this
     */
    public function extraField($fields =  []): static
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
