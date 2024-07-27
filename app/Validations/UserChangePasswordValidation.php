<?php

namespace app\Validations;

use App\Entity\User;
use App\Exceptions\ValidationException;
use Doctrine\ORM\EntityManager;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Factory;
use Respect\Validation\Validator as v;

class UserChangePasswordValidation
{
    public array $errors = [];

    public function __construct(private EntityManager $em)
    {
    }

    function validate($data)
    {
        $rules = [
            'new_password' => v::notEmpty()->length(5, 40),
        ];
        $translate = [
            'length' => 'Минимальная длинна должна быть: {{minValue}} ',
        ];
        foreach ($rules as $field => $rule) {
            try {
                $rule->assert($data[$field]);
            } catch (NestedValidationException $ex) {
                $this->errors[$field] = $ex->getMessages($translate);
            }
        }
        if ($this->errors) {
            throw new ValidationException($this->errors);
        }

    }
}