<?php
declare(strict_types=1);
namespace App;

interface EntityErrorInterface
{
    /**
     * @return array
     */
    public function getRules(): array;

    /**
     * @return errors;
     */
    public function getErrors(): array;
}
