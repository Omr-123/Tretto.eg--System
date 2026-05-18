<?php
class TrackOrder
{
    private $pdo;
    private const STEPS = [

        'placed' => 'Placed',
        'confirmed' => 'Confirmed',
        'shipped' => 'Shipped',
        'delivered' => 'Delivered'

    ];
    private const STATUS_MAP = [

        'pending' => 'placed',
        'placed' => 'placed',
        'processing' => 'confirmed',
        'confirmed' => 'confirmed',
        'shipped' => 'shipped',
        'delivered' => 'delivered'

    ];
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }
    public function findByReference($reference)
    {
        $reference = trim($reference);
        if ($reference == '') {
            return null;
        }
        $row = null;
        if (ctype_digit($reference))
             {

            $row = $this->fetchRow(
                'o.orderID = :id',
                [':id' => (int) $reference]
            );
        }
        if ($row == null) {

            $row = $this->fetchRow(
                't.trackingNumber = :ref',
                [':ref' => $reference]
            );
        }
        if (
            $row == null &&
            preg_match('/(\d+)$/', $reference, $m)
        ) {

            $row = $this->fetchRow(
                'o.orderID = :id',
                [':id' => (int) $m[1]]
            );
        }
        if (!$row) {
            return null;
        }

        return $this->formatOrder($row);
    }
    private function fetchRow($where, $params)
    {
        $sql = "

            SELECT

                o.orderID,
                o.orderDate,
                o.totalAmount,
                o.status,
                o.deliveryDate AS order_delivery,

                t.trackingNumber,
                t.deliveryDate AS track_delivery,
                t.currentLocation

            FROM orders o

            LEFT JOIN track_order t
            ON t.orderID = o.orderID

            WHERE $where

            LIMIT 1
        ";
        $stmt = $this->pdo->prepare($sql);

        $stmt->execute($params);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }
    private function formatOrder($row)
    {
        $statusKey = $this->mapStatus($row['status']);

        $orderId = (int) $row['orderID'];

        $estimated =
            $row['track_delivery']
            ?: $row['order_delivery'];
        return [

            'order_id' =>

                $row['trackingNumber']
                ? $row['trackingNumber']
                : 'TRT-' .
                date('Ymd', strtotime($row['orderDate'])) .
                '-' .
                str_pad($orderId, 4, '0', STR_PAD_LEFT),
            'order_date' =>

                date(
                    'd M Y',
                    strtotime($row['orderDate'])
                ),
            'total_price' =>

                number_format(
                    (float) $row['totalAmount'],
                    2
                ) . ' EGP',
            'status' => ucfirst($statusKey),

            'status_key' => $statusKey,
            'estimated_delivery' =>

                $estimated
                ? date('d M Y', strtotime($estimated))
                : null,
            'current_location' =>

                $row['currentLocation'],
            'timeline' =>

                $this->buildTimeline($statusKey)

        ];
    }
    private function mapStatus($dbStatus)
    {
        $key = strtolower(trim($dbStatus));

        return self::STATUS_MAP[$key] ?? 'placed';
    }
    private function buildTimeline($statusKey)
    {
        $keys = array_keys(self::STEPS);

        $activeIndex = array_search(
            $statusKey,
            $keys
        );
        if ($activeIndex === false) {
            $activeIndex = 0;
        }
        $timeline = [];
        foreach ($keys as $i => $key) {

            $state = 'pending';
            if ($i < $activeIndex) {

                $state = 'done';

            } else if ($i == $activeIndex) 
            {
                if ($statusKey == 'delivered') {
                    $state = 'done';
                } else {
                    $state = 'active';
                }
            }
            $timeline[] = [

                'label' => self::STEPS[$key],
                'state' => $state
            ];
        }
        return $timeline;
    }
}