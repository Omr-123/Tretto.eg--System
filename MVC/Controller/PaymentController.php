<?php
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../Model/PaymentModel.php';

class PaymentController
{
    private PaymentModel $model;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        global $conn;
        $this->model = new PaymentModel($conn);
    }

    /**
     * @return array{
     *   items: array,
     *   totals: array,
     *   paymentMethod: string,
     *   error: string,
     *   isEmpty: bool
     * }
     */
    public function loadPageData(int $userId): array
    {
        $items = $this->model->getCartLineItems($userId);
        $totals = $this->model->calculateTotals($items);
        $paymentMethod = $_POST['payment_method'] ?? 'visa';

        return [
            'items' => $items,
            'totals' => $totals,
            'paymentMethod' => $paymentMethod,
            'error' => '',
            'isEmpty' => empty($items),
        ];
    }

    /**
     * @return array{success: bool, orderID?: int, error?: string, paymentMethod: string}
     */
    public function processPlaceOrder(int $userId, array $post): array
    {
        $paymentMethod = strtolower(trim($post['payment_method'] ?? 'visa'));
        if (!in_array($paymentMethod, ['visa', 'cod'], true)) {
            $paymentMethod = 'visa';
        }

        $items = $this->model->getCartLineItems($userId);
        if (empty($items)) {
            return [
                'success' => false,
                'error' => 'Your cart is empty',
                'paymentMethod' => $paymentMethod,
            ];
        }

        if ($paymentMethod === 'visa') {
            $cardName = trim($post['card_name'] ?? '');
            $cardNumber = preg_replace('/\s+/', '', $post['card_number'] ?? '');
            $expiry = trim($post['card_expiry'] ?? '');
            $cvv = trim($post['card_cvv'] ?? '');

            if ($cardName === '' || strlen($cardNumber) < 13 || $expiry === '' || strlen($cvv) < 3) {
                return [
                    'success' => false,
                    'error' => 'Please complete all card details.',
                    'paymentMethod' => $paymentMethod,
                ];
            }
        }

        $checkoutId = (int) ($_SESSION['checkoutID'] ?? 0);
        if ($checkoutId <= 0) {
            return [
                'success' => false,
                'error' => 'Please complete shipping details first.',
                'paymentMethod' => $paymentMethod,
            ];
        }

        $totals = $this->model->calculateTotals($items);
        $result = $this->model->placeOrder(
            $userId,
            $checkoutId,
            $paymentMethod,
            $items,
            $totals['total']
        );

        $result['paymentMethod'] = $paymentMethod;
        return $result;
    }
}
