<?php
require_once __DIR__ . '/../Model/user.php';
class AuthController
{
    private UserModel $userModel;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        $this->userModel = new UserModel();
    }
    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (empty($email) || empty($password)) {
            $this->redirectWithError('login', 'Please fill in all fields.');
            return;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || $password !== $user->password) {
            $this->redirectWithError('login', 'Incorrect email or password.');
            return;
        }

        $_SESSION['userID'] = $user->userID;
        $_SESSION['name'] = $user->name;
        $_SESSION['email'] = $user->email;
        $_SESSION['user_type'] = $user->user_type;
        $_SESSION['logged_in'] = true;

        if ($user->user_type === 'admin') {
            header('Location: /Tretto.eg--System/MVC/View/GUI/component/admin_dashboard.php');
        } else {
            header('Location: /Tretto.eg--System/MVC/View/GUI/component/index.php');
        }
        exit;
    }
    public function register(): void
    {
        $fname = trim($_POST['fname'] ?? '');
        $lname = trim($_POST['lname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $_SESSION['old_input'] = [
            'fname' => $fname,
            'lname' => $lname,
            'email' => $email,
            'phone' => $phone,
            'user_type' => 'user',


        ];
        if (empty($fname) || empty($lname) || empty($email) || empty($password)) {
            $this->redirectWithError('register', 'Please fill in all required fields.');
            return;
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->redirectWithError('register', 'email');
            return;
        }
        if ($this->userModel->emailExists($email)) {
            $this->redirectWithError('register', 'email');
            return;
        }
        if (!empty($phone) && !preg_match('/^01[0125][0-9]{8}$/', $phone)) {
            $this->redirectWithError('register', 'phone');
            return;
        }
        if (strlen($password) < 8) {
            $this->redirectWithError('register', 'password');
            return;
        }
        $created = $this->userModel->createUser([
            'name' => $fname . ' ' . $lname,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'user_type' => 'user',
        ]);
        if (!$created) {
            $this->redirectWithError('register', 'Something went wrong. Please try again.');
            return;
        }

        unset($_SESSION['old_input']);
        $_SESSION['auth_success'] = 'Account created successfully! Please sign in.';
        header('Location: /Tretto.eg--System/MVC/View/GUI/login.php');
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
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new AuthController();
    $action = trim($_POST['action'] ?? '');

    match ($action) {
        'login' => $controller->login(),
        'register' => $controller->register(),
        'logout' => $controller->logout(),
        default => header('Location: /Tretto.eg--System/MVC/View/GUI/login.php'),
    };
    exit;
}