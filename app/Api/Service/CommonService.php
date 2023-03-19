<?php

namespace App\Api\Service;

class CommonService
{
    /**
     * @return Formatter
     */
    protected function formatter()
    {
        return Formatter::factory();
    }
}
