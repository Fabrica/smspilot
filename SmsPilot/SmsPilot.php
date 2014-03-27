<?php

namespace SmsPilot;


class SmsPilot {

    /**
     * @var API
     */
    protected $api = null;

    /**
     * @param string $apiKey
     * @return API
     */
    protected function createAPI($apiKey=null)
    {
        $this->api = new API($apiKey);

        return $this->api;
    }

    /**
     * @throws SmsPilotException
     * @return API
     */
    public function getApi()
    {
        if (is_null($this->api)) {
            throw new SmsPilotException('Create API before use it.');
        }

        return $this->api;
    }

    /**
     * Отправитель. Имя или номер телефона
     * @var string|int
     */
    protected $from = 'smspilot';

    /**
     * @param int|string|bool $from
     * @return $this
     */
    public function setFrom($from=false)
    {
        if (is_string($from) || is_int($from)) {
            $this->from = $from;
        } else {
            $this->from = 'smspilot';
        }

        return $this;
    }

    /**
     * @return int|string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @param string $apiKey
     * @param string $from
     */
    function __construct($apiKey=null, $from=null)
    {
        $this->createAPI($apiKey);
        $this->setFrom($from);
    }

    /**
     * @param int|array|bool $phone Телефон(ы) получателя
     * @param string $message Сообщение
     * @param string $from Отправитель
     * @return $this
     */
    public function send($phone=false, $message=null, $from=null)
    {
        if (is_array($phone)) {
            foreach ($phone as $v) {
                $this->addMessage($v, $message, $from ? $from : $this->getFrom());
            }
        } elseif (is_int($phone) || is_string($phone)) {
            $this->addMessage($phone, $message, $from ? $from : $this->getFrom());
        }

        $result = $this->getApi()->send($this->getCollection()->toSend());

        foreach ($result['send'] as $sms) {
            $this->getCollection()->appleResult($sms['id'], $sms);
        }

        $this->setBalance($result['balance']);
        $this->getCollection()->setPacketId($result['server_packet_id']);

        return $this;
    }

    /**
     * Создаёт сообщения
     * @param $phone
     * @param $message
     * @param $from
     * @return $this
     */
    public function addMessage($phone, $message, $from=null)
    {
        $params = array(
            'to' => $phone,
            'text' => $message,
            'from' => $from ? $from : $this->getFrom()
        );

        $this->getCollection()
            ->push(new Sms($params));

        return $this;
    }

    /**
     * @var SmsCollection
     */
    protected $collection = null;

    /**
     * @return SmsCollection
     */
    public function getCollection()
    {
        if (is_null($this->collection)) {
            $this->collection = new SmsCollection();
        }

        return $this->collection;
    }

    /**
     * Баланс
     * @var int
     */
    protected $balance = 0;

    /**
     * @param int $balance
     * @return $this
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * @return int
     */
    public function getBalance()
    {
        return $this->balance;
    }

} 