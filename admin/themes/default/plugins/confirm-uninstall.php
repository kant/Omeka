<?php 
$pageTitle = __('Confirm %s Uninstall', $plugin->getDirectoryName());
head(array('title'=>$pageTitle, 'bodyclass'=>'plugins')); ?>
        
    <div class="six columns alpha">
    <?php echo flash(); ?>
    <p><strong><?php echo __('Are you sure you want to uninstall this plugin?'); ?></strong></p>
    <?php if ($message): ?>
    <?php echo __($message); ?>
    <?php endif; ?>
    <form action="<?php echo html_escape(uri('plugins/uninstall')); ?>" method="post">
        <p><input type="checkbox" name="confirm" /> <?php echo __('Yes, I want to uninstall this plugin.'); ?></p>
        <input type="hidden" name="name" value="<?php echo html_escape($plugin->getDirectoryName()); ?>" />

        <p id="uninstall-confirm"><input type="submit" class="uninstall small red button" name="uninstall-confirm" class="foo" value="<?php echo __('Uninstall'); ?>" /> <span><?php echo __('or'); ?> <?php echo link_to('plugins', 'browse', __('Cancel')); ?></span></p>
    </form>

<?php foot(); ?>