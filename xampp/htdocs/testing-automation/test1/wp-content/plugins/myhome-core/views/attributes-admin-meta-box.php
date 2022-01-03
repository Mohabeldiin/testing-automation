<?php
/* @var \MyHomeCore\Attributes\Text_Attribute[] $attributes */
/* @var integer $estate_id */
?>
<div id="mh-admin-attributes">
	<?php foreach ( $attributes as $attribute ) :
		$estate_terms = \MyHomeCore\Terms\Term_Factory::get_from_estate( $estate_id, $attribute );
		$is_in_breadcrumbs = $attribute->is_in_breadcrumbs();
		?>
		<div class="acf-field acf-1of3">
			<div class="acf-label">
				<label><?php echo esc_html( $attribute->get_full_name() ); ?></label>
			</div>
			<div class="acf-input">
				<div id="taxonomy-post_tag" class="categorydiv">
					<input
						type="hidden"
						name="tax_input[<?php echo esc_attr( $attribute->get_slug() ); ?>][]"
						value="0"
					>

					<select
						class="selectize"
						name="tax_input[<?php echo esc_attr( $attribute->get_slug() ); ?>][]"
						<?php if ( ! $is_in_breadcrumbs ) : ?>
							multiple
						<?php else : ?>
							required
						<?php endif; ?>
					>
						<?php if ( ! $is_in_breadcrumbs ) : ?>
							<option value="">-</option>
						<?php endif; ?>

						<?php foreach (
							\MyHomeCore\Terms\Term_Factory::get(
								$attribute, \MyHomeCore\Terms\Term_Factory::ALL,
								\MyHomeCore\Terms\Term_Factory::ORDER_BY_NAME,
								\MyHomeCore\Terms\Term_Factory::ORDER_ASC
							) as $term
						) : ?>
							<option value="<?php echo esc_attr( $term->get_slug() ); ?>"
							        <?php if ( array_key_exists( $term->get_ID(), $estate_terms ) ) { ?>selected="selected"<?php } ?>>
								<?php echo esc_html( $term->get_name() ); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
		</div>
	<?php endforeach; ?>
</div>