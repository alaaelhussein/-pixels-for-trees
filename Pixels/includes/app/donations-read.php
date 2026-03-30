<?php
function row_to_donation(array $row): array
{
    $meta = json_decode((string) ($row['meta'] ?? '{}'), true) ?: [];
    $d = [
        'id'            => $row['id'],
        'userId'        => $row['user_id'],
        'userName'      => $row['user_name'],
        'amount'        => (int) $row['amount'],
        'pixelsData'    => json_decode((string) ($row['pixels_data'] ?? '[]'), true) ?: [],
        'message'       => $row['message'],
        'status'        => $row['status'],
        'treeState'     => $row['tree_state'],
        'treeRef'       => $row['tree_ref'],
        'providerId'    => $row['provider_id'],
        'confirmedAt'   => $row['confirmed_at'],
        'reservedUntil' => $row['reserved_until'],
        'createdAt'     => $row['created_at'],
    ];
    return array_merge($d, $meta);
}

function donations_all(): array
{
    $rows = db()->query("SELECT * FROM donations ORDER BY created_at ASC")->fetchAll();
    return array_map('row_to_donation', $rows);
}

function donations_save(array $items): void
{
    $pdo = db();
    $stmt = $pdo->prepare("
        INSERT OR REPLACE INTO donations
            (id, user_id, user_name, amount, pixels_data, message,
             status, tree_state, tree_ref, provider_id,
             confirmed_at, reserved_until, created_at, meta)
        VALUES
            (:id, :user_id, :user_name, :amount, :pixels_data, :message,
             :status, :tree_state, :tree_ref, :provider_id,
             :confirmed_at, :reserved_until, :created_at, :meta)
    ");
    foreach ($items as $d) {
        $stmt->execute(donation_to_row($d));
    }
}

function find_donation(string $id): ?array
{
    $stmt = db()->prepare("SELECT * FROM donations WHERE id = ?");
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ? row_to_donation($row) : null;
}

function user_donations(string $userId): array
{
    $stmt = db()->prepare("SELECT * FROM donations WHERE user_id = ? ORDER BY created_at ASC");
    $stmt->execute([$userId]);
    return array_map('row_to_donation', $stmt->fetchAll());
}

function leaderboard_players(int $limit = 50): array
{
    $stmt = db()->prepare("
        SELECT user_name, SUM(json_array_length(pixels_data)) AS pixel_count
        FROM donations
        WHERE status = 'confirmed'
        GROUP BY user_id, user_name
        ORDER BY pixel_count DESC
        LIMIT ?
    ");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}
