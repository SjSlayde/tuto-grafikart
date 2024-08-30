<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_PROPERTY | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class Banworld extends Constraint
{
    public function __construct(
        public string $message =  'le mot " {{ banWord }} " n\'est pas autoriser', 
        public array $banWords = ['spam','viagra'],
        ?array $groups = null,
        mixed $payload = null
        )
    {
        parent:: __construct(null,$groups,$payload);
    }

}
