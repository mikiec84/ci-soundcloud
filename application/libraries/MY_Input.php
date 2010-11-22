<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class MY_Input extends CI_Input {

    var $_headers = false;

    function MY_Input() {
        parent::CI_Input();

        $this->_headers = $this->_clean_input_data($this->_get_headers());
    }

    function header($index = '', $xss_clean = false) {
        return $this->_fetch_from_array(
            $this->_headers,
            $index,
            $xss_clean
        );
    }

    function _get_headers() {
        if (function_exists('getallheaders')) {
            $headers = $this->_clean_input_data(getallheaders());
        } else {
            $headers = array();

            foreach ($_SERVER as $key => $val) {
                if (preg_match('/^http/i', $key)) {
                    $key = $this->_clean_input_keys(
                        str_replace(
                            ' ',
                            '-',
                            ucwords(
                                str_replace(
                                    array('http_', '_'),
                                    array('', ' '),
                                    strtolower($key)
                                )
                            )
                        )
                    );
                    $val = $this->_clean_input_data($val);
                    $headers[$key] = $val;
                }
            }
        }

        return $headers;
    }

}
