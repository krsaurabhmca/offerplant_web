<div class="breadcrumb-area">
    <div class="container">
        <ul class="breadcrumb">
            <li><a href="index.php">Home</a></li>
            <li><i class="fas fa-chevron-right"></i></li>
            <?php if(isset($breadcrumb_parent)): ?>
                <li><a href="<?php echo $breadcrumb_parent_url; ?>"><?php echo $breadcrumb_parent; ?></a></li>
                <li><i class="fas fa-chevron-right"></i></li>
            <?php endif; ?>
            <li class="current"><?php echo $breadcrumb_current; ?></li>
        </ul>
    </div>
</div>
