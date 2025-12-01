<?php
// CODEX RATING
// Efficiency: 7/10
// Security: 6/10
// Readability: 9/10

// Initialize session and security headers
require_once '../includes/session-init.php';
require_once '../includes/security-headers.php';
require_once '../functions/connect.php';
require_once '../includes/RBAC.php';
RBAC ('admin', '../index.php');

// Redirect to login if user is not authenticated
if (!isset($_SESSION['uuid'])) {
    header('Location: auth/login.php');
    exit();
}

// Get UUID from GET parameter or session
$uuid = $_GET['player'] ?? $_SESSION['uuid'];

// Query player combat and armor data
$sql = "SELECT 
    helmet, helmetPattern, helmetMaterial, helmetEnchants,
    chestplate, chestplatePattern, chestplateMaterial, chestplateEnchants,
    leggings, leggingsPattern, leggingsMaterial, leggingsEnchants,
    boot, bootPattern, bootMaterial, bootEnchants
    FROM loadouts
    WHERE uuid = '$uuid'";

$result = $conn->query($sql);

// Define armor slots and their corresponding DB keys
$armorSlots = [
    'Head' => [
        'db_key' => 'helmet',
        'pattern' => 'helmetPattern',
        'material' => 'helmetMaterial',
        'enchants' => 'helmetEnchants',
        'empty' => 'empty helmet'
    ],
    'Body' => [
        'db_key' => 'chestplate',
        'pattern' => 'chestplatePattern',
        'material' => 'chestplateMaterial',
        'enchants' => 'chestplateEnchants',
        'empty' => 'empty chestplate'
    ],
    'Legs' => [
        'db_key' => 'leggings',
        'pattern' => 'leggingsPattern',
        'material' => 'leggingsMaterial',
        'enchants' => 'leggingsEnchants',
        'empty' => 'empty leggings'
    ],
    'Feet' => [
        'db_key' => 'boot',
        'pattern' => 'bootPattern',
        'material' => 'bootMaterial',
        'enchants' => 'bootEnchants',
        'empty' => 'empty boots'
    ],
];

// Armor attributes for each material and slot
$armorAttributes = [
    'leather' => [
        'helmet' => '+ 1 Armor',
        'chestplate' => '+ 3 Armor',
        'leggings' => '+ 2 Armor',
        'boots' => '+ 1 Armor',
    ],
    'golden' => [
        'helmet' => '+ 2 Armor',
        'chestplate' => '+ 5 Armor',
        'leggings' => '+ 3 Armor',
        'boots' => '+ 1 Armor',
    ],
    'chainmail' => [
        'helmet' => '+ 2 Armor',
        'chestplate' => '+ 5 Armor',
        'leggings' => '+ 4 Armor',
        'boots' => '+ 1 Armor',
    ],
    'iron' => [
        'helmet' => '+ 2 Armor',
        'chestplate' => '+ 6 Armor',
        'leggings' => '+ 5 Armor',
        'boots' => '+ 2 Armor',
    ],
    'diamond' => [
        'helmet' => '+ 3 Armor<br>+ 2 Armor Toughness',
        'chestplate' => '+ 8 Armor<br>+ 2 Armor Toughness',
        'leggings' => '+ 6 Armor<br>+ 2 Armor Toughness',
        'boots' => '+ 3 Armor<br>+ 2 Armor Toughness',
    ],
    'netherite' => [
        'helmet' => '+ 3 Armor<br>+ 3 Armor Toughness<br>+ 0.1 Knockback Resistance',
        'chestplate' => '+ 8 Armor<br>+ 3 Armor Toughness<br>+ 0.1 Knockback Resistance',
        'leggings' => '+ 6 Armor<br>+ 3 Armor Toughness<br>+ 0.1 Knockback Resistance',
        'boots' => '+ 3 Armor<br>+ 3 Armor Toughness<br>+ 0.1 Knockback Resistance',
    ],
    'turtle' => [
        'helmet' => '+ 2 Armor',
    ],
];

// List of items that are not considered armor
$nonArmorItems = [
    'elytra', 'jack o lantern', 'carved pumpkin', 'creeper head', 'skeleton skull',
    'wither skeleton skull', 'zombie head', 'player head', 'dragon head', 'piglin head'
];

// Helper: Check if item is an armor piece
function isArmorPiece($name, $nonArmorItems) {
    if (empty($name)) return false;
    $lowerName = strtolower($name);
    foreach ($nonArmorItems as $item) {
        if (strpos($lowerName, $item) !== false) return false;
    }
    return true;
}

// Helper: Get armor attribute string for tooltip
function getArmorAttribute($name, $slot, $armorAttributes) {
    if (empty($name)) return '';
    $pieceName = strtolower($name);
    $parts = explode(' ', $pieceName);
    $material = $parts[0];
    $slotMap = [
        'Head' => 'helmet',
        'Body' => 'chestplate',
        'Legs' => 'leggings',
        'Feet' => 'boots',
    ];
    $slotKey = $slotMap[$slot] ?? strtolower($slot);
    if ($material === 'turtle') {
        return $armorAttributes['turtle']['helmet'] ?? '';
    }
    return $armorAttributes[$material][$slotKey] ?? '';
}

