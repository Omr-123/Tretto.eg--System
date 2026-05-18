<?php
require_once __DIR__ . '/../Model/user.php';
require_once __DIR__ . '/../Model/RegistrationValidator.php';
require_once __DIR__ . '/../Model/LoginValidator.php';

class AuthController
{
    private UserModel $userModel;
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->userModel = new UserModel();
    }

    /**
     * @return array{success: bool, authError: string, old: array{email: string}, redirect?: string}
     */
    public function processLogin(array $post): array
    {
        $email = trim($post['email'] ?? '');
        $password = $post['password'] ?? '';

        $result = LoginValidator::authenticate($this->userModel, $email, $password);

        if (!empty($result['errors'])) {
            unset(
                $_SESSION['logged_in'],
                $_SESSION['userID'],
                $_SESSION['name'],
                $_SESSION['email'],
                $_SESSION['user_type']
            );

            return [
                'success' => false,
                'authError' => 'Invalid email or password',
                'old' => ['email' => $email],
            ];
        }

        $user = $result['user'];

        $_SESSION['userID'] = $user->userID;
        $_SESSION['name'] = $user->name;
        $_SESSION['email'] = $user->email;
        $_SESSION['user_type'] = $user->user_type;
        $_SESSION['logged_in'] = true;

        $redirect = ($user->user_type === 'admin')
            ? '/Tretto.eg--System/MVC/View/GUI/admin-dashboard.php'
            : '/Tretto.eg--System/MVC/View/GUI/index.php';

        return [
            'success' => true,
            'authError' => '',
            'old' => ['email' => $email],
            'redirect' => $redirect,
        ];
    }

    public function login(): void
    {
        $result = $this->processLogin($_POST);

        if (!$result['success']) {
            $authError = $result['authError'];
            $old = $result['old'];
            require __DIR__ . '/../View/GUI/login.php';
            exit;
        }

        header('Location: ' . $result['redirect']);
        exit;
    }

    public function register(): void
    {
        $fname = trim($_POST['fname'] ?? '');
        $lname = trim($_POST['lname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = $_POST['password'] ?? '';

        $_SESSION['old_input'] = [
            'fname' => $fname,
            'lname' => $lname,
            'email' => $email,
            'phone' => $phone,
        ];

        $errors = RegistrationValidator::validate([
            'fname' => $fname,
            'lname' => $lname,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
        ]);

        if (RegistrationValidator::validateEmailFormat($email) && $this->userModel->emailExists($email)) {
            $errors['email'] = 'This email is already registered';
        }

        if (!empty($errors)) {
            $_SESSION['field_errors'] = $errors;
            header('Location: /Tretto.eg--System/MVC/View/GUI/register.php');
            exit;
        }

        $created = $this->userModel->createUser([
            'name' => $fname . ' ' . $lname,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'user_type' => 'user',
        ]);

        if (!$created) {
            $_SESSION['field_errors'] = ['_global' => 'Something went wrong. Please try again.'];
            header('Location: /Tretto.eg--System/MVC/View/GUI/register.php');
            exit;
        }

        unset($_SESSION['old_input'], $_SESSION['field_errors']);
        $_SESSION['auth_success'] = 'Account created successfully! Please sign in.';
        header('Location: /Tretto.eg--System/MVC/View/GUI/login.php');
        exit;
    }

    public function checkEmail(): void
    {
        header('Content-Type: application/json');

        $email = trim($_GET['email'] ?? '');

        if (!RegistrationValidator::validateEmailFormat($email)) {
            echo json_encode(['valid' => false, 'exists' => false, 'message' => 'Invalid email format']);
            exit;
        }

        $exists = $this->userModel->emailExists($email);
        echo json_encode([
            'valid' => true,
            'exists' => $exists,
            'message' => $exists ? 'This email is already registered' : '',
        ]);
        exit;
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();
        header('Location: /Tretto.eg--System/MVC/View/GUI/index.php');
        exit;
    }

    private function redirectWithError(string $page, string $message): void
    {
        $_SESSION['auth_error'] = $message;
        header("Location: /Tretto.eg--System/MVC/View/GUI/{$page}.php");
        exit;
    }
}

if ($_SERVER['SCRIPT_NAME'] == '/Tretto.eg--System/MVC/Controller/AuthController.php') {

    $controller = new AuthController();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

        if (($_GET['action'] ?? '') == 'checkEmail')
        {
            $controller->checkEmail();
        }

        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $action = $_POST['action'] ?? '';

        if ($action == 'login') {
            $controller->login();

        } elseif ($action == 'register') {
            $controller->register();

        } elseif ($action == 'logout') {
            $controller->logout();

        } else {
            header('Location: /Tretto.eg--System/MVC/View/GUI/login.php');
        }

        exit;
    }
}
