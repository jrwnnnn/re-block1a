<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        header('Location: auth/login.php');
        exit();
    }
?>

<div class="space-y-10 md:pr-30">    
    <div class="text-white">
        <p class="mb-5 text-2xl font-bold">Language</p>
        <p class="mb-5">Select a language</p>
        <div class="grid gap-2 md:grid-cols-2">
            <button class="px-4 py-3 text-left text-white bg-gray-800 rounded-lg hover:bg-blue-600 hover:cursor-pointer">
                <img src="https://cdn-icons-png.flaticon.com/128/555/555526.png" alt="English" class="inline-block w-5 h-5 mr-2"> English, US
            </button>
            <button class="px-4 py-3 text-left text-white bg-gray-800 rounded-lg hover:bg-blue-600 hover:cursor-pointer">
                <img src="https://cdn-icons-png.flaticon.com/128/555/555526.png" alt="English,UK" class="inline-block w-5 h-5 mr-2"> English, UK
            </button>
            <button class="px-4 py-3 text-left text-white bg-gray-800 rounded-lg hover:bg-blue-600 hover:cursor-pointer">
                <img src="https://cdn-icons-png.flaticon.com/128/330/330557.png" alt="Español" class="inline-block w-5 h-5 mr-2"> Español
            </button>
        </div>    
    </div>
</div>