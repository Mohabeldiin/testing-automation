<?php

namespace MyHomeCore\Estates;

use MyHomeCore\Attributes\Attribute_Factory;
use MyHomeCore\Attributes\Number_Attribute;
use MyHomeCore\Attributes\Price_Attribute;
use MyHomeCore\Frontend_Panel\Panel_Fields;


/**
 * Class Estate_Data
 * @package MyHomeCore\Estates
 */
class Estate_Data {

	/**
	 * @param Estate $estate
	 *
	 * @return array
	 */
	public static function get_data( $estate ) {
		$gallery_limit = intval( \MyHomeCore\My_Home_Core()->settings->get( 'listing-gallery_limit' ) );

		$data = array(
			'id'                => $estate->get_ID(),
			'name'              => apply_filters( 'myhome_data_name', $estate->get_name() ),
			'slug'              => $estate->get_slug(),
			'excerpt'           => $estate->get_short_excerpt(),
			'link'              => $estate->get_link(),
			'has_price'         => $estate->has_price(),
			'image_srcset'      => \MyHomeCore\My_Home_Core()->images->get( $estate->get_image_id(), 'myhome-standard-xxxs' ),
			'image'             => wp_get_attachment_image_url( $estate->get_image_id(), 'standard' ),
			'attributes'        => $estate->get_attributes_data(),
			'address'           => apply_filters( 'myhome_data_address', $estate->get_address() ),
			'days_ago'          => $estate->get_days_ago(),
			'is_featured'       => $estate->is_featured(),
			'offer_type'        => $estate->get_offer_type()->get_data(),
			'status'            => $estate->get_status(),
			'payment_status'    => $estate->get_payment_state(),
			'attribute_classes' => $estate->get_attribute_classes(),
			'gallery'           => $estate->has_gallery() ? $estate->get_gallery_data( $gallery_limit ) : [],
			'date'              => get_the_date( '', $estate->get_post() )
		);

		if ( $data['has_price'] ) {
			if ( ! isset( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_listing-all-currencies'] ) || empty( \MyHomeCore\My_Home_Core()->settings->props['mh-estate_listing-all-currencies'] ) ) {
				$price = $estate->get_current_currency_prices_data();
				if ( empty( $price ) ) {
					$data['price'] = $estate->get_prices_data();
				} else {
					$data['price'] = $price;
				}
			} else {
				$data['price'] = $estate->get_prices_data();
			}
		}

		return $data;
	}

	/**
	 * @param Estate $estate
	 *
	 * @return array
	 */
	public static function get_full_data( $estate ) {
		$data = array(
			'id'           => $estate->get_ID(),
			'title'        => $estate->get_name(),
			'description'  => $estate->get_description(),
			'link'         => $estate->get_link(),
			'status'       => $estate->get_status(),
			'address'      => $estate->get_address(),
			'position'     => $estate->get_position(),
			'virtual_tour' => $estate->get_virtual_tour(),
			'is_featured'  => $estate->is_featured(),
			'is_paid'      => $estate->is_paid(),
			'attributes'   => array()
		);

		foreach ( $estate->get_attributes( true ) as $property_attribute ) {
			if ( ! $property_attribute->has_values() && ! $property_attribute->attribute instanceof Price_Attribute ) {
				continue;
			}

			$values = array();

			if ( $property_attribute->attribute instanceof Price_Attribute ) {
				foreach ( Panel_Fields::get_price_fields( $property_attribute->attribute ) as $field ) {
					$data['attributes'][ $field['slug'] ] = floatval( get_post_meta( $estate->get_ID(), 'estate_attr_' . $field['slug'], true ) );
				}
			} else {
				foreach ( $property_attribute->get_values() as $value ) {
					if ( $property_attribute->attribute instanceof Number_Attribute ) {
						$values = $value->get_value();
					} else {
						$values[] = $value->get_name();
					}
				}
			}

			if ( $property_attribute->attribute instanceof Price_Attribute ) {
				continue;
			}

			if ( $property_attribute->attribute instanceof Number_Attribute ) {
				$data['attributes'][ $property_attribute->get_slug() ] = $values;
			} else {
				$data['attributes'][ $property_attribute->get_ID() ] = $values;
			}
		}

		$offer_type_attribute = Attribute_Factory::get_offer_type();
		if ( $offer_type_attribute !== false ) {
			$values = array();
			foreach ( $estate->get_offer_type() as $value ) {
				$values[] = $value->get_name();
			}

			if ( count( $values ) ) {
				$data['attributes'][ $offer_type_attribute->get_ID() ] = $values;
			}
		}

		if ( $estate->has_gallery( true ) ) {
			$images = array();
			foreach ( $estate->get_gallery() as $image ) {
				$images[] = array(
					'id'     => $image['ID'],
					'url'    => $image['url'],
					'status' => 4
				);
			}
			$data['images'] = $images;
		}

		if ( $estate->has_plans() ) {
			$plans       = array();
			$plans_field = get_field( 'estate_plans', $estate->get_ID() );
			if ( ! is_array( $plans_field ) ) {
				$plans_field = array();
			}

			foreach ( $plans_field as $plan ) {
				$plans[] = array(
					'image_id' => $plan['estate_plans_image']['id'],
					'url'      => $plan['estate_plans_image']['url'],
					'name'     => $plan['estate_plans_name'],
					'status'   => 4
				);
			}

			$data['plans'] = $plans;
		}

		if ( $estate->has_attachments() ) {
			$attachments       = array();
			$attachments_field = get_field( 'estate_attachments', $estate->get_ID() );

			if ( ! is_array( $attachments_field ) ) {
				$attachments_field = array();
			}

			foreach ( $attachments_field as $attachment ) {
				$attachments[] = array(
					'name'   => $attachment['estate_attachment_name'],
					'status' => 4,
					'id'     => $attachment['estate_attachment_file']['id'],
					'url'    => $attachment['estate_attachment_file']['url']
				);
			}

			$data['attachments'] = $attachments;
		}

		if ( $estate->has_image() ) {
			$data['image'] = array(
				'image_id' => $estate->get_image_id(),
				'url'      => wp_get_attachment_image_url( $estate->get_image_id(), 'standard' )
			);
		}

		if ( $estate->has_additional_features() ) {
			$data['additional_features'] = $estate->get_additional_features();
		}

		$video         = $estate->get_video();
		$data['video'] = $video['url'];

		return $data;
	}

	/**
	 * @param Estate $estate
	 *
	 * @return array
	 */
	public static function get_marker_data( $estate ) {
		return array(
			'id'       => $estate->get_ID(),
			'name'     => $estate->get_name(),
			'slug'     => $estate->get_slug(),
			'link'     => $estate->get_link(),
			'image'    => $estate->get_marker_image(),
			'position' => $estate->get_position(),
			'price'    => $estate->get_current_currency_prices_data()
		);
	}

}