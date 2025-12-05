<?php
include_once __DIR__ . '/../../../config/config.php';
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<style>
    /* Sidebar Animation */
    #mobile-sidebar {
        clip-path: circle(0px at calc(100% - 45px) 45px);
        transition: clip-path 0.6s cubic-bezier(0.25, 1, 0.5, 1), visibility 0.6s;
        visibility: hidden;
    }
    
    #mobile-sidebar.sidebar-open {
        clip-path: circle(150vh at calc(100% - 45px) 45px);
        visibility: visible;
    }

    /* Hamburger Animation */
    .hamburger-line {
        transition: all 0.3s ease-in-out;
        transform-origin: center;
    }
    
    .hamburger-active .line-1 {
        transform: translateY(10px) rotate(45deg);
    }
    .hamburger-active .line-2 {
        opacity: 0;
        transform: translateX(-10px);
    }
    .hamburger-active .line-3 {
        transform: translateY(-10px) rotate(-45deg);
    }
</style>

<nav class="bg-[#1A212B] p-4 px-5 md:px-30 flex items-center justify-between relative z-50">
    <!-- Logo (Desktop) -->
    <img src="<?= $baseUrl ?>app/public/assets/images/cs1a.png" alt="logo" class="hidden md:block w-20 hover:cursor-pointer md:mr-20" onclick="window.location.replace('<?= $baseUrl ?>index.php')">

    <!-- Mobile Menu Button (Right) -->
    <button id="mobile-menu-btn" class="md:hidden text-white focus:outline-none ml-auto z-50 relative w-8 h-8 flex flex-col justify-center gap-1.5">
        <span class="hamburger-line line-1 block w-8 h-1 bg-white rounded-full"></span>
        <span class="hamburger-line line-2 block w-8 h-1 bg-white rounded-full"></span>
        <span class="hamburger-line line-3 block w-8 h-1 bg-white rounded-full"></span>
    </button>

    <!-- Desktop Menu -->
    <div id="nav-links" class="hidden md:flex items-center justify-between w-full max-w-lg space-x-10">
        <a href="<?= $baseUrl ?>index.php" class="text-white hover:text-gray-300 transition-colors">Home</a>
        <a href="<?= $baseUrl ?>app/news.php" class="text-white hover:text-gray-300 transition-colors">News</a>
        <a href="<?= $baseUrl ?>app/rules.php" class="text-white hover:text-gray-300 transition-colors">Rules</a>
        <a href="<?= $baseUrl ?>app/bluemap.php" class="text-white hover:text-gray-300 transition-colors">BlueMap</a>
        <div class="flex items-center gap-2 bg-white px-2 py-1 rounded-md cursor-pointer hover:bg-gray-300 transition-colors" onclick="window.location.replace('<?= $baseUrl ?>app/<?= isset($_SESSION['uuid']) ? 'profile.php?player=' . sanitize($_SESSION['uuid']) : 'login' ?>.php')">
            <img src="https://mc-heads.net/avatar/<?= isset($_SESSION['username']) ? sanitize($_SESSION['username']) : '0385'; ?>" alt="avatar" class="w-5 rounded aspect-square">
            <p class="truncate text-black"><?= isset($_SESSION['username']) ? sanitize($_SESSION['username']) : 'Login' ?></p>
        </div>
    </div>

    <!-- Mobile Sidebar -->
    <div id="mobile-sidebar" class="md:hidden absolute top-0 left-0 w-full h-screen bg-[#1A212B] z-40 flex flex-col p-6 pt-28 gap-6 overflow-y-auto">
        
        <div class="flex items-center justify-center pb-6 border-b border-gray-700/50 nav-item">
            <img src="<?= $baseUrl ?>app/public/assets/images/cs1a.png" alt="logo" class="w-24 hover:cursor-pointer hover:scale-105 transition-transform duration-300" onclick="window.location.replace('<?= $baseUrl ?>index.php')">
        </div>
        
        <div class="flex flex-col gap-4">
            <?php
            $links = [
                ['url' => $baseUrl . 'index.php', 'name' => 'Home', 'file' => 'index.php'],
                ['url' => $baseUrl . 'app/news.php', 'name' => 'News', 'file' => 'news.php'],
                ['url' => $baseUrl . 'app/rules.php', 'name' => 'Rules', 'file' => 'rules.php'],
                ['url' => $baseUrl . 'app/bluemap.php', 'name' => 'BlueMap', 'file' => 'bluemap.php'],
            ];
            foreach ($links as $link):
                $isActive = ($currentPage === $link['file']);
            ?>
            <a href="<?= $link['url'] ?>" class="nav-item text-white text-xl font-medium hover:text-gray-300 transition-all duration-300 flex items-center gap-3 group">
                <span class="w-1.5 h-5 rounded-sm transition-colors duration-300 <?= $isActive ? 'bg-yellow-500 shadow-[0_0_8px_rgba(234,179,8,0.6)]' : 'bg-transparent' ?>"></span>
                <?= $link['name'] ?>
            </a>
            <?php endforeach; ?>
        </div>

        <div class="mt-auto pt-6 border-t border-gray-700/50 nav-item">
            <div class="flex items-center gap-3 bg-white/10 p-3 rounded-xl cursor-pointer hover:bg-white/20 transition-all duration-300 group" onclick="window.location.replace('<?= $baseUrl ?>app/<?= isset($_SESSION['uuid']) ? 'profile.php?player=' . sanitize($_SESSION['uuid']) : 'login' ?>.php')">
                <img src="https://mc-heads.net/avatar/<?= isset($_SESSION['username']) ? sanitize($_SESSION['username']) : '0385'; ?>" alt="avatar" class="w-10 rounded-lg shadow-lg group-hover:scale-110 transition-transform">
                <div class="flex flex-col">
                    <span class="text-gray-400 text-xs uppercase tracking-wider">Account</span>
                    <p class="truncate text-white font-bold text-lg"><?= isset($_SESSION['username']) ? sanitize($_SESSION['username']) : 'Login' ?></p>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    const menuBtn = document.getElementById('mobile-menu-btn');
    const sidebar = document.getElementById('mobile-sidebar');

    function toggleSidebar() {
        const isOpen = sidebar.classList.contains('sidebar-open');
        
        if (!isOpen) {
            // Open
            menuBtn.classList.add('hamburger-active');
            sidebar.classList.add('sidebar-open');
            document.body.style.overflow = 'hidden';
        } else {
            // Close
            menuBtn.classList.remove('hamburger-active');
            sidebar.classList.remove('sidebar-open');
            document.body.style.overflow = '';
        }
    }

    menuBtn.addEventListener('click', toggleSidebar);
</script>