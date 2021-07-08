<?php 
$slug=isset($_GET['slug']) ? $_GET['slug'] : '';	
$notice=pickle_admin_notices()->admin->get_notice($slug); 
?>

<h2 class="wp-heading-inline">Notice</h2>

<a href="<?php echo admin_url('options-general.php?page=pickle-admin-notices&tab=notice&action=edit'); ?>" class="page-title-action">Add New</a>

<form name="post" action="" method="post" class="notice-form">
	<?php wp_nonce_field('update_notice', 'pickle_admin_notices_admin'); ?>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder columns-2">
			<div id="post-body-content">
				<div class="notice-input-row">
					<label for="notice_slug">Name</label>
					<input type="text" name="notice_details[name]" id="notice_name" class="notice-name" value="<?php echo $notice['name']; ?>" />
				</div>

				<div class="notice-input-row">
					<label for="notice_slug">Slug</label>
					<input type="text" name="notice_details[slug]" id="notice_slug" class="disabled notice-slug" value="<?php echo $notice['slug']; ?>" />
				</div>

				<div class="notice-input-row textarea">
					<label for="notice_notice">Notice</label>
					<textarea name="notice_details[notice]" id="notice_notice" class=""><?php echo $notice['notice']; ?></textarea>
				</div>

				<div class="notice-input-row">
					<label for="notice_type">Type</label>
					<select name="notice_details[type]" id="notice_type" class="" value="<?php echo $notice['notice']; ?>">
    					<option value="warning">Warning</option>
    					<option value="error">Error</option>
    					<option value="success">Success</option>
    					<option value="info">Info</option>    					    					    					
					</select>
				</div>
				
				<div class="notice-input-row radio">
					<label for="notice_display">Display</label>
					<div class="radio-wrap">
    					<label for="notice_display_yes"><input type="radio" name="notice_details[display]" id="notice_display_yes" class="notice-display" value="1" <?php checked($notice['display'], 1); ?> />Yes</label><br />
                        <label for="notice_display_no"><input type="radio" name="notice_details[display]" id="notice_display_no" class="notice-display" value="0" <?php checked($notice['display'], 0); ?> />No</label>
					</div>
				</div>

				<div class="notice-input-row radio">
					<label for="notice_dismissible">Dismissible</label>
					<div class="radio-wrap">
    					<label for="notice_dismissible_yes"><input type="radio" name="notice_details[dismissible]" id="notice_dismissible_yes" class="notice-display" value="1" <?php checked($notice['dismissible'], 1); ?> />Yes</label><br />
                        <label for="notice_dismissible_no"><input type="radio" name="notice_details[dismissible]" id="notice_dismissible_no" class="notice-display" value="0" <?php checked($notice['dismissible'], 0); ?> />No</label>
					</div>
				</div>
                      
			</div>
			
			<div id="postbox-container-1" class="postbox-container">
				<div id="side-sortables" class="meta-box-sortables ui-sortable" style="">
					<div id="" class="postbox ">
						<div class="inside">
							<div id="delete-action">
								<a class="submitdelete deletion" href="<?php echo admin_url('options-general.php?page=pickle-admin-notices&tab=notices&action=delete&slug='.$notice['slug']); ?>">Delete</a>
							</div>
	
							<div id="publishing-action">
								<input name="save" type="submit" class="button button-primary button-large" id="publish" value="Update">
							</div>
						</div>
					</div>
				</div>
			</div>
		
		</div>
	</div>
</form>