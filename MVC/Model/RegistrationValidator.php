<?php
class RegistrationValidator
{
    private const NAME_PATTERN = '/^[\x{0600}-\x{06FF}a-zA-Z]+(?:\s+[\x{0600}-\x{06FF}a-zA-Z]+)*$/u';
    private const PHONE_PATTERN = '/^01[0125][0-9]{8}$/';
    public static function validate(array $data): array
    {
        $errors = [];
        $fname = trim($data['fname'] ?? '');
        $lname = trim($data['lname'] ?? '');
        $email = trim($data['email'] ?? '');
        $phone = trim($data['phone'] ?? '');
        $password = $data['password'] ?? '';

        $fnameError = self::validateName($fname);
        if ($fnameError !== null) {
            $errors['fname'] = $fnameError;
        }

        $lnameError = self::validateName($lname);
        if ($lnameError !== null) {
            $errors['lname'] = $lnameError;
        }

        if ($email === '') {
            $errors['email'] = 'Invalid email format';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        }

        if ($phone === '') {
            $errors['phone'] = 'Invalid Egyptian phone number';
        } elseif (!preg_match(self::PHONE_PATTERN, $phone)) {
            $errors['phone'] = 'Invalid Egyptian phone number';
        }

        if (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters';
        }

        return $errors;
    }

    public static function validateName(string $value): ?string
    {
        if ($value === '') {
            return 'Name must contain letters only';
        }

        if (preg_match('/[0-9]/', $value) || preg_match('/[^\x{0600}-\x{06FF}a-zA-Z\s]/u', $value)) {
            return 'Numbers and symbols are not allowed';
        }

        if (!preg_match(self::NAME_PATTERN, $value)) {
            return 'Name must contain letters only';
        }

        return null;
    }
    public static function validateEmailFormat(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    public static function validatePhone(string $phone): bool
    {
        return preg_match('/^01[0125][0-9]{8}$/', $phone);
    }
    public static function validatePassword(string $password): bool
    {
        return strlen($password) >= 8;
    }
}
