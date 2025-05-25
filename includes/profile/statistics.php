<!-- TODO - Add enchantment glints to armor pieces -->

<?php
    require_once 'includes/session-init.php';
    require_once 'functions/connect.php';
    
    if (!isset($_SESSION['user_id'])) {
        header('Location: auth/login.php');
        exit();
    }
    
    $uuid = $_SESSION['uuid'];

    $sql = "SELECT 
        blockMined, blockPlaced, damageAbsorbed, damageDealt, damageTaken, deaths, 
        firstJoined, lastSeen, mobKills, playerKills, playTime, timeSinceDeath, 
        distanceTraveled, level,
        helmet, helmetPattern, helmetMaterial, helmetEnchants,
        chestplate, chestplatePattern, chestplateMaterial, chestplateEnchants,
        leggings, leggingsPattern, leggingsMaterial, leggingsEnchants,
        boot, bootPattern, bootMaterial, bootEnchants
    FROM statistics
    WHERE uuid = '$uuid'";

    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $blockMined = $row['blockMined'];
        $blockPlaced = $row['blockPlaced'];
        $damageAbsorbed = $row['damageAbsorbed'];
        $damageDealt = $row['damageDealt'];
        $damageTaken = $row['damageTaken'];
        $deaths = $row['deaths'];
        $firstJoined = $row['firstJoined'];
        $lastSeen = $row['lastSeen'];
        $mobKills = $row['mobKills'];
        $playerKills = $row['playerKills'];
        $playTime = $row['playTime'];
        $timeSinceDeath = $row['timeSinceDeath'];
        $distanceTraveled = $row['distanceTraveled'];
        $level = $row['level'];

        $armor = [
            [
                'slot' => 'Head',
                'image' => 'assets/armor_sets/' . $row['helmet'] . '.webp',
                'name' => ucwords($row['helmet']),
                'trim' => str_replace('minecraft:', '', $row['helmetPattern']),
                'trim_material' => str_replace('minecraft:', '', $row['helmetMaterial']),
                'enchants' => explode(', ', $row['helmetEnchants']),
            ],
            [
                'slot' => 'Body',
                'image' => 'assets/armor_sets/' . $row['chestplate'] . '.webp',
                'name' => ucwords($row['chestplate']),
                'trim' => str_replace('minecraft:', '', $row['chestplatePattern']),
                'trim_material' => str_replace('minecraft:', '', $row['chestplateMaterial']),
                'enchants' => explode(', ', $row['chestplateEnchants']),
            ],
            [
                'slot' => 'Legs',
                'image' => 'assets/armor_sets/' . $row['leggings'] . '.webp',
                'name' => ucwords($row['leggings']),
                'trim' => str_replace('minecraft:', '', $row['leggingsPattern']),
                'trim_material' => str_replace('minecraft:', '', $row['leggingsMaterial']),
                'enchants' => explode(', ', $row['leggingsEnchants']),
            ],
            [
                'slot' => 'Feet',
                'image' => 'assets/armor_sets/' . $row['boot'] . '.webp',
                'name' => ucwords($row['boot']),
                'trim' => str_replace('minecraft:', '', $row['bootPattern']),
                'trim_material' => str_replace('minecraft:', '', $row['bootMaterial']),
                'enchants' => explode(', ', $row['bootEnchants']),
            ],
        ];

            $armorAttributes = [
                'leather' => [
                    'helmet' => '+ 1 Armor',
                    'chestplate' => '+ 3 Armor',
                    'leggings' => '+ 2 Armor',
                    'boots' => '+ 1 Armor',
                ],
                'gold' => [
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
    } else {
        echo "No statistics found for UUID: $uuid";
    }

    $sql = "SELECT cause, x, y, z, timestamp FROM death_log WHERE uuid = '$uuid' ORDER BY id DESC LIMIT 5";
    $result = $conn->query($sql);

    $conn->close();
?>

<div class="flex flex-col space-y-5 md:space-y-10">
    <!-- Statistics and Skin -->
    <div class="grid gap-10 md:grid-cols-2">
        <div class="flex flex-col">
            <div class="flex items-center">
                <img src="https://cdn-icons-png.flaticon.com/128/5528/5528021.png" alt="Statistics Icon" class="w-5 h-5 mr-2" style="filter: invert(1);">
                <p class="text-2xl font-bold text-white">Statistics</p>
            </div>
            <table class="w-full mt-2 text-sm text-gray-200">
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Blocks Broken</td>
                <td class="py-1 text-right"><?= htmlspecialchars($blockMined); ?></td>
                </tr>
                        <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Blocks Placed</td>
                <td class="py-1 text-right"><?= htmlspecialchars($blockPlaced); ?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Damage Absorbed</td>
                <td class="py-1 text-right"><?= htmlspecialchars($damageAbsorbed); ?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Damage Dealt</td>
                <td class="py-1 text-right"><?= htmlspecialchars($damageDealt); ?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Damage Taken</td>
                <td class="py-1 text-right"><?= htmlspecialchars($damageTaken); ?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Deaths</td>
                <td class="py-1 text-right"><?= htmlspecialchars($deaths); ?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Distance Traveled</td>
                <td class="py-1 text-right"><?= htmlspecialchars($distanceTraveled); ?> Blocks</td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Level</td>
                <td class="py-1 text-right"><?= htmlspecialchars($level); ?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Mob Kills</td>
                <td class="py-1 text-right"><?= htmlspecialchars($mobKills); ?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Player Kills</td>
                <td class="py-1 text-right"><?= htmlspecialchars($playerKills); ?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Time Since Last Death</td>
                <td class="py-1 text-right"><?= htmlspecialchars(ticksToReadable($timeSinceDeath)); ?></td>
                </tr>
            </table>
        </div>
        <div class="flex flex-col">
        <div class="flex items-center">
            <img src="https://cdn-icons-png.flaticon.com/128/786/786346.png" alt="Death Logs Icon" class="w-5 h-5 mr-2" style="filter: invert(1);">
            <p class="text-2xl font-bold text-white">Armor</p>
        </div>
        <div class="flex gap-5 mt-4">
            <?php foreach ($armor as $i => $piece): ?>
                <div class="relative flex items-center justify-center p-2 rounded-sm group aspect-square bg-neutral-500 armor-piece" data-index="<?= $i ?>">
                    <img src="<?= htmlspecialchars($piece['image']) ?>" alt="<?= htmlspecialchars($piece['name']) ?>" class="w-12">
                    <div class="armor-tooltip absolute z-50 left-0 top-0 hidden flex-col min-w-[200px] bg-neutral-900/95 border border-neutral-600 rounded shadow-lg p-2 text-white font-minecraft pointer-events-none leading-none">                    
                        <p class="mb-1 font-bold text-white"><?= htmlspecialchars($piece['name']) ?></p>
                        <?php if (!empty($piece['trim'])): ?>
                            <p class="text-gray-400">Upgrade:</p>
                            <p class="ml-2 text-white"><?= htmlspecialchars($piece['trim']) ?> Armor Trim</p>
                            <p class="ml-2 text-white"><?= htmlspecialchars($piece['trim_material']) ?> Material</p>
                        <?php endif; ?>
                        <?php if (!empty($piece['enchants'])): ?>
                            <?php foreach ($piece['enchants'] as $enchant): ?>
                                <p class="text-gray-400"><?= htmlspecialchars($enchant) ?></p>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <p class="mt-5 text-gray-400">When on <?=$piece['slot']?>:</p>
                        <?php
                            $pieceName = strtolower($piece['name']); 
                            $parts = explode(' ', $pieceName);
                            $material = $parts[0]; // "iron"
                            $slot = strtolower($piece['slot']); 

                            $slotMap = [
                                'head' => 'helmet',
                                'body' => 'chestplate',
                                'legs' => 'leggings',
                                'feet' => 'boots',
                            ];
                            $slotKey = isset($slotMap[$slot]) ? $slotMap[$slot] : $slot;

                            if ($material === 'turtle') {
                                $attribute = isset($armorAttributes['turtle']['helmet']) ? $armorAttributes['turtle']['helmet'] : '';
                            } else {
                                $attribute = isset($armorAttributes[$material][$slotKey]) ? $armorAttributes[$material][$slotKey] : '';
                            }
                        ?>
                        <?php if ($attribute): ?>
                            <p class="text-[#504fed]"><?= $attribute ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div> 
    </div>
    <div class="flex flex-col">
        <div class="flex items-center">
            <img src="https://cdn-icons-png.flaticon.com/128/18650/18650881.png" alt="Death Logs Icon" class="w-5 h-5 mr-2" style="filter: invert(1);">
            <p class="text-2xl font-bold text-white">Death Logs</p>
        </div>
        <p class="mb-5 text-sm italic text-gray-300">Your last 5 deaths will be shown here.</p>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div onclick="window.location.href='bluemap.php?x=<?= htmlspecialchars($row['x']); ?>&z=<?= htmlspecialchars($row['z']); ?>&zoom=50'" class="px-4 py-3 mb-2 transition duration-200 bg-gray-800 rounded-lg cursor-pointer hover:bg-gray-700 hover:shadow-lg">
                    <p class="font-bold text-white"><?= htmlspecialchars($row['cause']); ?></p>
                    <p class="text-sm text-gray-400"><?= htmlspecialchars($row['x']) ?>, <?= htmlspecialchars($row['y']) ?>, <?= htmlspecialchars($row['z']) ?></p>
                    <p class="text-sm text-gray-400" 
                        data-time="<?= htmlspecialchars($row['timestamp']); ?>" 
                        data-format='{"year":"numeric","month":"long","day":"numeric" ,"hour":"2-digit","minute":"2-digit"}'>
                        Loading time...
                    </p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="flex items-center justify-center px-4 py-3 mb-2 bg-gray-800 rounded-lg">
                <p class="text-white">Congrats. You haven't died yet.</p>
            </div>
        <?php endif; ?>
    </div> 
</div>
<script src="script/timeFunctions.js"></script>
<script src="script/armor_set.js"></script>
<script>
    document.querySelectorAll('[data-time]').forEach(el => {
        const utcDateStr = el.getAttribute('data-time');
        let formatOptions = {};

        try {
            const formatData = el.getAttribute('data-format');
            if (formatData) {
                formatOptions = JSON.parse(formatData);
            }
        } catch(e) {
        }

        el.textContent = convertToLocalTime(utcDateStr, formatOptions);
    });
</script>