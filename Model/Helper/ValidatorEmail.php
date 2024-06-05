<?php

declare(strict_types=1);

namespace Ferreira\LoginByTaxvat\Model\Helper;

class ValidatorEmail
{
    /**
     * Check if string $email is email valid or invalid
     *
     * @param string $email
     * @return bool|string
     */
    public function validaEmail(string $email): bool|string
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
