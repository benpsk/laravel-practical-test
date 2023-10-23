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
     * @var self
     */
    public static $instance;


    /**
     * @var string|null
     */
    protected ?string $endpoint = null;
    protected ?string $token = null;
    protected float $start = 0;
    protected int $end = 0;
    protected array $fieldName;

    protected array $fieldValue;
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
        if ($status) $this->status = $status;

        $response = $this->defaultFormat();

        if ($this->token) $response["token"] = $this->tokenInfo();

        $response["data"] = $data;

        return response()
            ->json($response, $this->status);
    }

    /**
     * @return array
     */
    public function tokenInfo(): array
    {
        return [
            "type" => "Bearer",
            "access_token" => $this->token,
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
            'duration' => $this->getDuration()
        ];
    }

    protected function getDuration(): float
    {
        $end = microtime(true);
        return round(($end - $this->start) * 1000, 2);
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

    public function setStart(float $start): static
    {
        $this->start = $start;
        return $this;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;
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
}
