/**
 * LOGIKA CAROUSEL HALAMAN ABOUT - PARASIKA
 * Script ini mengelola navigasi vertikal pada carousel sejarah/perjalanan Parasika.
 */

let currentSlide = 0;   // Index slide yang sedang ditampilkan
const totalSlides = 3; // Jumlah total item slide dalam carousel

/**
 * FUNGSI: updateCarousel
 * Memperbarui posisi track carousel dan indikator titik (dots) navigasi.
 */
function updateCarousel() {
  const track = document.getElementById("carousel-track");
  if (!track) return;

  // Menggeser track secara vertikal menggunakan transform translateY
  track.style.transform = `translateY(-${currentSlide * 100}%)`;

  // Memperbarui visual indikator titik (dots) sesuai index aktif
  for (let i = 0; i < totalSlides; i++) {
    const dot = document.getElementById(`dot-${i}`);
    if (dot) {
      if (i === currentSlide) {
        // Gaya untuk indikator yang aktif
        dot.classList.add("bg-white", "h-4");
        dot.classList.remove("bg-white/30", "h-2");
      } else {
        // Gaya untuk indikator yang tidak aktif
        dot.classList.remove("bg-white", "h-4");
        dot.classList.add("bg-white/30", "h-2");
      }
    }
  }
}

/**
 * Navigasi ke slide berikutnya (Looped)
 */
function nextSlide() {
  currentSlide = (currentSlide + 1) % totalSlides;
  updateCarousel();
}

/**
 * Navigasi ke slide sebelumnya (Looped)
 */
function prevSlide() {
  currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
  updateCarousel();
}

/**
 * Inisialisasi carousel saat DOM selesai dimuat
 */
document.addEventListener("DOMContentLoaded", () => {
  updateCarousel();
});
