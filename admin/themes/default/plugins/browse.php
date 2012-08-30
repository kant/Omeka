<?php 
$pageTitle = __('All Plugins') . ' ' . __('(%s total)', count($plugins));
head(array('title'=>$pageTitle, 'content_class' => 'vertical-nav', 'bodyclass'=>'plugins browse')); 
?>

    <?php include('plugin-tabs.php'); ?>

    <?php include('plugin-table.php'); ?>
    
    </div>

<?php foot(); ?>
