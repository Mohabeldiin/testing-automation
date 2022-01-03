<?php

namespace MyHomeIDXBroker;

/**
 * Class Api
 *
 * @package MyHomeIDXBroker
 */
class Api
{

    const CITIES                  = 'https://api.idxbroker.com/mls/cities';
    const COUNTIES                = 'https://api.idxbroker.com/mls/counties';
    const POSTAL_CODES            = 'https://api.idxbroker.com/mls/postalcodes';
    const PROPERTY_TYPES          = 'https://api.idxbroker.com/mls/propertytypes/a';
    const SEARCH_FIELDS           = 'https://api.idxbroker.com/mls/searchfields';
    const SEARCH_FIELD_VALUES     = 'https://api.idxbroker.com/mls/searchfieldvalues';
    const AGENTS                  = 'https://api.idxbroker.com/clients/agents';
    const PROPERTIES_ACTIVE       = 'https://api.idxbroker.com/clients/featured';
    const PROPERTIES_SOLD_PENDING = 'https://api.idxbroker.com/clients/soldpending';
    const PROPERTIES_SUPPLEMENTAL = 'https://api.idxbroker.com/clients/supplemental';
    const APPROVED_MLS            = 'https://api.idxbroker.com/mls/approvedmls';
    const ACCOUNT_INFO            = 'https://api.idxbroker.com/clients/accountinfo';

    const SYSTEM_LINKS        = 'https://api.idxbroker.com/clients/systemlinks';
    const SAVED_LINKS         = 'https://api.idxbroker.com/clients/savedlinks';
    const WIDGET_SRC          = 'https://api.idxbroker.com/clients/widgetsrc';
    const DYNAMIC_WRAPPER_URL = 'https://api.idxbroker.com/clients/dynamicwrapperurl';
    const WRAPPER_CACHE       = 'https://api.idxbroker.com/clients/wrappercache';

    const OPTION_API_KEY = 'api_key';

    /**
     * @var string
     */
    private $key;

    /**
     * Api constructor.
     */
    public function __construct()
    {
        $this->key = My_Home_IDX_Broker()->options->get(Api::OPTION_API_KEY);
    }

    /**
     * @return bool
     */
    public function has_key()
    {
        return ! empty($this->key);
    }

    /**
     * @param  string  $query
     * @param  array  $data
     *
     * @return array|bool|mixed|object
     */
    public function request($query, $data = array())
    {
        $headers = array(
            'Content-Type: application/x-www-form-urlencoded',
            'accesskey: '.$this->key,
            'outputtype: json',
            'apiversion: 1.7'
        );

        $handle = curl_init();
        curl_setopt($handle, CURLOPT_URL, $query);
        curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

        if ( ! empty($data)) {
            curl_setopt($handle, CURLOPT_POST, 1);
            curl_setopt($handle, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        if ($query == Api::WRAPPER_CACHE) {
            curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "DELETE");
        }

        $response = curl_exec($handle);
        $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

        if ($code >= 200 && $code < 300) {
            update_option('myhome_idx_broker_api_limit', 0);

            return json_decode($response);
        }

        if ($code == 412) {
            global $idx_broker_limit;
            $idx_broker_limit = true;
            update_option('myhome_idx_broker_api_limit', 1);
        }

        return false;
    }

    public function clear_wrapper_cache()
    {
        $query = Api::WRAPPER_CACHE;

        return $this->request($query);
    }

    public function get_account_info()
    {
        $query = Api::ACCOUNT_INFO;

        $account_info = $this->request($query);
        if (empty($account_info)) {
            return array();
        }

        return json_decode(json_encode($account_info), true);
    }

    /**
     * @return array
     */
    public function get_mls()
    {
        $query = Api::APPROVED_MLS;

        return $this->request($query);
    }

    /**
     * @param $mls_ID
     *
     * @return bool|object
     */
    public function get_search_fields($mls_ID)
    {
        $query = Api::SEARCH_FIELDS.'/'.$mls_ID;

        return $this->request($query);
    }

    /**
     * @param $mls_ID
     * @param $mls_pt_ID
     * @param $mls_name
     */
    public function get_search_field_values($mls_ID, $mls_pt_ID, $mls_name)
    {
        $query = Api::SEARCH_FIELD_VALUES.'/'.$mls_ID.'?mlsPtID='.$mls_pt_ID.'&name='.$mls_name;

        $this->request($query);
    }

    /**
     * @return array
     */
    public function get_agents()
    {
        $query = Api::AGENTS;

        $response = $this->request($query);

        if (isset($response->agent)) {
            return $response->agent;
        }

        return array();
    }

    /**
     * @param  string  $last_check
     *
     * @return array
     */
    public function get_new_active_properties($last_check = '')
    {
        $output = [];
        $query = self::PROPERTIES_ACTIVE;

        $response = $this->request($query);
        $data = json_decode(json_encode($response), true);

        if ( ! is_array($data)) {
            return $output;
        }

        $output += $data['data'];

        while ( ! empty($data['next'])) {
            $response = $this->request($data['next']);
            $data = json_decode(json_encode($response), true);

            if ( ! is_array($data)) {
                return $output;
            }

            $output += $data['data'];
        }

        return $output;
    }

    /**
     * @param  string  $last_check
     *
     * @return array
     */
    public function get_sold_pending_properties($last_check = '')
    {
        $output = [];
        $query = self::PROPERTIES_SOLD_PENDING;

        $response = $this->request($query);
        $data = json_decode(json_encode($response), true);

        if ( ! is_array($data)) {
            return $output;
        }

        $output += $data['data'];

        while ( ! empty($data['next'])) {
            $response = $this->request($data['next']);
            $data = json_decode(json_encode($response), true);

            if ( ! is_array($data)) {
                return $output;
            }

            $output += $data['data'];
        }

        return $output;
    }

    public function get_supplemental_properties()
    {
        $query = Api::PROPERTIES_SUPPLEMENTAL;

        $this->request($query);
    }

    /**
     * @return array
     */
    public function get_system_links()
    {
        $response = $this->request(Api::SYSTEM_LINKS);
        $system_links = json_decode(json_encode($response), true);

        if ( ! is_array($system_links)) {
            return array();
        }

        return $system_links;
    }

    /**
     * @return array
     */
    public function get_saved_links()
    {
        $response = $this->request(Api::SAVED_LINKS);
        $saved_links = json_decode(json_encode($response), true);

        if ( ! is_array($saved_links)) {
            return array();
        }

        return $saved_links;
    }

    /**
     * @return array
     */
    public function get_widget_src()
    {
        $response = $this->request(Api::WIDGET_SRC);
        $widget_src = json_decode(json_encode($response), true);

        if ( ! is_array($widget_src)) {
            return array();
        }

        return $widget_src;
    }

    /**
     * @param  array  $data
     */
    public function update_wrapper($data)
    {
        $this->request(Api::DYNAMIC_WRAPPER_URL, $data);
    }

}