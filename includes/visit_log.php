<?php
/**
 * includes/visit_log.php
 * Shared visit log helpers.
 */

function recordVisitorSignIn(PDO $pdo, int $visitorId): int
{
    $stmtLog = $pdo->prepare("SELECT log_id FROM visit_logs WHERE visitor_id = ? ORDER BY sign_in DESC LIMIT 1");
    $stmtLog->execute([$visitorId]);
    $latestLog = $stmtLog->fetch();

    if ($latestLog) {
        $update = $pdo->prepare("UPDATE visit_logs SET sign_in = CURRENT_TIMESTAMP, sign_out = NULL WHERE log_id = ?");
        $update->execute([$latestLog['log_id']]);

        $deleteOlderLogs = $pdo->prepare("DELETE FROM visit_logs WHERE visitor_id = ? AND log_id <> ?");
        $deleteOlderLogs->execute([$visitorId, $latestLog['log_id']]);

        return (int) $latestLog['log_id'];
    }

    $insert = $pdo->prepare("INSERT INTO visit_logs (visitor_id) VALUES (?)");
    $insert->execute([$visitorId]);

    return (int) $pdo->lastInsertId();
}
