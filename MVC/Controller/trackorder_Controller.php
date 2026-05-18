<?php

require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../Model/TrackOrder.php';

class TrackOrderController
{
    /**
     * @return array{order: ?array, trackError: string, orderRef: string}
     */
    public function processTrack(array $post): array
    {
        $orderRef = trim($post['order_id'] ?? '');
        $order = null;
        $trackError = '';

        if ($orderRef === '') {
            return [
                'order' => null,
                'trackError' => 'Order not found',
                'orderRef' => '',
            ];
        }

        $database = new Database();
        $pdo = $database->getConnection();

        if ($pdo) {
            $tracker = new TrackOrder($pdo);
            $order = $tracker->findByReference($orderRef);
        }

        if ($order === null) {
            $trackError = 'Order not found';
        }
        return [
            'order' => $order,
            'trackError' => $trackError,
            'orderRef' => $orderRef,
        ];
    }
}