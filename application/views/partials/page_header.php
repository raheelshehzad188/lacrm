<div class="page-section border-bottom-2">
    <div class="container-fluid page__container">
        <div class="row align-items-center">
            <div class="col">
                <h1 class="h2 mb-0"><?php echo isset($page_title) ? $page_title : 'Page Title'; ?></h1>
                <?php if (isset($page_description)): ?>
                    <p class="text-muted mb-0"><?php echo $page_description; ?></p>
                <?php endif; ?>
            </div>
            <?php if (isset($page_actions)): ?>
                <div class="col-auto">
                    <?php echo $page_actions; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

