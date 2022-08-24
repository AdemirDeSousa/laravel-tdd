<?php

namespace App\Rules;

use App\Services\BrasilAPI\BrasilAPI;
use App\Services\BrasilAPI\Exceptions\CNPJNotFound;
use Illuminate\Contracts\Validation\Rule;

class ValidCNPJ implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        try {

            $cnpj = (new BrasilAPI())->cnpj($value);

            return $cnpj->isActive();

        } catch (CNPJNotFound $e) {

            return false;

        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'CNPJ inválido';
    }
}
