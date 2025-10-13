function copyToClipboard() {
    const text = "cs1a.sparked.network";
    document.getElementById("copy-button").innerHTML = "Copied!";
    navigator.clipboard.writeText(text);
  }

  fetch('https://api.mcsrvstat.us/2/cs1a.sparked.network')
    .then(response => response.json())
    .then(data => {
      if (data.online) {
        document.getElementById('player-count').innerText = data.players && data.players.online !== undefined ? data.players.online : "0";
      } else {
        document.getElementById('player-count').innerText = "Offline";
      }
    })
    .catch(() => {
      document.getElementById('player-count').innerText = "Offline";
    });

  const carousel = document.getElementById('carousel');
    const total = carousel.children.length;
    let index = 0;

    function updateSlide() {
      carousel.style.transform = `translateX(-${index * 100}%)`;
    }

    function nextSlide() {
      index = (index + 1) % total;
      updateSlide();
    }

    setInterval(nextSlide, 4000); 