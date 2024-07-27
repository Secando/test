<?php

namespace app\Validations;

use App\Entity\User;
use App\Exceptions\ValidationException;
use Doctrine\ORM\EntityManager;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Factory;
use Respect\Validation\Validator as v;

class UserRegisterValidation
{
    public array $errors = [];

    public function __construct(private EntityManager $em)
    {
    }

    function validate($data)
    {
        $rules = [
            'name' => v::stringType()->length(2, 20)->notEmpty(),
            'email' => v::email()->notEmpty(),
            'password' => v::notEmpty()->length(5, 40),
            'password_confirm' => v::equals($data['password']),
        ];
        $translate = [
            'length' => 'Минимальная длинна должна быть: {{minValue}} ',
            'email' => 'Поле должно являтся почтой',
            'equals' => 'Пароли не совпадают',
            'stringType' => 'Поле должно содержать только буквы'
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