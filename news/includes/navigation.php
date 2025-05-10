<?php
$current_page = basename($_SERVER['PHP_SELF']);

$grid6 = "Login";
$grid6_link = "../auth/login.php";
if (isset($_SESSION['user_id'])) {
    $grid6 = "Profile";
    $grid6_link = "../profile.php";
}

function navLink($href, $label, $current_page, $activePages = []) {
    $isActive = in_array($current_page, $activePages);
    $baseClasses = "nav-tab block py-2 md:inline text-white relative group";
    $underline = "md:after:absolute md:after:left-0 md:after:-bottom-1 md:after:w-0 md:after:h-0.5 md:after:bg-white md:after:transition-all md:after:duration-300 md:group-hover:after:w-full";
    $activeUnderline = $isActive ? "md:after:w-full" : "";

    return "<a href=\"$href\" class=\"$baseClasses $underline $activeUnderline\">$label</a>";
}
?>

<nav class="bg-[#1A212B] p-4 px-5 md:px-30 flex items-center justify-between">
    <img src="../assets/cs1a.png" alt="logo" class="w-20 hover:cursor-pointer" onclick="window.location.replace('../index.php')">

    <button id="menu-toggle" class="md:hidden text-white focus:outline-none">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
    </button>

    <div id="nav-links" class="hidden md:grid md:grid-cols-6 absolute md:static top-19.5 left-0 w-full md:w-auto bg-[#1A212B] text-center md:flex-row md:space-x-4 transition-all duration-300 ease-in-out z-10">
        <?php
        echo navLink("../index.php", "Home", $current_page, ["index.php"]);
        echo navLink("../news.php", "News", $current_page, ["news.php", "article.php", "editor.php"]);
        echo navLink("../rules.php", "Rules", $current_page, ["rules.php"]);
        echo navLink("../bluemap.php", "BlueMap", $current_page, ["bluemap.php"]);
        echo navLink("../help-and-support.php", "Help and Support", $current_page, ["help-and-support.php", "faq.php", "contact.php"]);
        echo navLink($grid6_link, $grid6, $current_page, [$grid6_link]);
        ?>
    </div>
</nav>

<script>
const toggle = document.getElementById('menu-toggle');
const links = document.getElementById('nav-links');

toggle.addEventListener('click', () => {
    links.classList.toggle('hidden');
    links.classList.toggle('flex');
    links.classList.toggle('flex-col');
    links.classList.toggle('animate-slide');
});
</script>
