<?php

namespace MyHomeIDXBroker;


use MyHomeCore\Terms\Term;

/**
 * Class Importer
 * @package MyHomeIDXBroker
 */
class Importer
{

    const LAST_CHECK = 'myhome_idx_broker_last_check';
    const CURRENT_STATUS = 'myhome_idx_broker_current_status';
    const STATUS_STOP = 'stop';
    const STATUS_WORK = 'work';
    const JOBS = 'myhome_idx_broker_jobs';
    const CRON_JOB = 'myhome_idx_broker_cron_job';
    const CRON_JOB_INIT = 'myhome_idx_broker_cron_init';
    const CRON_JOB_TASK = 'myhome_idx_broker_cron_task';
    const CRON_JOB_THUMBNAILS = 'myhome_idx_broker_cron_thumbnails';
    const CRON_HASH = 'myhome_idx_broker_hash';

    public function import()
    {
        $status = get_option(Importer::CURRENT_STATUS, Importer::STATUS_STOP);
        if ($status == Importer::STATUS_WORK) {
            $this->job();
        }
    }

    public function init()
    {
        $fields = get_option(Fields::OPTION_KEY);
        if (empty($fields) || !is_array($fields)) {
            $fields = new Fields();
            $fields->import();
        }

        $api = new Api();
        $properties_active = $api->get_new_active_properties();
        $disable_sold_import = My_Home_IDX_Broker()->options->get('disable_sold_import');
        if (!empty($disable_sold_import)) {
            $properties_sold_pending = [];
        } else {
            $properties_sold_pending = $api->get_sold_pending_properties();
        }
        $properties = array_merge($properties_sold_pending, $properties_active);

        update_option(Importer::JOBS, $properties);
        update_option(Importer::LAST_CHECK, date("Y-m-d h:i:s"));


        $agents = new Agents();
        $agents->import();

        $mls_ids = array();
        if (!empty($properties)) {
            foreach ($properties as $property) {
                $mls_ids[] = $property['listingID'];
            }
            $this->check_active($mls_ids);

            update_option(Importer::CURRENT_STATUS, Importer::STATUS_WORK);
            echo json_encode(array(
                'start' => true,
                'found' => count($properties),
                'msg' => esc_html__('Please wait synchronizing data...', 'myhome-idx-broker')
            ));
        } else {
            update_option(Importer::CURRENT_STATUS, Importer::STATUS_STOP);

            global $idx_broker_limit;
            if (!is_null($idx_broker_limit) && $idx_broker_limit) {
                $msg = esc_html__('Account is over its hourly access limit.', 'myhome-idx-broker');
            } else {
                $msg = esc_html__('Nothing new', 'myhome-idx-broker');
            }
            echo json_encode(array(
                'start' => false,
                'msg' => $msg
            ));
        }
    }

    public function check_active($mlsIds)
    {
        $leave_off_market_as_public = \MyHomeIDXBroker\My_Home_IDX_Broker()->options->get('leave_off_market_as_public');
        $offer_type = Term::get_term(My_Home_IDX_Broker()->options->get('offer_type_off_market'));

        $query = new \WP_Query(array(
            'post_type' => 'estate',
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ));

        foreach ($query->posts as $post) {
            $mlsId = get_post_meta($post->ID, Properties::IDX_LISTING_ID, true);
            if (empty($mlsId)) {
                continue;
            }

            if ($offer_type instanceof Term) {
                wp_set_object_terms($post->ID, array($offer_type->get_ID()), $offer_type->get_wp_term()->taxonomy);
            }

            if (!in_array($mlsId, $mlsIds)) {
                if (empty($leave_off_market_as_public)) {
                    wp_update_post(array(
                        'ID' => $post->ID,
                        'post_status' => 'draft'
                    ));
                }

                if ($offer_type instanceof Term) {
                    wp_set_object_terms($post->ID, array($offer_type->get_ID()), $offer_type->get_wp_term()->taxonomy);
                }
            }
        }
    }

    public function job()
    {
        $properties = get_option(Importer::JOBS);
        if (empty($properties) || !is_array($properties)) {
            update_option(Importer::CURRENT_STATUS, Importer::STATUS_STOP);

            return false;
        }

        $property = array_shift($properties);
        $properties_manager = new Properties();

        if (!$properties_manager->exists($property['listingID'])) {
            $properties_manager->create($property);
        } else {
            $properties_manager->update($property);
        }

        update_option(Importer::JOBS, $properties);

        if (empty($properties)) {
            update_option(Importer::CURRENT_STATUS, Importer::STATUS_STOP);
            update_option(Importer::CRON_JOB, Importer::STATUS_STOP);
        }

        return true;
    }

    public function cron()
    {
        $current_job = get_option(Importer::CRON_JOB, Importer::CRON_JOB_INIT);
        $importer = new Importer();
        $job_type = 'Nothing to do';

        switch ($current_job) {
            case Importer::CRON_JOB_INIT:
                $importer->init();
                update_option(Importer::CRON_JOB, Importer::CRON_JOB_TASK);
                $job_type = 'Init';
                break;
            case Importer::CRON_JOB_TASK:
                $importer->job();
                $job_type = 'Task';
                break;
            case Importer::CRON_JOB_THUMBNAILS;
                if (!$importer->generate_thumbnails(true)) {
                    update_option(Importer::CRON_JOB, Importer::CRON_JOB_TASK);
                }
                $job_type = 'Thumbnails';
                break;
        }

        update_option(
            'mh_cron_job',
            [
                'type' => $job_type,
                'date' => date('Y-m-d H:i:s')
            ]
        );
    }

    public function generate_thumbnails($is_cron = false)
    {
        $images = get_option(Properties::IDX_GENERATE_THUMBNAILS);
        $attachment = array_pop($images);

        Properties::generate_thumbnails($attachment);

        update_option(Properties::IDX_GENERATE_THUMBNAILS, $images);

        $next = empty($images) ? false : true;

        if (!$is_cron) {
            echo json_encode(array('next' => $next));
        }

        return $next;
    }

}