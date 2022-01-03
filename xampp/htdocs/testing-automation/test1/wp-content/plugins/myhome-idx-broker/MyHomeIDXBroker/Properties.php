<?php

namespace MyHomeIDXBroker;

use MyHomeCore\Attributes\Attribute;
use MyHomeCore\Attributes\Number_Attribute;
use MyHomeCore\Attributes\Text_Attribute;
use MyHomeCore\Common\Breadcrumbs\Breadcrumbs;
use MyHomeCore\Terms\Term;


/**
 * Class Properties
 *
 * @package MyHomeIDXBroker
 */
class Properties
{

    const IDX_LISTING_ID = 'idx_broker_property_id';
    const IDX_STATUS_PENDING = 'pending';
    const IDX_STATUS_ACTIVE = 'active';
    const IDX_STATUS_SOLD = 'sold';
    const IDX_GENERATE_THUMBNAILS = 'myhome_idx_broker_images';

    /**
     * @param array $properties
     */
    public function import($properties)
    {
        if (empty($properties)) {
            return;
        }

        foreach ($properties as $property) {
            if (!$this->exists($property['listingID'])) {
                $this->create($property);
            }
        }
    }

    private function update_images($property_id, $property)
    {
        if (!isset($property['image'])) {
            return;
        }

        $old_gallery = get_post_meta($property_id, 'estate_gallery', true);
        if (is_array($old_gallery)) {
            foreach ($old_gallery as $image_id) {
                wp_delete_attachment($image_id, 1);
            }

            update_post_meta($property_id, 'estate_gallery', '0');
        }

        $gallery = array();

        $attachments = array();
        if (isset($property['image']['totalCount'])) {
            unset($property['image']['totalCount']);
        }

        $images_limit_number = intval(\MyHomeIDXBroker\My_Home_IDX_Broker()->options->get('images_limit'));
        $images_limit = $images_limit_number != -1;
        $first = true;

        foreach ($property['image'] as $key => $image) {
            if ($images_limit && $images_limit_number == $key) {
                break;
            }

            $image = $image['url'];
            if (strpos($image, 'http') === false && strpos($image, 'https') === false) {
                $image = 'https:' . $image;
            }
            if (strpos($image, '&thumbnail') !== false) {
                $image = str_replace('&thumbnail', '', $image);
            }
            $image = apply_filters('myhome_idx_download_img_url', $image);
            $get = wp_remote_get($image, ['sslverify' => false]);
            $type = wp_remote_retrieve_header($get, 'content-type');
            $name = basename($image);
            if (
                $type == 'image/jpeg'
                && (
                    strpos('jpg', $image) === false
                    && strpos('jpeg', $image) === false
                    && strpos('JPG', $image) === false
                )
            ) {
                $name .= '.jpg';
            }
            $mirror = wp_upload_bits($name, '', wp_remote_retrieve_body($get));
            $size = getimagesize($mirror['file']);

            if (isset($size[0]) && $size[0] < 300) {
                continue;
            }

            $attachment = array(
                'post_title' => basename($image),
                'post_mime_type' => $type
            );

            $attachment_id = wp_insert_attachment($attachment, $mirror['file']);
            if (!is_wp_error($attachment_id)) {
                $gallery[] = $attachment_id;
                $attachments[] = array(
                    'id' => $attachment_id,
                    'file' => $mirror['file']
                );
                update_post_meta($property_id, 'estate_gallery', $gallery);

                if ($first) {
                    set_post_thumbnail($property_id, $attachment_id);
                    $first = false;
                }
            } else {
                $attachment_id->get_error_message();
            }
        }

        update_option(Properties::IDX_GENERATE_THUMBNAILS, $attachments);
        update_post_meta($property_id, 'estate_gallery', $gallery);

        if (IDX::$is_crone) {
            if (!empty($gallery)) {
                update_option(Importer::CRON_JOB, Importer::CRON_JOB_THUMBNAILS);
            }
        } else {
            echo json_encode(array('thumbnails' => !empty($gallery), 'count' => count($gallery)));
        }
    }

    /**
     * @param array $property
     *
     * @return int|\WP_Error
     */
    public function create($property)
    {
        if ($this->exists($property['listingID'])) {
            return false;
        }

        $property_name = '';
        if (isset($property['address'])) {
            $property_name = $property['address'];
        } elseif (isset($property['listingID'])) {
            $property_name = $property['listingID'];
        }

        $status = My_Home_IDX_Broker()->options->get('init_status');
        if (empty($status)) {
            $status = 'draft';
        }

        $property_data = array(
            'post_title' => $property_name,
            'post_type' => 'estate',
            'post_status' => $status
        );

        if (isset($property['userAgentID']) && !empty($property['userAgentID'])) {
            $agent = Idx_Broker_Agent::get_by_idx_broker_id($property['userAgentID']);
            if ($agent instanceof Idx_Broker_Agent) {
                $property_data['post_author'] = $agent->get_ID();
            }
        }

        if (!array_key_exists('post_author', $property_data)) {
            $default_user = intval(\MyHomeIDXBroker\My_Home_IDX_Broker()->options->get('user'));
            if (!empty($default_user)) {
                $property_data['post_author'] = $default_user;
            }
        }

        $property_id = wp_insert_post($property_data);
        $this->update_data($property_id, $property);

        $this->update_images($property_id, $property);

        return $property_id;
    }

