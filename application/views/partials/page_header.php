<div class="container-fluid page__heading-container">
    <div class="page__heading d-flex align-items-center">
        <div class="flex">
            <?php if (isset($breadcrumbs)): ?>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <?php foreach ($breadcrumbs as $index => $crumb): ?>
                            <?php if ($index === count($breadcrumbs) - 1): ?>
                                <li class="breadcrumb-item active" aria-current="page"><?php echo html_escape($crumb['label']); ?></li>
                            <?php else: ?>
                                <li class="breadcrumb-item">
                                    <a href="<?php echo isset($crumb['url']) ? base_url($crumb['url']) : '#'; ?>"><?php echo html_escape($crumb['label']); ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ol>
                </nav>
            <?php endif; ?>
            <h1 class="m-0"><?php echo isset($page_title) ? html_escape($page_title) : 'Page Title'; ?></h1>
            <?php if (isset($page_description)): ?>
                <p class="text-muted mb-0"><?php echo html_escape($page_description); ?></p>
            <?php endif; ?>
        </div>
        <?php if (isset($page_actions)): ?>
            <div class="ml-3">
                <?php echo $page_actions; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

