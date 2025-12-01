<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Countries Helper
 * Provides country list and related functions
 */

if (!function_exists('get_countries')) {
    /**
     * Get list of countries
     * @return array Associative array of country code => country name
     */
    function get_countries() {
        return array(
            'US' => 'United States',
            'CA' => 'Canada',
            'GB' => 'United Kingdom',
            'AU' => 'Australia',
            'DE' => 'Germany',
            'FR' => 'France',
            'IT' => 'Italy',
            'ES' => 'Spain',
            'NL' => 'Netherlands',
            'BE' => 'Belgium',
            'CH' => 'Switzerland',
            'AT' => 'Austria',
            'SE' => 'Sweden',
            'NO' => 'Norway',
            'DK' => 'Denmark',
            'FI' => 'Finland',
            'PL' => 'Poland',
            'IE' => 'Ireland',
            'PT' => 'Portugal',
            'GR' => 'Greece',
            'CZ' => 'Czech Republic',
            'HU' => 'Hungary',
            'RO' => 'Romania',
            'BG' => 'Bulgaria',
            'HR' => 'Croatia',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'LT' => 'Lithuania',
            'LV' => 'Latvia',
            'EE' => 'Estonia',
            'LU' => 'Luxembourg',
            'MT' => 'Malta',
            'CY' => 'Cyprus',
            'IN' => 'India',
            'CN' => 'China',
            'JP' => 'Japan',
            'KR' => 'South Korea',
            'SG' => 'Singapore',
            'MY' => 'Malaysia',
            'TH' => 'Thailand',
            'ID' => 'Indonesia',
            'PH' => 'Philippines',
            'VN' => 'Vietnam',
            'PK' => 'Pakistan',
            'BD' => 'Bangladesh',
            'LK' => 'Sri Lanka',
            'NP' => 'Nepal',
            'MM' => 'Myanmar',
            'KH' => 'Cambodia',
            'LA' => 'Laos',
            'BN' => 'Brunei',
            'AE' => 'United Arab Emirates',
            'SA' => 'Saudi Arabia',
            'KW' => 'Kuwait',
            'QA' => 'Qatar',
            'BH' => 'Bahrain',
            'OM' => 'Oman',
            'JO' => 'Jordan',
            'LB' => 'Lebanon',
            'IL' => 'Israel',
            'TR' => 'Turkey',
            'EG' => 'Egypt',
            'ZA' => 'South Africa',
            'NG' => 'Nigeria',
            'KE' => 'Kenya',
            'GH' => 'Ghana',
            'TZ' => 'Tanzania',
            'UG' => 'Uganda',
            'ET' => 'Ethiopia',
            'BR' => 'Brazil',
            'MX' => 'Mexico',
            'AR' => 'Argentina',
            'CL' => 'Chile',
            'CO' => 'Colombia',
            'PE' => 'Peru',
            'VE' => 'Venezuela',
            'EC' => 'Ecuador',
            'UY' => 'Uruguay',
            'PY' => 'Paraguay',
            'BO' => 'Bolivia',
            'NZ' => 'New Zealand',
            'FJ' => 'Fiji',
            'PG' => 'Papua New Guinea',
            'RU' => 'Russia',
            'UA' => 'Ukraine',
            'BY' => 'Belarus',
            'KZ' => 'Kazakhstan',
            'UZ' => 'Uzbekistan',
            'GE' => 'Georgia',
            'AM' => 'Armenia',
            'AZ' => 'Azerbaijan',
        );
    }
}

if (!function_exists('get_country_name')) {
    /**
     * Get country name by code
     * @param string $code Country code
     * @return string Country name or empty string if not found
     */
    function get_country_name($code) {
        $countries = get_countries();
        return isset($countries[$code]) ? $countries[$code] : '';
    }
}

if (!function_exists('get_countries_dropdown')) {
    /**
     * Generate HTML dropdown options for countries
     * @param string $selected Selected country code
     * @return string HTML options
     */
    function get_countries_dropdown($selected = '') {
        $countries = get_countries();
        $options = '<option value="">Select Country</option>';
        
        foreach ($countries as $code => $name) {
            $sel = ($selected == $code) ? ' selected' : '';
            $options .= '<option value="' . html_escape($code) . '"' . $sel . '>' . html_escape($name) . '</option>';
        }
        
        return $options;
    }
}

