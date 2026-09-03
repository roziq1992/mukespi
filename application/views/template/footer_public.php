            <!-- ===== CONTENT END ===== -->
        </main>

        <!-- ===== FOOTER ===== -->
        <footer class="text-center text-muted py-3" style="font-size:0.8rem;border-top:1px solid var(--border-color);background:#fff;">
            <div class="container-fluid">
                &copy; <?= date('Y'); ?> <strong>RS Airlangga</strong> &mdash; Aplikasi Serah Terima Keperawatan v2.0
                <span class="mx-2">|</span> Created by <strong>Much Roziq, S.Kom</strong>
            </div>
        </footer>

    </div>
    <!-- ===== END CONTENT WRAPPER ===== -->

    <!-- ========================================== -->
    <!-- SCRIPTS                                   -->
    <!-- ========================================== -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ===== TOGGLE SIDEBAR (Mobile) =====
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggleSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            function toggleSidebar() {
                sidebar.classList.toggle('open');
                overlay.classList.toggle('active');
                document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
            }

            if (toggleBtn) {
                toggleBtn.addEventListener('click', toggleSidebar);
            }

            if (overlay) {
                overlay.addEventListener('click', toggleSidebar);
            }

            // Auto close sidebar saat resize ke desktop
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('active');
                    document.body.style.overflow = '';
                }
            });
        });

        // ===== AUTO DISMISS ALERT =====
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = bootstrap.Alert.getInstance(alert);
                    if (bsAlert) bsAlert.close();
                }, 5000);
            });
        });
    </script>

</body>
</html>