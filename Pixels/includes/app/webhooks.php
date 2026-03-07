<?php
// webhooks.php — SQLite read/write layer for webhook log

function webhooks_all(): array
{
    $rows = db()->query("SELECT * FROM webhooks ORDER BY created_at ASC")->fetchAll();
    return array_map(function (array $row): array {
        return [
            'id'         => $row['id'],
            'donationId' => $row['donation_id'],
            'event'      => $row['event'],
            'status'     => $row['status'],
            'payload'    => json_decode((string) ($row['payload'] ?? '{}'), true) ?: [],
            'createdAt'  => $row['created_at'],
        ];
    }, $rows);
}

function log_webhook(array $item): void
{
    $stmt = db()->prepare("
        INSERT INTO webhooks (donation_id, event, status, payload, created_at)
        VALUES (:donation_id, :event, :status, :payload, :created_at)
    ");
    $stmt->execute([
        'donation_id' => $item['donationId'] ?? '',
        'event'       => $item['event'] ?? '',
        'status'      => $item['status'] ?? '',
        'payload'     => json_encode($item['payload'] ?? $item),
        'created_at'  => $item['createdAt'] ?? date(DATE_ATOM),
    ]);
}
