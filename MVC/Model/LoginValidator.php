<?php

class LoginValidator
{
    public const INVALID_EMAIL = 'Invalid email or password';
    public const INVALID_PASSWORD = 'Invalid password';
    public static function validateEmailFormat(string $email): bool
    {
        return $email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * @return array{errors: array<string, string>, user: object|false}
     */
    public static function authenticate(UserModel $userModel, string $email, string $password): array
    {
        $errors = [];

        if ($email === '' || !self::validateEmailFormat($email)) {
            $errors['email'] = self::INVALID_EMAIL;
            return ['errors' => $errors, 'user' => false];
        }

        $user = $userModel->findByEmail($email);

        if (!$user) {
            $errors['email'] = self::INVALID_EMAIL;
            return ['errors' => $errors, 'user' => false];
        }

        if ($password === '' || $password !== $user->password) {
            $errors['login'] = self::INVALID_EMAIL;
            return ['errors' => $errors, 'user' => false];
        }

        return ['errors' => [], 'user' => $user];
    }
}
