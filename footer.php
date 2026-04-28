</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- SweetAlert2 -->
<script src="plugins/sweetalert2/sweetalert2.all.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<script>
  $(function () {
    $(document).on('submit', '.js-delete-form', function (event) {
      event.preventDefault();

      var form = this;

      Swal.fire({
        title: $(form).data('title') || 'Delete item?',
        text: $(form).data('text') || 'This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        focusCancel: true
      }).then(function (result) {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });

    if (window.appFlash) {
      if (window.appFlash.variant === 'delete_success') {
        Swal.fire({
          toast: true,
          position: 'top',
          icon: 'success',
          title: window.appFlash.title || 'Deleted successfully',
          showConfirmButton: false,
          showCloseButton: true,
          timer: 3000,
          timerProgressBar: false,
          customClass: {
            popup: 'app-delete-toast',
            title: 'app-delete-toast-title',
            closeButton: 'app-delete-toast-close'
          }
        });
      } else {
        Swal.fire({
          icon: window.appFlash.icon || 'info',
          title: window.appFlash.title || '',
          text: window.appFlash.text || '',
          timer: 2500,
          timerProgressBar: true
        });
      }
    }

    function applyTableSearch(query) {
      var normalizedQuery = (query || '').toLowerCase().trim();

      $('.app-table tbody').each(function () {
        var $tbody = $(this);
        var $rows = $tbody.children('tr');

        if (!normalizedQuery) {
          $rows.show();
          $tbody.find('.js-search-empty-row').remove();
          return;
        }

        $tbody.find('.js-search-empty-row').remove();

        $rows.not('.record-day-row').each(function () {
          var $row = $(this);
          var isMatch = $row.text().toLowerCase().indexOf(normalizedQuery) !== -1;
          $row.toggle(isMatch);
        });

        $tbody.find('.record-day-row').each(function () {
          var $dayRow = $(this);
          var hasVisibleRows = false;
          var $nextRows = $dayRow.nextUntil('.record-day-row');

          $nextRows.each(function () {
            if ($(this).is(':visible')) {
              hasVisibleRows = true;
            }
          });

          $dayRow.toggle(hasVisibleRows);
        });

        var hasAnyVisibleRows = $tbody.children('tr:visible').not('.record-day-row').length > 0;

        if (!hasAnyVisibleRows) {
          var colspan = $tbody.closest('table').find('thead th').length || 1;
          $tbody.append('<tr class="js-search-empty-row"><td colspan="' + colspan + '" class="text-center text-muted">No matching results found.</td></tr>');
        }
      });
    }

    $(document).on('input', '.js-table-search', function () {
      applyTableSearch(this.value);
    });

    window.applyTableSearch = applyTableSearch;
  });
</script>
</body>
</html>
