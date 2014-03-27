<?php

namespace SmsPilot;


class API {

    /**
     * Ключ для API
     * @see https://www.smspilot.ru/my-settings.php#api
     * @var string
     */
    protected $key = 'XXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZXXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZ';

    /**
     * API ключ
     * @return string
     */
    protected function getKey()
    {
        return $this->key;
    }

    protected $host = 'http://smspilot.ru/api2.php';

    /**
     * @return string
     */
    protected function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $key
     */
    function __construct($key=null)
    {
        $this->key = is_string($key) && !is_null($key) ? $key : 'XXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZXXXXXXXXXXXXYYYYYYYYYYYYZZZZZZZZ';
    }

    /**
     * @var bool
     */
    protected $debug = false;

    /**
     * @param boolean|string $debug
     * @return $this
     */
    public function setDebug($debug=false)
    {
        $this->debug = is_string($debug) ? $debug : false;

        return $this;
    }

    /**
     * @return boolean|string
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * Отправка смс
     * @param $send
     * @return array
     */
    public function send($send)
    {
        return $this->post(array(
                'send' => $send
            ));
    }

    /**
     * @param int|array $id ID или массив ID сообщений, назначенные сервером
     * @return array
     */
    public function checkById($id)
    {
        $ids = array();
        if (is_array($id)) {
            foreach ($id as $v) {
                $ids[] = array('server_id' => $v);
            }
        } else {
            $ids = $id;
        }

        return $this->post(array(
                'check' => $ids
            ));
    }

    /**
     * @param int $packetId ID пакета
     * @return array
     */
    public function checkByPacket($packetId)
    {
        return $this->post(array(
                'check' => true,
                'server_packet_id' => $packetId
            ));
    }

    /**
     * @param string $currency Валюта: либо 'rur' либо 'sms'
     * @return array
     */
    public function balance($currency='rur')
    {
        return $this->post(array(
                'balance' => $currency
            ));
    }

    /**
     * Информация о пользователе
     * @return array
     */
    public function info()
    {
        return $this->post(array(
                'info' => true
            ));
    }

    /**
     * Список зарегистрированных входящих СМС
     * @param null $since YYYY-MM-DD HH:II:SS
     * @return array
     */
    public function inbound($since=null)
    {
        $params = array(
            'inbound' => true
        );
        if ($since) {
            $params['since'] = $since;
        }

        return $this->post($params);
    }

    /**
     * Отправка данных
     * @param $send
     * @return array
     * @throws SmsPilotException
     */
    protected function post($send)
    {
        $send['apikey'] = $this->getKey();
        if ($this->getDebug()) {
            $send['debug'] = $this->getDebug();
        }

        $result = json_decode(
            file_get_contents(
                $this->getHost(),
                false,
                stream_context_create(
                    array(
                        'http' => array(
                            'method' => 'POST',
                            'header' => "Content-Type: application/json\r\n",
                            'content' => json_encode($send),
                        ),
                    )
                )
            ),
            true
        );

        if (isset($result['error'])) {
            throw new SmsPilotException($result['error']['description'], $result['error']['code']);
        }

        return $result;
    }

} 