    /**
     * @param array $property_data
     *
     * @return bool
     */
    public function update($property_data)
    {
        $property = $this->get_property($property_data['listingID']);

        if (!$property instanceof \WP_Post) {
            return false;
        }

        if (!\MyHomeIDXBroker\My_Home_IDX_Broker()->options->exists('update_all_data')) {
            $all_data = 1;
        } else {
            $all_data = \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get('update_all_data');
        }

        if (!empty($all_data)) {
            $this->update_data($property->ID, $property_data);
        }

        $update_images = apply_filters('myhome_idx_update_images', false);
        if ($update_images) {
            $this->update_images($property->ID, $property_data);
        }

        return $property->ID;
    }

    private function update_data($property_id, $property)
    {
        if (is_wp_error($property_id)) {
            return false;
        }

        if (!empty($property['fullDetailsURL'])) {
            update_post_meta($property_id, 'idx_url', $property['fullDetailsURL']);
        }

        if (isset($property['userAgentID']) && !empty($property['userAgentID'])) {
            $agent = Idx_Broker_Agent::get_by_idx_broker_id($property['userAgentID']);
            if ($agent instanceof Idx_Broker_Agent) {
                $user_id = $agent->get_ID();
            }
        }

        if (isset($property['mediaData']) && isset($property['mediaData']['vt'])
            && count($property['mediaData']['vt'])
        ) {
            foreach ($property['mediaData']['vt'] as $vt) {
                if (!isset($vt['url'])) {
                    continue;
                }
                $embed = '<iframe src="' . $vt['url'] . '"  frameborder="0" allowfullscreen></iframe>';
                update_post_meta($property_id, 'virtual_tour', $embed);
                break;
            }
        }

        if (!empty(\MyHomeIDXBroker\My_Home_IDX_Broker()->options->get('import_featured'))) {
            update_post_meta($property_id, 'estate_featured', '1');
        }


        if (!isset($user_id)) {
            $default_user = intval(\MyHomeIDXBroker\My_Home_IDX_Broker()->options->get('user'));
            if (!empty($default_user)) {
                $user_id = $default_user;
            }
        }

        if (isset($user_id)) {
            wp_update_post([
                'ID' => $property_id,
                'post_author' => $user_id
            ]);
        }

	    wp_update_post([
		    'ID' => $property_id,
		    'post_status' => 'publish',
	    ]);

        if (
            $property['idxStatus'] == Properties::IDX_STATUS_PENDING
            || $property['propStatus'] == 'PENDING'
            || $property['propStatus'] == 'Pending'
        ) {
            $offer_type = Term::get_term(My_Home_IDX_Broker()->options->get('offer_type_pending'));
        } elseif (
            (isset($property['rntLse']) && $property['rntLse'] == Properties::IDX_STATUS_SOLD)
            || (isset($property['idxPropType']) && $property['idxPropType'] == 'Lease')
        ) {
            $offer_type = Term::get_term(My_Home_IDX_Broker()->options->get('offer_type_rent'));
        } elseif ($property['idxStatus'] == Properties::IDX_STATUS_SOLD) {
            $offer_type = Term::get_term(My_Home_IDX_Broker()->options->get('offer_type_sold'));
        } else {
            $offer_type = Term::get_term(My_Home_IDX_Broker()->options->get('offer_type'));
        }

        if (!empty($property['openHouseDates'])) {
            $open_house_offer_type = Term::get_term(My_Home_IDX_Broker()->options->get('offer_type_open_house'));
        } else {
            $open_house_offer_type = false;
        }

        $offer_types = [];

        if ($offer_type instanceof Term) {
            $offer_types[] = $offer_type->get_ID();
            $offer_type_taxonomy = $offer_type->get_wp_term()->taxonomy;
        }

        if ($open_house_offer_type instanceof Term) {
            $offer_types[] = $open_house_offer_type->get_ID();
            $offer_type_taxonomy = $open_house_offer_type->get_wp_term()->taxonomy;
        }

        if (!empty($offer_types)) {
            wp_set_object_terms($property_id, $offer_types, $offer_type_taxonomy);
        }

        $fields = Fields::get();

        if (isset($property['listingID'])) {
            update_post_meta($property_id, Properties::IDX_LISTING_ID, $property['listingID']);
        }

        $map = array('zoom' => 5);
        if (isset($property['address'])) {
            $map['address'] = $property['address'];
        }

        if (isset($property['latitude'])) {
            $map['lat'] = $property['latitude'];
        }

        if (isset($property['longitude'])) {
            $map['lng'] = $property['longitude'];
        }
        update_field('myhome_estate_location', $map, $property_id);

        $taxonomies = array();
        $additional_features = array();

        foreach ($property as $key => $value) {
            if (!isset($fields[$key])) {
                if ($key == 'idxPropType' && isset($fields['propType'])) {
                    $key = 'propType';
                } elseif ($key == 'idxPropType' && isset($fields['propSubType'])) {
                    $key = 'propSubType';
                } else {
                    continue;
                }
            }

            $atts = $fields[$key]->get_value();
            if (!is_array($atts) && $atts == 'ignore') {
                continue;
            } elseif (in_array('ignore', $atts) || empty($atts)) {
                continue;
            }

            if (!is_array($atts)) {
                $atts = array($atts);
            }

            foreach ($atts as $attribute) {
                if (strpos($attribute, 'myhome_attribute') !== false) {
                    $attribute_id = str_replace('myhome_attribute_', '', $attribute);
                    $attribute = Attribute::get_by_id($attribute_id);

                    if ($attribute instanceof Text_Attribute) {
                        if (isset($taxonomies[$attribute->get_slug()])) {
                            $taxonomies[$attribute->get_slug()][] = $value;
                        } else {
                            $taxonomies[$attribute->get_slug()] = array($value);
                        }
                    } elseif ($attribute instanceof Number_Attribute) {
                        $value = floatval(str_replace(',', '', preg_replace("/[^0-9,.]/", "", $value)));
                        update_field('myhome_estate_attr_' . $attribute->get_slug(), $value, $property_id);
                    }
                } elseif (strpos($attribute, 'myhome_price') !== false) {
                    $value = floatval(str_replace(',', '', preg_replace("/[^0-9,.]/", "", $value)));
                    $price_key = str_replace('myhome_', '', $attribute);
                    update_field('myhome_estate_attr_' . $price_key, $value, $property_id);
                } elseif ($attribute == 'myhome_name') {
                    wp_update_post(array(
                        'post_title' => $value,
                        'ID' => $property_id
                    ));
                } elseif ($attribute == 'myhome_description') {
                    wp_update_post(array(
                        'post_content' => $value,
                        'ID' => $property_id
                    ));
                } elseif ($attribute == 'myhome_additional_features') {
                    $field = Fields::get_by_key($key);
                    if ($field != false && !empty(trim($value))) {
                        $additional_features[] = array(
                            'estate_additional_feature_name' => $field->get_display_name(),
                            'estate_additional_feature_value' => $value
                        );
                    }
                }
            }
        }

        update_field('estate_additional_features', $additional_features, $property_id);

        foreach (Breadcrumbs::get_attributes() as $attribute) {
            if (isset($taxonomies[$attribute->get_slug()]) && !empty($taxonomies[$attribute->get_slug()])) {
                continue;
            }

            $default_term_id = intval(My_Home_IDX_Broker()->options->get('attributes', $attribute->get_slug()));
            if ($default_term_id == 0) {
                $wp_terms = get_terms(array(
                    'taxonomy' => $attribute->get_slug()
                ));

                if (count($wp_terms) == 0) {
                    continue;
                }

                $default_term = new Term($wp_terms[0]);
            } else {
                $default_term = Term::get_term($default_term_id);
            }

            $taxonomies[$attribute->get_slug()] = $default_term->get_name();
        }

        foreach ($taxonomies as $taxonomy => $terms) {
            wp_set_object_terms($property_id, $terms, $taxonomy);
        }
    }

    /**
     * @param $idx_listing_ID
     *
     * @return bool|\WP_Post
     */
    public function get_property($idx_listing_ID)
    {
        $args = array(
            'post_type' => 'estate',
            'meta_key' => Properties::IDX_LISTING_ID,
            'meta_value' => $idx_listing_ID
        );
        $wp_query = new \WP_Query($args);

        return isset($wp_query->posts[0]) ? $wp_query->posts[0] : false;
    }

    /**
     * @param string $idx_listing_ID
     *
     * @return bool
     */
    public function exists($idx_listing_ID)
    {
        $args = array(
            'post_type' => 'estate',
            'meta_key' => Properties::IDX_LISTING_ID,
            'meta_value' => $idx_listing_ID
        );
        $wp_query = new \WP_Query($args);

        return $wp_query->found_posts > 0;
    }

    public static function generate_thumbnails($attachment)
    {
        $attachment_data = wp_generate_attachment_metadata($attachment['id'], $attachment['file']);
        wp_update_attachment_metadata($attachment['id'], $attachment_data);
    }

}