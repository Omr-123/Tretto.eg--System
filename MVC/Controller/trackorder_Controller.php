<?php
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../Model/TrackOrder.php';

class TrackOrderController
{
    public function processTrack(array $post): array
    {
        $orderRef = trim($post['order_id'] ?? '');

        if ($orderRef === '') {
            return ['order' => null, 'trackError' => 'Order not found', 'orderRef' => ''];
        }

        $database = new Databases();     
        $pdo = $database->getConnection();

        if (!$pdo) {
            return ['order' => null, 'trackError' => 'Database error', 'orderRef' => $orderRef];
        }

        $tracker = new TrackOrder($pdo);
        $order = $tracker->findByReference($orderRef);

        return [
            'order'      => $order,
            'trackError' => $order === null ? 'Order not found' : '',
            'orderRef'   => $orderRef,
        ];
    }
}