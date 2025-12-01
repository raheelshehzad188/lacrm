<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo isset($title) ? $title : 'CRM Dashboard'; ?></title>

    <!-- Prevent the demo from appearing in search engines -->
    <meta name="robots" content="noindex">

    <!-- Simplebar -->
    <link type="text/css" href="<?php echo base_url('design/assets/vendor/simplebar.min.css'); ?>" rel="stylesheet">

    <!-- App CSS -->
    <link type="text/css" href="<?php echo base_url('design/assets/css/app.css'); ?>" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url('design/assets/css/app.rtl.css'); ?>" rel="stylesheet">

    <!-- Material Design Icons -->
    <link type="text/css" href="<?php echo base_url('design/assets/css/vendor-material-icons.css'); ?>" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url('design/assets/css/vendor-material-icons.rtl.css'); ?>" rel="stylesheet">

    <!-- Font Awesome FREE Icons -->
    <link type="text/css" href="<?php echo base_url('design/assets/css/vendor-fontawesome-free.css'); ?>" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url('design/assets/css/vendor-fontawesome-free.rtl.css'); ?>" rel="stylesheet">

    <!-- Flatpickr -->
    <link type="text/css" href="<?php echo base_url('design/assets/css/vendor-flatpickr.css'); ?>" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url('design/assets/css/vendor-flatpickr.rtl.css'); ?>" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url('design/assets/css/vendor-flatpickr-airbnb.css'); ?>" rel="stylesheet">
    <link type="text/css" href="<?php echo base_url('design/assets/css/vendor-flatpickr-airbnb.rtl.css'); ?>" rel="stylesheet">

    <!-- Vector Maps -->
    <link type="text/css" href="<?php echo base_url('design/assets/vendor/jqvmap/jqvmap.min.css'); ?>" rel="stylesheet">

    <?php if (isset($extra_css)): ?>
        <?php foreach ($extra_css as $css): ?>
            <link type="text/css" href="<?php echo base_url($css); ?>" rel="stylesheet">
        <?php endforeach; ?>
    <?php endif; ?>
</head>

