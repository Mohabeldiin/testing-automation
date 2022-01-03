<?php

namespace MyHomeCore\Common\Breadcrumbs;


use MyHomeCore\Attributes\Attribute;
use MyHomeCore\Attributes\Attribute_Factory;
use MyHomeCore\Attributes\Text_Attribute;
use MyHomeCore\Terms\Term;

/**
 * Class Breadcrumbs
 *
 * @package MyHomeCore\Common\Breadcrumbs
 */
class Breadcrumbs
{

    /**
     * @var Breadcrumb[]
     */
    private $breadcrumbs = array();

    /**
     * @var Term
     */
    private $current_term;

    /**
     * @var string
     */
    private $link = '';

    /**
     * @var array
     */
    private $ids = array();

    /**
     * Breadcrumbs constructor.
     */
    public function __construct()
    {
        $attributes = Attribute_Factory::get_text();
        $attributes_list = array();
        $counter = count($attributes);
        $key = 'mh-breadcrumbs_';
        $archive_link = get_post_type_archive_link('estate');
        $archive_title = \MyHomeCore\My_Home_Core()->settings->get('estate_archive-name');

        $this->link = $archive_link;

        if (empty($archive_title)) {
            $archive_title = esc_html__('Properties', 'myhome-core');
        } else {
            $archive_title = apply_filters(
                'wpml_translate_single_string',
                $archive_title,
                'MyHome - Settings',
                'Breadcrumbs archive title'
            );
        }

        $this->breadcrumbs[] = new Breadcrumb($archive_title, $archive_link);

        foreach ($attributes as $attribute) {
            $attributes_list[$attribute->get_ID()] = $attribute;
        }

        for ($i = 0; $i < $counter; $i++) {
            $current_key = $key.$i;
            if (empty(\MyHomeCore\My_Home_Core()->settings->props[$current_key])) {
                break;
            }

            $attribute_id = intval(\MyHomeCore\My_Home_Core()->settings->props[$current_key]);
            if ( ! isset($attributes_list[$attribute_id])) {
                break;
            }

            global $wp_query;

            if (empty($wp_query->query_vars[$attributes_list[$attribute_id]->get_slug()])) {
                return;
            }

            $value = $wp_query->query_vars[$attributes_list[$attribute_id]->get_slug()];

            $wp_term = get_term_by('slug', $value, $attributes_list[$attribute_id]->get_slug());
            if ($wp_term == false) {
                break;
            }

            $this->current_term = new Term($wp_term);
            $breadcrumb =
                new Breadcrumb($this->current_term->get_name(), $archive_link, $attributes_list[$attribute_id],
                    $this->current_term, $this->ids);
            $this->ids = $breadcrumb->get_ids_filtered();
            $this->breadcrumbs[] = $breadcrumb;
            $archive_link .= $this->current_term->get_slug().'/';

            $this->link = $archive_link;
        }

        $this->link = $archive_link;
    }

    /**
     * @return bool
     */
    public function has_child()
    {
        return count($this->breadcrumbs) - 1 <= Breadcrumbs::breadcrumbs_number();
    }

    /**
     * @return bool|Text_Attribute
     */
    public function get_child_attribute()
    {
        $child_key = 'mh-breadcrumbs_'.(count($this->breadcrumbs) - 1);

        if ( ! isset(\MyHomeCore\My_Home_Core()->settings->props[$child_key])
             || empty(\MyHomeCore\My_Home_Core()->settings->props[$child_key])
        ) {
            return false;
        }

        $attribute_id = \MyHomeCore\My_Home_Core()->settings->props[$child_key];

        return Attribute::get_by_id($attribute_id);
    }

    /**
     * @return Breadcrumb
     */
    public function get_child_attribute_breadcrumb()
    {
        $attribute = $this->get_child_attribute();
        $breadcrumb = new Breadcrumb($attribute->get_name(), $this->link, $attribute, false, $this->ids);

        return $breadcrumb;
    }

    /**
     * @return int
     */
    public static function breadcrumbs_number()
    {
        $attributes = Attribute_Factory::get_text();
        $counter = count($attributes);

        for ($i = 1; $i <= $counter; $i++) {
            if (
                ! isset(\MyHomeCore\My_Home_Core()->settings->props['mh-breadcrumbs_'.$i])
                || empty(\MyHomeCore\My_Home_Core()->settings->props['mh-breadcrumbs_'.$i])
            ) {
                return $i - 1;
            }
        }

        return $counter;
    }

    /**
     * @return Breadcrumb[]
     */
    public function get_elements()
    {
        return $this->breadcrumbs;
    }

    /**
     * @return array
     */
    public function get_elements_title()
    {
        $title = [];
        foreach ($this->get_elements() as $element) {
            $title[] = $element->get_name();
        }
        unset($title[0]);

        return implode(', ', $title);
    }

    /**
     * @return int
     */
    public function has_elements()
    {
        return ! empty($this->current_term) || is_post_type_archive('estate');
    }

    /**
     * @return Term
     */
    public function get_current_term()
    {
        return $this->current_term;
    }

    /**
     * @return bool
     */
    public function show_count()
    {
        return ! empty(\MyHomeCore\My_Home_Core()->settings->props['mh-breadcrumbs_show-count']);
    }

    /**
     * @return Text_Attribute[]
     */
    public static function get_attributes()
    {
        if ( ! isset(\MyHomeCore\My_Home_Core()->settings->props['mh-breadcrumbs'])
             || empty(\MyHomeCore\My_Home_Core()->settings->props['mh-breadcrumbs'])
        ) {
            return array();
        }

        $attributes = Attribute_Factory::get_text();
        $attributes_list = array();
        $counter = count($attributes);
        $key = 'mh-breadcrumbs_';
        $breadcrumbs = array();

        foreach ($attributes as $attribute) {
            $attributes_list[$attribute->get_ID()] = $attribute;
        }

        for ($i = 0; $i < $counter; $i++) {
            $current_key = $key.$i;
            if (empty(\MyHomeCore\My_Home_Core()->settings->props[$current_key])) {
                break;
            }

            $attribute_id = intval(\MyHomeCore\My_Home_Core()->settings->props[$current_key]);
            if ( ! isset($attributes_list[$attribute_id])) {
                break;
            }

            $breadcrumbs[] = $attributes_list[$attribute_id];
        }

        return $breadcrumbs;
    }
}