// Helper: Build armor slot data for rendering
function buildArmorSlot($row, $slot, $info) {
    $item = $row[$info['db_key']] ?? null;
    $enchants = $row[$info['enchants']] ?? null;
    $hasEnchants = !empty($enchants);
    // Select image based on enchant status and item presence
    $image = 'assets/armor_sets/' . ($item !== null ? ($hasEnchants ? 'enchanted ' : '') . $item : $info['empty']) . ($hasEnchants ? '.gif' : '.webp');
    return [
        'slot' => $slot,
        'image' => $image,
        'name' => $item !== null ? ucwords($item) : null,
        'trim' => $row[$info['pattern']] !== null ? str_replace(['minecraft:', '_pattern'], '', $row[$info['pattern']]) : null,
        'trim_material' => $row[$info['material']] !== null ? str_replace(['minecraft:', '_material'], '', $row[$info['material']]) : null,
        'enchants' => $enchants !== null ? array_map('ucwords', explode(', ', $enchants)) : null,
    ];
}

// Helper: Render tooltip HTML for armor piece
function renderArmorTooltip($piece, $nonArmorItems, $armorAttributes) {
    if (empty($piece['name'])) return '';
    ob_start();
    ?>
    <div class="armor-tooltip absolute z-50 left-0 top-0 hidden min-w-[200px] bg-neutral-900/95 shadow-lg p-1 pointer-events-none">
        <div class="border-2 border-[#34009a] p-1 text-white font-minecraft leading-none flex-col flex-grow">
            <p class="mb-1 font-bold text-white"><?= ($piece['name']) ?></p>
            <?php if (!empty($piece['trim'])): ?>
                <p class="text-gray-400">Upgrade:</p>
                <p class="ml-2 text-white"><?= ucwords($piece['trim']) ?> Armor Trim</p>
                <p class="ml-2 text-white"><?= ucwords($piece['trim_material']) ?> Material</p>
            <?php endif; ?>
            <?php if (!empty($piece['enchants'])): ?>
                <?php foreach ($piece['enchants'] as $enchant): ?>
                    <?php
                        $isCurse = stripos($enchant, 'Curse Of Binding') !== false || stripos($enchant, 'Curse Of Vanishing') !== false;
                        $enchantClass = $isCurse ? 'text-red-500' : 'text-gray-400';
                    ?>
                    <p class="<?= $enchantClass ?>"><?= $enchant ?></p>
                <?php endforeach; ?>
            <?php endif; ?>
            <?php if (isArmorPiece($piece['name'], $nonArmorItems)): ?>
                <p class="mt-5 text-gray-400">When on <?= $piece['slot'] ?>:</p>
                <?php $attribute = getArmorAttribute($piece['name'], $piece['slot'], $armorAttributes); ?>
                <?php if ($attribute): ?>
                    <p class="text-[#504fed]"><?= $attribute ?></p>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

$sql = "SELECT damageAbsorbed, damageDealt, damageTaken, damageResisted  FROM player_statistics WHERE uuid = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $uuid);
$stmt->execute();
$stmt->bind_result($damageAbsorbed, $damageDealt, $damageTaken, $damageResisted);
$stmt->fetch();
$stmt->close();

// If player data found, extract stats and armor info
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Build armor array for rendering
    $armor = [];
    foreach ($armorSlots as $slot => $info) {
        $armor[] = buildArmorSlot($row, $slot, $info);
    }
} else {
    echo "No statistics found for UUID: $uuid";
}
?>
<!-- Armor display and stats table -->
<div class="flex flex-col">
    <div class="flex items-center">
        <img src="https://cdn-icons-png.flaticon.com/128/786/786346.png" alt="Death Logs Icon" class="w-5 h-5 mr-2" style="filter: invert(1);">
        <p class="text-2xl font-bold text-white">Armor</p>
    </div>
    <div class="flex mt-4">
        <?php foreach ($armor as $i => $piece): ?>
            <div class="flex relative items-center justify-center p-4 group aspect-square bg-[url(../assets/item_slot.webp)] armor-piece bg-cover" data-index="<?= $i ?>">
                <img src="<?= htmlspecialchars($piece['image']) ?>" alt="<?= htmlspecialchars($piece['name'] ?? 'Empty Slot') ?>" class="w-12">
                <?= renderArmorTooltip($piece, $nonArmorItems, $armorAttributes); ?>
            </div>
        <?php endforeach; ?>
    </div>
    <table class="w-full mt-5 text-sm text-gray-200">
        <tr>
            <td class="py-1 pr-2 font-medium text-gray-300">Damage Absorbed</td>
            <td class="py-1 text-right"><?= $damageAbsorbed ?></td>
        </tr>
        <tr>
            <td class="py-1 pr-2 font-medium text-gray-300">Damage Dealt</td>
            <td class="py-1 text-right"><?= $damageDealt ?></td>
        </tr>
        <tr>
            <td class="py-1 pr-2 font-medium text-gray-300">Damage Resisted</td>
            <td class="py-1 text-right"><?= $damageResisted ?></td>
        </tr>
        <tr>
            <td class="py-1 pr-2 font-medium text-gray-300">Damage Taken</td>
            <td class="py-1 text-right"><?= $damageTaken ?></td>
        </tr>
    </table>
</div>