<body class="<?php echo isset($body_class) ? $body_class : 'layout-default'; ?>">

    <?php if (isset($show_preloader) && $show_preloader): ?>
        <div class="preloader"></div>
    <?php endif; ?>

    <!-- Header Layout -->
    <div class="mdk-header-layout js-mdk-header-layout">

        <?php if (isset($header)): ?>
            <?php $this->load->view('partials/header', $header); ?>
        <?php endif; ?>

        <!-- Header Layout Content -->
        <div class="mdk-header-layout__content">
            <div class="mdk-drawer-layout js-mdk-drawer-layout" data-push data-responsive-width="992px">
                <div class="mdk-drawer-layout__content page">
                    
                    <?php if (isset($page_header)): ?>
                        <?php 
                        // Set default breadcrumbs if not provided
                        if (!isset($page_header['breadcrumbs'])) {
                            $page_header['breadcrumbs'] = array(
                                array('label' => 'Home', 'url' => 'dashboard'),
                                array('label' => isset($page_header['page_title']) ? $page_header['page_title'] : 'Page')
                            );
                        }
                        ?>
                        <?php $this->load->view('partials/page_header', $page_header); ?>
                    <?php endif; ?>

                    <div class="container-fluid page__container">
                        <?php 
                        // Display flash alerts at the top of content
                        $this->load->helper('flash');
                        $flash_alerts = display_flash_alerts();
                        if (!empty($flash_alerts)) {
                            echo '<div style="margin-bottom: 1.5rem;">' . $flash_alerts . '</div>';
                        }
                        ?>
                        <?php echo $content; ?>
                    </div>

                </div>
                <!-- // END drawer-layout__content -->

                <!-- drawer -->
                <?php if (isset($sidebar)): ?>
                    <?php $this->load->view('partials/sidebar', $sidebar); ?>
                <?php endif; ?>
            </div>
            <!-- // END drawer-layout -->
        </div>
        <!-- // END header-layout__content -->

    </div>
    <!-- // END header-layout -->

    <?php if (isset($footer)): ?>
        <?php $this->load->view('partials/footer', $footer); ?>
    <?php endif; ?>

    <!-- jQuery -->
    <script src="<?php echo base_url('design/assets/vendor/jquery.min.js'); ?>"></script>

    <!-- Bootstrap -->
    <script src="<?php echo base_url('design/assets/vendor/popper.min.js'); ?>"></script>
    <script src="<?php echo base_url('design/assets/vendor/bootstrap.min.js'); ?>"></script>

    <!-- Simplebar -->
    <script src="<?php echo base_url('design/assets/vendor/simplebar.min.js'); ?>"></script>

    <!-- DOM Factory -->
    <script src="<?php echo base_url('design/assets/vendor/dom-factory.js'); ?>"></script>

    <!-- MDK -->
    <script src="<?php echo base_url('design/assets/vendor/material-design-kit.js'); ?>"></script>

    <!-- App -->
    <script src="<?php echo base_url('design/assets/js/toggle-check-all.js'); ?>"></script>
    <script src="<?php echo base_url('design/assets/js/check-selected-row.js'); ?>"></script>
    <script src="<?php echo base_url('design/assets/js/dropdown.js'); ?>"></script>
    <script src="<?php echo base_url('design/assets/js/sidebar-mini.js'); ?>"></script>
    <script src="<?php echo base_url('design/assets/js/app.js'); ?>"></script>

    <!-- App Settings (safe to remove) -->
    <script src="<?php echo base_url('design/assets/js/app-settings.js'); ?>"></script>

    <!-- Flatpickr -->
    <script src="<?php echo base_url('design/assets/vendor/flatpickr/flatpickr.min.js'); ?>"></script>
    <script src="<?php echo base_url('design/assets/js/flatpickr.js'); ?>"></script>

    <!-- Global Settings -->
    <script src="<?php echo base_url('design/assets/js/settings.js'); ?>"></script>

    <!-- Chart.js -->
    <script src="<?php echo base_url('design/assets/vendor/Chart.min.js'); ?>"></script>

    <!-- App Charts JS -->
    <script src="<?php echo base_url('design/assets/js/charts.js'); ?>"></script>

    <!-- Vector Maps -->
    <script src="<?php echo base_url('design/assets/vendor/jqvmap/jquery.vmap.min.js'); ?>"></script>
    <script src="<?php echo base_url('design/assets/vendor/jqvmap/maps/jquery.vmap.world.js'); ?>"></script>
    <script src="<?php echo base_url('design/assets/js/vector-maps.js'); ?>"></script>

    <?php if (isset($extra_js)): ?>
        <?php foreach ($extra_js as $js): ?>
            <script src="<?php echo base_url($js); ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>

    <!-- Initialize sidebar tabs -->
    <script>
        (function() {
            function initSidebarTabs() {
                // Get all tab links in mini sidebar
                var $tabLinks = $('#sidebar-mini-tabs [data-toggle="tab"]');
                
                if ($tabLinks.length === 0) {
                    return;
                }
                
                // Handle tab clicks
                $tabLinks.on('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    var $this = $(this);
                    var target = $this.attr('href');
                    
                    if (!target) {
                        return;
                    }
                    
                    // Remove active from all mini sidebar items
                    $('#sidebar-mini-tabs .sidebar-menu-item').removeClass('active');
                    $this.closest('.sidebar-menu-item').addClass('active');
                    
                    // Update aria-selected
                    $('#sidebar-mini-tabs [role="tab"]').attr('aria-selected', 'false');
                    $this.attr('aria-selected', 'true');
                    
                    // Hide all tab panes
                    $('#sidebar-tab-content .tab-pane').removeClass('active show');
                    
                    // Show target tab pane
                    var $targetPane = $(target);
                    if ($targetPane.length) {
                        $targetPane.addClass('active show');
                    }
                });
                
                // Initialize tooltips
                if (typeof $.fn.tooltip !== 'undefined') {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            }
            
            // Initialize when document is ready
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', initSidebarTabs);
            } else {
                initSidebarTabs();
            }
            
            // Also try with jQuery if available
            if (typeof jQuery !== 'undefined') {
                jQuery(document).ready(initSidebarTabs);
            }
        })();
    </script>

</body>
</html>

