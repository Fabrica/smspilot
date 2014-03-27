<?php

namespace SmsPilot;


class SmsCollection implements \Iterator {

    /**
     * @var Sms[]
     */
    private $sms = array();

    public function push(Sms $sms)
    {
        if (!$sms->getId()) {
            $sms->setId(sizeof($this->sms));
        }
        $this->sms[] = $sms;

        return $this;
    }

    public function toSend()
    {
        $array = [];
        foreach ($this->sms as $sms) {
            $array[] = $sms->toSend();
        }

        return $array;
    }

    public function appleResult($id, $result)
    {
        foreach($this->sms as $k => $sms) {
            if ($id == $sms->getId()) {
                $this->sms[$k]->set($result);
            }
        }
    }

    /**
     * @var int
     */
    protected $packet_id = 0;

    /**
     * @param int $packet_id
     * @return $this
     */
    public function setPacketId($packet_id)
    {
        $this->packet_id = $packet_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getPacketId()
    {
        return $this->packet_id;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return current($this->sms);
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return mixed
     */
    public function next()
    {
        next($this->sms);

        return $this->current();
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return key($this->sms);
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        $key = key($this->sms);
        $var = ($key !== NULL && $key !== FALSE);

        return $var;
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return mixed
     */
    public function rewind()
    {
        reset($this->sms);

        return $this->current();
    }

} 