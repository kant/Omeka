<?php
$pageTitle = __('Configure the &#8220;%s&#8221; Theme', html_escape($theme->title));
head(array('title'=>$pageTitle, 'bodyclass'=>'themes theme-configuration')); ?>
<?php echo js('tiny_mce/tiny_mce'); ?>
<?php echo js('themes'); ?>
               <?php echo flash(); ?>

            <p><?php echo __('Configurations apply to this theme only.'); ?></p>
            <?php echo $configForm; ?>

<?php foot(); ?>
