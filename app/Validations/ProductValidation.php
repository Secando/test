<?php

namespace app\Validations;

use App\Entity\User;
use App\Exceptions\ValidationException;
use Doctrine\ORM\EntityManager;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Factory;
use Respect\Validation\Validator as v;

class ProductValidation
{
    public array $errors = [];

    public function __construct(private EntityManager $em)
    {
    }

    function validate($data)
    {
        $rules = [
            'english_name' => v::stringType()->length(5,60 )->notEmpty(),
            'rus_name' => v::stringType()->length(5,60 )->notEmpty(),
            'page_count' => v::number()->notEmpty(),
            'isbn' => v::stringType()->length(5, 40)->notEmpty(),
            'author' => v::stringType()->notEmpty(),
            'cost' => v::number()->notEmpty(),

        ];
        $translate = [
            'length' => 'Минимальная длинна должна быть: {{minValue}} ',
            'email' => 'Поле должно являтся почтой',
            'equals' => 'Пароли не совпадают',
            'stringType' => 'Поле должно содержать только буквы',
            'number' => 'Поле должно содержать число',
            'notEmpyu' => 'Поле не должно быть пустым',
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