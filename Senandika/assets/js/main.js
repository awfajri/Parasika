/**
 * MAIN DASHBOARD LOGIC - SENANDIKA
 * Script ini menangani interaksi UI di dalam Dashboard seperti Animasi Bar, 
 * Sidebar Mobile, dan Dialog Konfirmasi menggunakan SweetAlert2.
 */

document.addEventListener("DOMContentLoaded", function () {
  
  /**
   * ANIMASI PROGRESS BAR
   * Memberikan efek transisi lebar (width) pada bar statistik saat halaman dimuat.
   */
  document.querySelectorAll(".bar-fill").forEach(function (bar) {
    const target = bar.getAttribute("data-width") || "0%";
    setTimeout(function () {
      bar.style.width = target;
    }, 120);
  });

  /**
   * SIDEBAR TOGGLE (MOBILE)
   * Mengatur buka/tutup menu sidebar pada resolusi layar kecil (Tablet/HP).
   */
  const toggleBtn = document.getElementById("sidebarToggle");
  const sidebar = document.getElementById("sidebar");
  if (toggleBtn && sidebar) {
    // Event Klik pada icon hamburger/toggle
    toggleBtn.addEventListener("click", function () {
      sidebar.classList.toggle("open");
    });

    // Menutup sidebar secara otomatis jika klik dilakukan di luar area sidebar (overlay effect)
    document.addEventListener("click", function (e) {
      if (window.innerWidth < 769 && sidebar.classList.contains("open")) {
        if (!sidebar.contains(e.target) && e.target !== toggleBtn) {
          sidebar.classList.remove("open");
        }
      }
    });
  }

  /**
   * KONFIRMASI HAPUS DATA (SWEETALERT2)
   * Menampilkan dialog konfirmasi saat user menekan tombol hapus dengan class '.btn-del'.
   */
  document.querySelectorAll(".btn-del").forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.preventDefault(); // Mencegah navigasi langsung
      const url = this.getAttribute("href");
      
      Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#6B0D2A" /* Warna Maroon Parasika */,
        cancelButtonColor: "#6B7280",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
      }).then((result) => {
        if (result.isConfirmed) {
          // Jika dikonfirmasi, arahkan window ke URL penghapusan
          window.location.href = url;
        }
      });
    });
  });

  /**
   * KONFIRMASI TOLAK REGISTRASI (SWEETALERT2)
   * Digunakan pada modul Kelola Anggota untuk menghapus data registrasi yang ditolak.
   */
  document.querySelectorAll(".btn-tolak").forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const form = this.closest("form");
      
      Swal.fire({
        title: "Tolak Registrasi?",
        text: "Data registrasi ini akan dihapus secara permanen.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DC2626", /* Warna Merah */
        cancelButtonColor: "#6B7280",
        confirmButtonText: "Ya, tolak!",
        cancelButtonText: "Batal",
      }).then((result) => {
        if (result.isConfirmed) {
          // Menambahkan flag 'reject' agar backend tahu aksi yang diinginkan
          const hiddenInput = document.createElement("input");
          hiddenInput.type = "hidden";
          hiddenInput.name = "action";
          hiddenInput.value = "reject";
          form.appendChild(hiddenInput);
          form.submit();
        }
      });
    });
  });
});
