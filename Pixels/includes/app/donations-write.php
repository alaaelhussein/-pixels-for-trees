<?php
function donation_to_row(array $d): array
{
    $known = ['id','userId','userName','amount','pixelsData','message',
              'status','treeState','treeRef','providerId',
              'confirmedAt','reservedUntil','createdAt'];
    $extra = array_diff_key($d, array_flip($known));
    return [
        'id'             => $d['id'] ?? '',
        'user_id'        => $d['userId'] ?? '',
        'user_name'      => $d['userName'] ?? '',
        'amount'         => (int) ($d['amount'] ?? 0),
        'pixels_data'    => json_encode($d['pixelsData'] ?? []),
        'message'        => $d['message'] ?? '',
        'status'         => $d['status'] ?? 'pending',
        'tree_state'     => $d['treeState'] ?? 'pending',
        'tree_ref'       => $d['treeRef'] ?? '',
        'provider_id'    => $d['providerId'] ?? '',
        'confirmed_at'   => $d['confirmedAt'] ?? '',
        'reserved_until' => $d['reservedUntil'] ?? '',
        'created_at'     => $d['createdAt'] ?? date(DATE_ATOM),
        'meta'           => json_encode($extra),
    ];
}

function create_donation(array $user, array $pixels, string $message): array
{
    $donation = [
        'id'            => make_id('don'),
        'userId'        => $user['id'],
        'userName'      => $user['name'],
        'amount'        => count($pixels) * price_per_pixel(),
        'pixelsData'    => $pixels,
        'message'       => $message,
        'status'        => 'pending',
        'treeState'     => 'pending',
        'treeRef'       => '',
        'providerId'    => '',
        'confirmedAt'   => '',
        'reservedUntil' => date(DATE_ATOM, time() + reservation_seconds()),
        'createdAt'     => date(DATE_ATOM),
    ];
    $row  = donation_to_row($donation);
    $stmt = db()->prepare("
        INSERT INTO donations
            (id, user_id, user_name, amount, pixels_data, message,
             status, tree_state, tree_ref, provider_id,
             confirmed_at, reserved_until, created_at, meta)
        VALUES
            (:id, :user_id, :user_name, :amount, :pixels_data, :message,
             :status, :tree_state, :tree_ref, :provider_id,
             :confirmed_at, :reserved_until, :created_at, :meta)
    ");
    $stmt->execute($row);
    return $donation;
}

function replace_donation(array $next): void
{
    $row  = donation_to_row($next);
    $stmt = db()->prepare("
        INSERT OR REPLACE INTO donations
            (id, user_id, user_name, amount, pixels_data, message,
             status, tree_state, tree_ref, provider_id,
             confirmed_at, reserved_until, created_at, meta)
        VALUES
            (:id, :user_id, :user_name, :amount, :pixels_data, :message,
             :status, :tree_state, :tree_ref, :provider_id,
             :confirmed_at, :reserved_until, :created_at, :meta)
    ");
    $stmt->execute($row);
}

function delete_donation(string $id): void
{
    $stmt = db()->prepare("DELETE FROM donations WHERE id = ?");
    $stmt->execute([$id]);
}

function reset_donations(): void
{
    db()->exec("DELETE FROM donations");
}
