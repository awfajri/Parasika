document.addEventListener('DOMContentLoaded', function () {

  document.querySelectorAll('.bar-fill').forEach(function (bar) {
    const target = bar.getAttribute('data-width') || '0%';
    setTimeout(function () {
      bar.style.width = target;
    }, 120);
  });

  const toggleBtn = document.getElementById('sidebarToggle');
  const sidebar   = document.getElementById('sidebar');
  if (toggleBtn && sidebar) {
    toggleBtn.addEventListener('click', function () {
      sidebar.classList.toggle('open');
    });

    document.addEventListener('click', function (e) {
      if (window.innerWidth < 769 && sidebar.classList.contains('open')) {
        if (!sidebar.contains(e.target) && e.target !== toggleBtn) {
          sidebar.classList.remove('open');
        }
      }
    });
  }

  document.querySelectorAll('.btn-del').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      if (!confirm('Yakin ingin menghapus dokumen ini?')) {
        e.preventDefault();
      }
    });
  });

});
