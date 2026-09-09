/**
 * main.js
 * -----------------------------------------------------------------------
 * Shared front-end behaviour for every GeoVendor page:
 *  - Mobile sidebar toggle
 *  - Simple confirm-before-delete helper used on management tables
 * -----------------------------------------------------------------------
 */
document.addEventListener('DOMContentLoaded', function () {
  var toggleBtn = document.getElementById('gvSidebarToggle');
  var sidebar = document.getElementById('gvSidebar');

  // Show the hamburger toggle only on small screens
  function syncToggleVisibility() {
    if (!toggleBtn) return;
    toggleBtn.style.display = window.innerWidth <= 900 ? 'inline-flex' : 'none';
  }
  syncToggleVisibility();
  window.addEventListener('resize', syncToggleVisibility);

  if (toggleBtn && sidebar) {
    toggleBtn.addEventListener('click', function () {
      sidebar.classList.toggle('open');
    });
  }
});

/**
 * gvConfirmDelete
 * Simple confirmation wrapper used on "Remove" / "Deactivate" buttons
 * throughout the management tables. Since this is a static prototype,
 * it only prevents the (dummy) form submission if the user cancels.
 */
function gvConfirmDelete(message) {
  return window.confirm(message || 'Are you sure you want to proceed? This action cannot be undone.');
}
