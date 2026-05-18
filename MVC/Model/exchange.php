<?php
require_once __DIR__ . '/../../db.php';

class ExchangeModel
{
    public mysqli $db;

    public function __construct()
    {
        global $conn;
        $this->db = $conn;
    }

    // -------------------------------------------------------------------------
    // ORDERS
    // -------------------------------------------------------------------------

    public function getDeliveredOrdersByUser(int $userID): array
    {
        $result = $this->db->query("
            SELECT orderID, userID, orderDate, totalAmount,
                   status, shippingAddress, paymentMethod, deliveryDate
            FROM orders
            WHERE userID = $userID
             AND LOWER(status) = 'delivered'
            ORDER BY deliveryDate DESC
        ");

        if (!$result)
            return [];
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // -------------------------------------------------------------------------

   public function getOrderItems(int $orderID): array
{
    $result = $this->db->query("
        SELECT
            oi.orderItemID,
            oi.orderID,
            oi.PID AS product_id,
            oi.quantity,
            oi.price,

            p.name AS product_name,
            p.image AS product_image,
            p.category,
            p.descriptions AS description,

            o.deliveryDate AS delivery_date

        FROM orderItems oi
        JOIN product p
            ON p.PID = oi.PID
        JOIN orders o
            ON o.orderID = oi.orderID

        WHERE oi.orderID = $orderID
    ");

    if (!$result) {
        return [];
    }

    return $result->fetch_all(MYSQLI_ASSOC);
}

    // -------------------------------------------------------------------------
    // EXCHANGE REQUESTS
    // -------------------------------------------------------------------------

    public function createExchangeRequest(array $d): int|false
    {
        $order_id = (int) $d['order_id'];
        $user_id = (int) $d['user_id'];
        $old_product_id = (int) $d['old_product_id'];
        $new_product_id = $d['new_product_id'] ? (int) $d['new_product_id'] : 'NULL';
        $request_type = $this->db->real_escape_string($d['request_type']);
        $reason = $this->db->real_escape_string($d['reason']);
        $details = $this->db->real_escape_string($d['details']);
        $preferred_size = $d['preferred_size']
            ? "'" . $this->db->real_escape_string($d['preferred_size']) . "'"
            : 'NULL';
        $preferred_color = $d['preferred_color']
            ? "'" . $this->db->real_escape_string($d['preferred_color']) . "'"
            : 'NULL';
        $contact_method = $this->db->real_escape_string($d['contact_method']);

        $ok = $this->db->query("
            INSERT INTO exchange_requests
                (order_id, user_id, old_product_id, new_product_id,
                 request_type, reason, details,
                 preferred_size, preferred_color, contact_method, status)
            VALUES
                ($order_id, $user_id, $old_product_id, $new_product_id,
                 '$request_type', '$reason', '$details',
                 $preferred_size, $preferred_color, '$contact_method', 'pending')
        ");

        if (!$ok)
            return false;
        return $this->db->insert_id;
    }

    // -------------------------------------------------------------------------

    public function getExchangeRequestByID(int $id): array|false
    {
        $result = $this->db->query("
            SELECT er.*, p.name AS old_product_name, o.orderID AS order_ref
            FROM exchange_requests er
            JOIN product p ON p.PID = er.old_product_id
            JOIN orders   o ON o.orderID = er.order_id
            WHERE er.request_id = $id
            LIMIT 1
        ");

        if (!$result)
            return false;
        $row = $result->fetch_assoc();
        return $row ?: false;
    }

    // -------------------------------------------------------------------------

    public function isOrderOwnedByUser(int $orderID, int $userID): bool
    {
        $result = $this->db->query("
            SELECT COUNT(*) AS cnt FROM orders
            WHERE orderID = $orderID
              AND userID  = $userID
              AND status  = 'Delivered'
        ");

        if (!$result)
            return false;
        return (bool) $result->fetch_assoc()['cnt'];
    }

    // -------------------------------------------------------------------------
    // PRODUCTS
    // -------------------------------------------------------------------------

    public function getAvailableProducts(): array
    {
        $result = $this->db->query("
            SELECT
    PID AS product_id,
    name,
    price,
    image,
    category
FROM product
ORDER BY name ASC
            
            WHERE stock > 0
            ORDER BY name ASC
        ");

        if (!$result)
            return [];
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}