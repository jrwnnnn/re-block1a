<?php
    require_once 'includes/security-headers.php';
    require_once 'includes/session-init.php';
    require_once 'functions/connect.php';

    $sql = "SELECT status, expiry, username FROM playpass WHERE uuid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $uuid);
    $stmt->execute();
    $result = $stmt->get_result();
    $playpassData = $result->fetch_assoc();
    $stmt->close();

    $statusText = match ($playpassData['status']) {
        0 => 'Expired',
        1 => 'Neutral',
        2 => 'Trial',
        3 => 'Active',
        default => 'Unknown',
    };
    $playpassData['status'] = $statusText;

    function prime($uuid) {
        $stmt = $conn->prepare("UPDATE playpass SET status = 3 WHERE uuid = ?");
        $stmt->bind_param("s", $uuid);
        $stmt->execute();
        $stmt->close();
        exit;
    }

?>
<div class="grid gap-10 md:grid-cols-2">
    <div class="flex flex-col">
        <h2 class="text-2xl font-semibold text-gray-200">PlayPass Controls</h2>
        <table class="w-full mt-2 mb-5 text-gray-200">
            <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Status</td>
                <td class="py-1 text-right"><?= htmlspecialchars($playpassData['status']); ?></td>
            </tr>
            <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Expiry</td>
                <td class="py-1 text-right"><?= htmlspecialchars($playpassData['expiry'] ?? 'N/A'); ?></td>
            </tr>
        </table>
        <div class="flex flex-row gap-5">
        <p type="submit" onclick="prime()" class="bg-green-500 glob-btn hover:bg-green-600">Prime</p>
        <p type="submit" class="!text-red-400 bg-gray-700 hover:bg-gray-600 glob-btn">Reset</p>
        </div>
    </div>
</div>
