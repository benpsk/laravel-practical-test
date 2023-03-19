<?php

namespace App\Api\Service;

class CommonService
{
    /**
     * @return Formatter
     */
    public function formatter()
    {
        return Formatter::factory();
    }
}
