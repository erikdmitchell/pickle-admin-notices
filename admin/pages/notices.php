<h2 class="wp-heading-inline">Notices</h2>

<a href="<?php echo admin_url('options-general.php?page=pickle-admin-notices&tab=notices&action=edit'); ?>" class="page-title-action">Add New</a>

<form id="pickle-admin-notices" method="post">

<table class="wp-list-table widefat fixed striped pickle-admin-notices">
	<thead>
		<tr>
			<td id="cb" class="manage-column column-cb check-column">
				<label class="screen-reader-text" for="cb-select-all-1">Select All</label>
				<input id="cb-select-all-1" type="checkbox">
			</td>
			
			<th scope="col" id="name" class="manage-column column-name column-primary">
				<span>Name</span>
			</th>
			
			<th scope="col" id="actions" class="manage-column column-actions">
				<span></span>
			</th>
		</tr>
	</thead>

	<tbody id="the-list">
    	<?php if (pickle_admin_notices()->admin->has_notices()) : ?>
    		<?php foreach (pickle_admin_notices()->admin->get_notices() as $notice) : ?>
    			<tr id="taxonomy-<?php echo $notice['slug']; ?>" class="notice-<?php echo $notice['slug']; ?> notice hentry">
    				<th scope="row" class="check-column">
    					<label class="screen-reader-text" for="cb-select-<?php echo $notice['slug']; ?>">Select <?php echo ucwords($notice['label']); ?></label>
    					<input id="cb-select-<?php echo $notice['slug']; ?>" type="checkbox" name="pickle_admin_notices[]" value="<?php echo $notice['slug']; ?>">
    				</th>
    				
    				<td class="name column-name column-primary" data-colname="Name">
    					<strong><a class="row-name" href="<?php echo admin_url('options-general.php?page=pickle-admin-notices&tab=notices&action=edit&slug='.$notice['slug']); ?>" aria-label="“<?php echo $notice['name']; ?>” (Edit)"><?php echo $notice['name']; ?></a></strong>
    				</td>
    				
                    <td class="actions column-actions" data-colname="Actions">
    					<strong><a class="delete" href="<?php echo pickle_admin_notices()->admin->delete_url($notice['slug']); ?>" aria-label="delete">Delete</a></strong>
    				</td>				
    			</tr>
    		<?php endforeach; ?>
		<?php endif; ?>
	</tbody>
</table>

<div class="tablenav bottom">
    <div class="alignleft actions bulkactions">
        <label for="bulk-action-selector-bottom" class="screen-reader-text">Select bulk action</label>
        <select name="action" id="bulk-action-selector-bottom">
            <option value="-1">Bulk Actions</option>
            <option value="deleteall">Delete</option>
        </select>
        
        <input type="submit" id="doaction" class="button action" value="Apply">
    </div>
    <br class="clear">
</div>

</form>