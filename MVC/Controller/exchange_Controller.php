<?php
require_once __DIR__ . '/../Model/exchange.php';

class ExchangeController
{
    private ExchangeModel $model;
    private int $currentUserID;

    // -------------------------------------------------------------------------

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        $this->model = new ExchangeModel();
        $this->currentUserID = (int) ($_SESSION['userID'] ?? 0);
    }

    // -------------------------------------------------------------------------
    // PAGE LOAD
    // -------------------------------------------------------------------------

    public function loadPage(): array
    {
        $orders = $this->model->getDeliveredOrdersByUser($this->currentUserID);
        $displayRows = [];

        foreach ($orders as $order) {
            $orderID = (int) $order['orderID'];
            $items = $this->model->getOrderItems($orderID);

            foreach ($items as $item) {
                $displayRows[] = [
                    'order_id' => $orderID,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'product_image' => $item['product_image'],
                    'price' => $item['price'],
                    'delivery_date' => $item['delivery_date'],
                    'variant' =>
    $item['category'] . ' — ' .
    mb_substr($item['product_description'] ?? '', 0, 60),
                ];
            }
        }

        return [
            'orders' => $displayRows,
            'products' => $this->model->getAvailableProducts(),
        ];
    }

    // -------------------------------------------------------------------------
    // FORM SUBMIT  (normal POST — no JSON)
    // -------------------------------------------------------------------------

    public function handleSubmit(): array
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return ['success' => false, 'message' => 'Invalid request.'];
        }

        if (!$this->currentUserID) {
            return ['success' => false, 'message' => 'Please log in first.'];
        }

        $raw = $_POST;
        $errors = $this->validate($raw);

        if (!empty($errors)) {
            return [
                'success' => false,
                'message' => 'Please fix the errors below.',
                'errors' => $errors,
            ];
        }

        $orderID = (int) $raw['order_id'];

        if (!$this->model->isOrderOwnedByUser($orderID, $this->currentUserID)) {
            return ['success' => false, 'message' => 'This order does not belong to your account.'];
        }

        $data = [
            'order_id' => $orderID,
            'user_id' => $this->currentUserID,
            'old_product_id' => (int) $raw['old_product_id'],
            'new_product_id' => !empty($raw['new_product_id']) ? (int) $raw['new_product_id'] : null,
            'request_type' => $raw['request_type'],
            'reason' => $raw['reason'],
            'details' => $raw['details'],
            'preferred_size' => $raw['preferred_size'] ?? null,
            'preferred_color' => $raw['preferred_color'] ?? null,
            'contact_method' => $raw['contact_method'],
        ];

        $requestID = $this->model->createExchangeRequest($data);

        if (!$requestID) {
            return ['success' => false, 'message' => 'Could not save your request. Please try again.'];
        }

        $saved = $this->model->getExchangeRequestByID($requestID);

        if (!$saved) {
            return ['success' => false, 'message' => 'Request saved but could not be retrieved.'];
        }

        return [
            'success' => true,
            'reference' => 'REF-' . date('Ymd') . '-' . str_pad($requestID, 4, '0', STR_PAD_LEFT),
            'request_type' => $saved['request_type'],
            'product_name' => $saved['old_product_name'],
            'reason' => $saved['reason'],
            'order_id' => $saved['order_ref'],
            'submitted_at' => date('d M Y, H:i', strtotime($saved['created_at'])),
            'contact' => $saved['contact_method'],
        ];
    }

    // -------------------------------------------------------------------------
    // VALIDATION
    // -------------------------------------------------------------------------

    private function validate(array $data): array
    {
        $errors = [];

        if (empty($data['order_id']) || !is_numeric($data['order_id']))
            $errors['order_id'] = 'Please select an order.';

        if (empty($data['old_product_id']) || !is_numeric($data['old_product_id']))
            $errors['old_product_id'] = 'Invalid product.';

        if (empty($data['request_type']) || !in_array($data['request_type'], ['refund', 'exchange'], true))
            $errors['request_type'] = 'Please select a valid request type.';

        if (empty($data['reason']))
            $errors['reason'] = 'Please select a reason.';

        $len = mb_strlen($data['details'] ?? '');
        if ($len < 20)
            $errors['details'] = 'Minimum 20 characters required.';
        elseif ($len > 1000)
            $errors['details'] = 'Maximum 1000 characters allowed.';

        $validMethods = ['whatsapp', 'email', 'phone'];
        if (empty($data['contact_method']) || !in_array($data['contact_method'], $validMethods, true))
            $errors['contact_method'] = 'Please select a contact method.';

        if (!empty($data['preferred_size']) && mb_strlen($data['preferred_size']) > 10)
            $errors['preferred_size'] = 'Invalid size value.';

        return $errors;
    }
}