<div class="wrap">
	<h1 class="wp-heading-inline">
		<?php esc_html_e( 'MyHome User Roles', 'myhome-core' ); ?>
		<a href="<?php admin_url( 'users.php?page=myhome-add-new-role' ); ?>" class="page-title-action">
			<?php esc_html_e( 'Add new', 'myhome-core' ); ?>
		</a>
	</h1>

	<table class="wp-list-table widefat fixed striped">

		<tr>
			<th scope="col" class="manage-column column-primary">
				<?php esc_html_e( 'Role', 'myhome-core' ); ?>
			</th>
			<th scope="col" class="manage-column column-primary">
				<?php esc_html_e( 'Frontend access', 'myhome-core' ); ?>
			</th>
			<th scope="col" class="manage-column column-primary">
				<?php esc_html_e( 'Submit properties', 'myhome-core' ); ?>
			</th>
			<th scope="col" class="manage-column column-primary">
				<?php esc_html_e( 'Moderate properties', 'myhome-core' ); ?>
			</th>
		</tr>

		<?php foreach ( get_editable_roles() as $myhome_role_name => $myhome_role_info ) : ?>
			<tr>
				<td><?php echo esc_html( $myhome_role_info['name'] ); ?></td>
				<td>
					<a class="button" href="<?php echo esc_url( admin_url( 'admin-post.php?action=myhome-edit-role' ) ); ?>">
						<?php esc_html_e( 'Edit', 'myhome-core' ); ?>
					</a>
				</td>
			</tr>
		<?php endforeach; ?>

	</table>
</div>