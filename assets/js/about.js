let currentSlide = 0;
const totalSlides = 3;

function updateCarousel() {
  const track = document.getElementById("carousel-track");
  if (!track) return;
  track.style.transform = `translateY(-${currentSlide * 100}%)`;
  for (let i = 0; i < totalSlides; i++) {
    const dot = document.getElementById(`dot-${i}`);
    if (dot) {
      if (i === currentSlide) {
        dot.classList.add("bg-white", "h-4");
        dot.classList.remove("bg-white/30", "h-2");
      } else {
        dot.classList.remove("bg-white", "h-4");
        dot.classList.add("bg-white/30", "h-2");
      }
    }
  }
}

function nextSlide() {
  currentSlide = (currentSlide + 1) % totalSlides;
  updateCarousel();
}

function prevSlide() {
  currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
  updateCarousel();
}

document.addEventListener("DOMContentLoaded", () => {
  updateCarousel();
});
