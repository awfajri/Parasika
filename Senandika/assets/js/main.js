document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".bar-fill").forEach(function (bar) {
    const target = bar.getAttribute("data-width") || "0%";
    setTimeout(function () {
      bar.style.width = target;
    }, 120);
  });

  const toggleBtn = document.getElementById("sidebarToggle");
  const sidebar = document.getElementById("sidebar");
  if (toggleBtn && sidebar) {
    toggleBtn.addEventListener("click", function () {
      sidebar.classList.toggle("open");
    });

    document.addEventListener("click", function (e) {
      if (window.innerWidth < 769 && sidebar.classList.contains("open")) {
        if (!sidebar.contains(e.target) && e.target !== toggleBtn) {
          sidebar.classList.remove("open");
        }
      }
    });
  }

  // SweetAlert untuk Konfirmasi Hapus (Link / Tombol Biasa)
  document.querySelectorAll(".btn-del").forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const url = this.getAttribute("href");
      Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Data yang dihapus tidak dapat dikembalikan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#6B0D2A" /* Warna Maroon */,
        cancelButtonColor: "#6B7280",
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
      }).then((result) => {
        if (result.isConfirmed) {
          window.location.href = url;
        }
      });
    });
  });

  // SweetAlert untuk Tolak Registrasi (Form Submit)
  document.querySelectorAll(".btn-tolak").forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      const form = this.closest("form");
      Swal.fire({
        title: "Tolak Registrasi?",
        text: "Data registrasi ini akan dihapus secara permanen.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DC2626",
        cancelButtonColor: "#6B7280",
        confirmButtonText: "Ya, tolak!",
        cancelButtonText: "Batal",
      }).then((result) => {
        if (result.isConfirmed) {
          // Tambahkan input tersembunyi agar form tahu tujuannya adalah menolak
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
