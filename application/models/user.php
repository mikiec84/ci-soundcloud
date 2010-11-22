<?php
class User extends Model {

    public static $attributes = array(
        'id',
        'username',
        'name',
        'avatar',
        'access_token',
        'refresh_token',
        'expires'
    );

    private static $_resource = 'users';

    function User() {
        parent::Model();
    }

    function add($data) {
        foreach ($data as $key => $val) {
            if (in_array($key, self::$attributes)) {
                $this->{$key} = $val;
            }
        }

        return ($this->predis->set($this->_key(), json_encode($data)))
            ? $this
            : false;
    }

    function delete() {
        return $this->predis->set($this->_key(), null);
    }

    function update($data) {
        return ($this->predis->set($this->_key(), json_encode($data)))
            ? $this
            : false;
    }

    function find_by_hash($hash) {
        $data = $this->predis->get($this->_key($hash));

        if ($data) {
            $data = json_decode($data, true);

            foreach ($data as $key => $val) {
                $this->{$key} = $val;
            }

            return $this;
        }

        return false;
    }

    function hash() {
        return substr(md5($this->id . $this->config->item('salt')), 0, 7);
    }

    function _key($hash = null) {
        return self::$_resource . ':' . (($hash) ? $hash : $this->hash());
    }

}
