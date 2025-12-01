<?php
require_once 'includes/session-init.php';
require_once 'includes/security-headers.php';
require_once 'functions/connect.php';
require_once 'includes/RBAC.php';
RBAC ('user', 'index.php');

$statistics = query("SELECT damageAbsorbed, damageDealt, damageTaken, damageResisted, mobKills, playerKills FROM player_statistics WHERE uuid = ?", [$uuid], "s");

?>
<div class="flex flex-col">
    <div class="flex items-center mb-2">
        <img src="https://cdn-icons-png.flaticon.com/128/786/786346.png" alt="Death Logs Icon" class="w-5 h-5 mr-2" style="filter: invert(1);">
        <p class="text-2xl font-bold text-white">Combat</p>
    </div>
    <table class="w-full text-sm text-gray-200">
        <tr>
            <td class="py-1 pr-2 font-medium text-gray-300">Mob Kills</td>
            <td class="py-1 text-right"><?= $statistics['mobKills']; ?></td>
        </tr>
        <tr>
            <td class="py-1 pr-2 font-medium text-gray-300">Player Kills</td>
            <td class="py-1 text-right"><?= $statistics['playerKills']; ?></td>
        </tr>
        <tr>
            <td class="py-1 pr-2 font-medium text-gray-300">Damage Absorbed</td>
            <td class="py-1 text-right"><?= $statistics['damageAbsorbed'] ?></td>
        </tr>
        <tr>
            <td class="py-1 pr-2 font-medium text-gray-300">Damage Dealt</td>
            <td class="py-1 text-right"><?= $statistics['damageDealt'] ?></td>
        </tr>
        <tr>
            <td class="py-1 pr-2 font-medium text-gray-300">Damage Resisted</td>
            <td class="py-1 text-right"><?= $statistics['damageResisted'] ?></td>
        </tr>
        <tr>
            <td class="py-1 pr-2 font-medium text-gray-300">Damage Taken</td>
            <td class="py-1 text-right"><?= $statistics['damageTaken'] ?></td>
        </tr>
    </table>
</div>