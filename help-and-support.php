<?php
    require 'includes/security-headers.php';
    require_once 'includes/session-init.php';
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
    <link href="src/output.css" rel="stylesheet">
    <title>Block1A - Help and Support</title>
</head>
    <body>
    <?php require 'includes/navigation.php'; ?>
        <section class="flex flex-col items-center justify-center text-white bg-cover bg-center bg-no-repeat min-h-[40vh] px-5" style="background-image: url('assets/help-and-support-hero.webp')">
            <p class="text-4xl font-bold text-center text-yellow-500 md:text-6xl">Welcome to the Help and Support!</p>
            <p class="mt-5 text-center md:text-lg">Need a hand? You're at the right place!</p>
        </section>
        <section class="bg-[#2D3748] grid md:grid-cols-2 md:px-30 px-5 pt-15 pb-5 gap-10">
            <div class="has-card">
                <img src="assets/faq.png" alt="faq">
                <div class="flex flex-col space-y-2">
                    <p class="mb-5 text-2xl font-bold">Frequently Asked Questions</p>
                    <div class="flex flex-col mb-5 space-y-2">
                        <a href="faq.php?topic=getting-started#how-do-i-join-the-server%3F" class="glob-link">How do I join the server?</a>
                        <a href="faq.php?topic=getting-started#how-do-i-join-the-server-on-bedrock-edition%3F" class="glob-link">How do I join the server on Bedrock Edition?</a>
                        <a href="#" class="glob-link">*Can I play on a bedrock client?</a>
                        <a href="faq.php?topic=getting-started#what-version-of-minecraft-do-i-need%3F" class="glob-link">What version of Minecraft do I need?</a>
                        <a href="#" class="glob-link">*Do I need a Minecraft License to join?</a>
                    </div>
                </div>
            </div>
            <div class="has-card">
                <img src="assets/technical.png" alt="technical">
                <div class="flex flex-col space-y-2">
                    <p class="mb-5 text-2xl font-bold">Technical</p>
                    <div class="flex flex-col mb-5 space-y-2">
                        <a href="#" class="glob-link">Connection lost</a>
                        <a href="#" class="glob-link">Unable to connect to world</a>
                        <a href="#" class="glob-link">VPN or Proxy Detected</a>
                        <a href="#" class="glob-link">We couldn't validate your login</a>
                        <a href="#" class="glob-link">Maintenance Mode</a>
                        <a href="#" class="glob-link">Outdated client</a>
                        <a href="#" class="glob-link">Invalid IP Address</a>
                    </div>
                </div>
            </div>
            <div class="has-card">
                <img src="assets/fairplay.png" alt="fairplay">
                <div class="flex flex-col space-y-2">
                    <p class="mb-5 text-2xl font-bold">Fairplay</p>
                    <div class="flex flex-col mb-5 space-y-2">
                        <a href="faq.php?topic=fairplay#what-are-the-rules%3F" class="glob-link">What are the rules?</a>
                        <a href="faq.php?topic=fairplay#i%27ve-been-banned%2Fmuted-—-what-now%3F" class="glob-link">I've been banned/muted/jailed</a>
                        <a href="faq.php?topic=fairplay#ban-appeals" class="glob-link">Ban Appeals</a>
                        <a href="faq.php?topic=fairplay#someone-griefed-my-base%21" class="glob-link">Someone griefed my base!</a>
                        <a href="#" class="glob-link">*How do I report rulebreakers?</a>
                    </div>
                </div>
            </div>
            <div class="has-card">
                <img src="assets/gameplay.png" alt="gameplay">
                <div class="flex flex-col space-y-2">
                    <p class="mb-5 text-2xl font-bold">Gameplay</p>
                    <div class="flex flex-col mb-5 space-y-2">
                        <a href="#" class="glob-link">How do I use /tpa?</a>
                        <a href="#" class="glob-link">How do I use /skin?</a>
                        <a href="#" class="glob-link">What is the shattered wilds?</a>
                        <a href="#" class="glob-link">Why can't I sleep?</a>
                        <a href="#" class="glob-link"></a>
                    </div>
                </div>
            </div>
        </section>
        <section class="bg-[#2D3748] md:px-30 px-5 pb-20 pt-5">
            <div class="flex md:flex-row flex-col justify-between gap-5 items-center bg-[#1A212B] py-5 px-10 rounded-md shadow-lg text-white">
                <div>
                    <p class="text-2xl font-bold">Still need help?</p>
                    <p class="md:text-lg">Can't find the answer to your question? Contact our support.</p>
                </div>
                <button onclick="window.location.href='contact.php';" class="bg-yellow-500 text-[#2D3748] md:text-lg font-bold py-2 px-5 rounded-md hover:bg-[#3a4d60] hover:text-white hover:cursor-pointer transition duration-300 ease-in-out">Contact us</button>
            </div>
            <img src="assets/buzzy-bees.webp" alt="faq" class="w-full mt-20 max-h-[40vh] object-cover object-center">
        </section>
        <?php require 'includes/footer.php'; ?>
        
    </body>
</html>