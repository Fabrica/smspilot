<?php

namespace SmsPilot;


class Sms {

    /**
     * Уникальный код сообщения в вашей системе, целое число.
     * @var int
     */
    protected $id;         

    /**
     * Уникальный код присвоенный сообщению шлюзом, целое число
     * @var int
     */
    protected $server_id;

    /**
     * Имя отправителя
     * @var string|int
     */
    protected $from;

    /**
     * Телефонный номер абонента
     * @var int
     */
    protected $to;

    /**
     * Стоимость SMS
     * @see http://www.smspilot.ru/price.php
     * @var double|float
     */
    protected $price;

    /**
     * Кол-во частей сообщения
     * @var int
     */
    protected $parts;

    /**
     * Код статуса (-2 – не принято, 0 – принято)
     * @var int
     */
    protected $status;

    /**
     * Код ошибки, если сообщение не принято, см. в конце этого документа
     * @var int
     */
    protected $error;

    /**
     * Код всего пакета назначенный шлюзом
     * @var int
     */
    protected $server_packet_id;

    /**
     * Баланс после отправки
     * @var double|float
     */
    protected $balance;

    /**
     * Дата/время отложенной отправки
     * @var \Datetime|null
     */
    protected $send_datetime;

    /**
     * Страна
     * @var string
     */
    protected $country;

    /**
     * Оператор
     * @var string
     */
    protected $operator;

    /**
     * Текст сообщения
     * @var string
     */
    protected $text;

    static $statuses = array(
        '-2' => 'Сервер не получал это сообщение. ID не найден.',
        '-1' => 'Сообщение не доставлено. Телефоно абонента выключен, оператор не поддерживается.',
        '0' => 'Принято',
        '1' => 'У оператора',
        '2' => 'Доставлено',
        '3' => 'Отложенная отправка',
    );

    /**
     * Установить ID
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Баланс после отправки
     * @return float
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Страна (Код)
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Код ошибки
     * @return int
     */
    public function getErrorCode()
    {
        return $this->error;
    }

    /**
     * Текст ошибкаи
     * @return string|false
     */
    public function getError()
    {
        return SmsPilotException::getErrorInfo($this->getErrorCode());
    }

    /**
     * Имя или телефон отправителя
     * @return int|string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * ID в Вашей системе
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Оператор
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * Кол-во частей сообщения
     * @return int
     */
    public function getParts()
    {
        return $this->parts;
    }

    /**
     * Цена отправки сообщения
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Дата отложенной отправки
     * @return \Datetime|null
     */
    public function getSendDatetime()
    {
        return $this->send_datetime;
    }

    /**
     * ID в системе шлюза
     * @return int
     */
    public function getServerId()
    {
        return $this->server_id;
    }

    /**
     * ID пакета, в рамках которого было отправлено сообщение
     * @return int
     */
    public function getServerPacketId()
    {
        return $this->server_packet_id;
    }

    /**
     * Код статуса состояния сообщения
     * @return int
     */
    public function getStatusCode()
    {
        return $this->status;
    }

    /**
     * Текст статуса состояния сообщения
     * @return string
     */
    public function getStatus()
    {
        return self::$statuses[$this->status] ? self::$statuses[$this->status] : false;
    }

    /**
     * Номер телефона получателя сообщения
     * @return int
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * @param array $sms информация об смс - см свойство класса
     */
    function __construct($sms=array())
    {
        $this->set($sms);
    }

    /**
     * Установить значения в объект
     * @param array $sms
     */
    public function set($sms=array())
    {
        if (is_array($sms)) {
            foreach ($sms as $k => $v) {
                if (property_exists($this, $k)) {
                    $this->$k = $v;
                }
            }
        }
    }

    /**
     * Собрать данные для отправки
     * @return array
     */
    public function toSend()
    {
        $send = array(
            'from' => $this->getFrom(),
            'to' => $this->getTo(),
            'text' => $this->getText()
        );
        if ($this->getId()) {
            $send['id'] = $this->getId();
        }

        return $send;
    }

} 