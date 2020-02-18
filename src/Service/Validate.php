<?php
namespace App\Service;


use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class Validate
{
    private $validator;
    /**
     * Validate constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }
    public function validateRequest($data)
    {
        $errors = $this->validator->validate($data);
        $errorsResponse = array();
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $errorsResponse[] = [
                'field' => $error->getPropertyPath(),
                'message' => $error->getMessage()
            ];
        }
        if (count($errors)) {
            $reponse = array(
                'code' => false,
                'message' => 'validation errors',
                'errors' => $errorsResponse,
                'result' => null
            );
            return $reponse;
        } else {
            $reponse = [];
            return $reponse;
        }
    }
}
