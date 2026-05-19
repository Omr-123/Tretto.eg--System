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
     *   isEmpty: bool,
     *   checkout: ?array
     * }
     */
    public function loadPageData(int $userId): array
    {
        $items = $this->buildPaymentItemsFromCart($userId);
        $totals = $this->model->calculateTotals($items);
        $paymentMethod = $_POST['payment_method'] ?? 'visa';

        $checkoutId = (int) ($_SESSION['checkoutID'] ?? 0);
        $checkout = $checkoutId > 0
            ? $this->model->getCheckoutById($checkoutId, $userId)
            : null;

        $error = '';
        if (empty($items)) {
            $error = '';
        } elseif (!$checkout) {
            $error = 'Please complete shipping details on the checkout page first.';
        }

        return [
            'items' => $items,
            'totals' => $totals,
            'paymentMethod' => $paymentMethod,
            'error' => $error,
            'isEmpty' => empty($items),
            'checkout' => $checkout,
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

        $items = $this->buildPaymentItemsFromCart($userId);
        if (empty($items)) {
            return [
                'success' => false,
                'error' => 'Your cart is empty.',
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
                'error' => 'Please complete shipping details on the checkout page first.',
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

    /**
     * @return list<array<string, mixed>>
     */
    private function buildPaymentItemsFromCart(int $userId): array
    {
        if ($userId <= 0) {
            return [];
        }

        $items = $this->buildPaymentItemsViaCartController($userId);
        if (!empty($items)) {
            return $items;
        }

        return $this->model->getCartLineItems($userId);
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function buildPaymentItemsViaCartController(int $userId): array
    {
        $cart = new Cart_Controller();
        $products = $cart->get_cart_items($userId);
        $cartInfo = $cart->get_cart_info($userId);

        if (!is_array($cartInfo) || $cartInfo === [] || empty($products)) {
            return [];
        }

        $items = [];
        foreach ($products as $i => $product) {
            if (!$product) {
                continue;
            }

            $row = $cartInfo[$i] ?? [];
            if ($row === []) {
                continue;
            }

            $pvid = (int) ($row['pvid'] ?? 0);
            $qty = (int) ($row['quantity'] ?? 1);
            $unit = (float) ($row['price'] ?? 0);
            $variant = $this->findVariant($product, $pvid);

            if ($unit <= 0) {
                $unit = (float) ($product->price ?? 0);
                if ($variant) {
                    $unit += (float) ($variant->add_price ?? 0);
                }
            }

            $imgPath = '';
            if ($variant && !empty($variant->img_url[0])) {
                $imgPath = (string) $variant->img_url[0];
            }

            $items[] = [
                'pid' => (int) ($product->pid ?? $row['PID'] ?? 0),
                'pvid' => $pvid,
                'name' => (string) ($product->name ?? 'Product'),
                'image' => $imgPath !== '' ? $imgPath : '../assets/images/placeholder.svg',
                'size' => $variant && isset($variant->size) ? (string) $variant->size : 'N/A',
                'color' => $variant && !empty($variant->color) ? (string) $variant->color : 'N/A',
                'quantity' => $qty,
                'unit_price' => $unit,
                'subtotal' => $unit * $qty,
            ];
        }

        return $items;
    }

    private function findVariant(object $product, int $pvid): ?object
    {
        if ($pvid <= 0 || empty($product->variants)) {
            return $product->variants[0] ?? null;
        }

        foreach ($product->variants as $variant) {
            if ((int) ($variant->pvid ?? 0) === $pvid) {
                return $variant;
            }
        }

        return $product->variants[0] ?? null;
    }
}
