<?php

require_once __DIR__ . '/../src/Validation/Rules/Rule.php';
require_once __DIR__ . '/../src/Validation/Rules/Email.php';
require_once __DIR__ . '/../src/Validation/Rules/MaxLength.php';
require_once __DIR__ . '/../src/Validation/Rules/MinLength.php';
require_once __DIR__ . '/../src/Validation/Rules/Numeric.php';
require_once __DIR__ . '/../src/Validation/Rules/Required.php';
require_once __DIR__ . '/../src/Validation/Validator.php';

use Validation\Rules\Email;
use Validation\Rules\MaxLength;
use Validation\Rules\MinLength;
use Validation\Rules\Numeric;
use Validation\Rules\Required;
use Validation\Validator;

class ValidationTest
{
    private function assert($condition, $message)
    {
        if (!$condition) {
            throw new Exception("Assertion failed: $message");
        }
    }

    public function testEmailRule()
    {
        $rule = new Email();
        $this->assert($rule->validate('user@example.com'), "Valid email should pass");
        $this->assert(!$rule->validate('invalid-email'), "Invalid email should fail");
        $this->assert($rule->getMessage() === "The field must be a valid email address.", "Email rule message is correct");
        echo "Email rule test passed.\n";
    }

    public function testMaxLengthRule()
    {
        $rule = new MaxLength(5);
        $this->assert($rule->validate('12345'), "String of max length should pass");
        $this->assert(!$rule->validate('123456'), "String exceeding max length should fail");
        $this->assert($rule->getMessage() === "The field must not exceed 5 characters.", "MaxLength rule message is correct");
        echo "MaxLength rule test passed.\n";
    }

    public function testMinLengthRule()
    {
        $rule = new MinLength(5);
        $this->assert($rule->validate('12345'), "String of min length should pass");
        $this->assert(!$rule->validate('1234'), "String below min length should fail");
        $this->assert($rule->getMessage() === "The field must be at least 5 characters.", "MinLength rule message is correct");
        echo "MinLength rule test passed.\n";
    }

    public function testNumericRule()
    {
        $rule = new Numeric();
        $this->assert($rule->validate('123'), "Numeric string should pass");
        $this->assert($rule->validate(123), "Integer should pass");
        $this->assert($rule->validate('123.45'), "Float string should pass");
        $this->assert(!$rule->validate('abc'), "Non-numeric string should fail");
        $this->assert($rule->getMessage() === "The field must be a numeric value.", "Numeric rule message is correct");
        echo "Numeric rule test passed.\n";
    }

    public function testRequiredRule()
    {
        $rule = new Required();
        $this->assert($rule->validate('value'), "Non-empty string should pass");
        $this->assert(!$rule->validate(''), "Empty string should fail");
        $this->assert(!$rule->validate(null), "Null should fail");
        $this->assert($rule->getMessage() === "The field is required.", "Required rule message is correct");
        echo "Required rule test passed.\n";
    }

    public function testValidator()
    {
        $validator = new Validator();
        $validator->addRule('email', new Email())
                  ->addRule('email', new Required())
                  ->addRule('password', new Required())
                  ->addRule('password', new MinLength(8))
                  ->addRule('age', new Numeric());

        $validData = [
            'email' => 'user@example.com',
            'password' => 'password123',
            'age' => '30'
        ];

        $invalidData = [
            'email' => 'invalid-email',
            'password' => 'short',
            'age' => 'not a number'
        ];

        $this->assert($validator->validate($validData), "Valid data should pass validation");
        $this->assert(empty($validator->getErrors()), "No errors for valid data");

        $this->assert(!$validator->validate($invalidData), "Invalid data should fail validation");
        $errors = $validator->getErrors();
        $this->assert(isset($errors['email']), "Email error should be present");
        $this->assert(isset($errors['password']), "Password error should be present");
        $this->assert(isset($errors['age']), "Age error should be present");

        echo "Validator test passed.\n";
    }

    public function runTests()
    {
        $this->testEmailRule();
        $this->testMaxLengthRule();
        $this->testMinLengthRule();
        $this->testNumericRule();
        $this->testRequiredRule();
        $this->testValidator();
        echo "All validation tests passed successfully.\n";
    }
}

// Run the tests
$test = new ValidationTest();
$test->runTests();