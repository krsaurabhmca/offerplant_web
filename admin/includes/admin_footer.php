    </div> <!-- End Main Content -->
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
    $(document).ready(function() {
        $('.datatable').DataTable({
            "pageLength": 10,
            "ordering": true,
            "info": true,
            "searching": true,
            "language": {
                "search": "_INPUT_",
                "searchPlaceholder": "Search records...",
                "lengthMenu": "Show _MENU_ entries"
            }
        });
    });
    </script>
</body>
</html>

