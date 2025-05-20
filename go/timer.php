<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Countdown Timer</title>
  <link rel="stylesheet" href="../src/output.css">
</head>
<body>

<div class="flex flex-col items-center justify-center min-h-screen text-center text-white">
    <div class="absolute inset-0 -z-10 bg-[url('https://res.cloudinary.com/ddbybfkod/image/upload/v1732622187/blogs/Martin/fireworks-in-minecraft/7_rw5nnm.jpg')] bg-cover bg-center brightness-75 rounded-md"></div>
    <h1 class="md:text-2xl">Season 2 Starts In:</h1>
    <div id="countdown" class="text-3xl font-bold md:text-4xl">
        Loading...
    </div>
</div>

  <script>
    const targetDate = new Date("2025-05-24T09:00:00+08:00");

    function updateCountdown() {
      const now = new Date();
      const diff = targetDate - now;

      const days = Math.floor(diff / (1000 * 60 * 60 * 24));
      const hours = Math.floor((diff / (1000 * 60 * 60)) % 24);
      const minutes = Math.floor((diff / (1000 * 60)) % 60);
      const seconds = Math.floor((diff / 1000) % 60);

      document.getElementById("countdown").innerText =
        `${days}d ${hours}h ${minutes}m ${seconds}s`;
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);
  </script>

</body>
</html>
