<?php

namespace App\Api\Service;

class CommonService
{
    /**
     * @return Formatter
     */
    public function formatter(): Formatter
    {
        return Formatter::factory();
    }
}
