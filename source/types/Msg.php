<?php

namespace types;

use types\interfaces\MsgInterface;

/** Класс, содержащий в себе методы и свойства сообщений
 * @property int $id ID сообщения
 * @property string $nameAuthor Имя автора
 * @property int $timeCreated Время создания в формате unixTime
 * @property int $timeUpdate Время обновления в формате unixTime
 * @property string $sesId ID сессии пользователя
 * @property string $text Текст сообщения
 * @property array $answers массив ответов на сообщение
 */
class Msg extends BaseType implements MsgInterface
{
    /**
     * @var array Массив данных, содержащихся в типе сообщения
     */
    protected array $variables = [
        'id' => [
            'type' => 'integer',
            'value' => 0
        ],
        'nameAuthor' => [
            'type' => 'string',
            'value' => ""
        ],
        'timeCreated' => [
            'type' => 'unixTime',
            'value' => 1626104518
        ],
        'timeUpdate' => [
            'type' => 'unixTime',
            'value' => 0
        ],
        'sesId' => [
            'type' => 'string',
            'value' => ""
        ],
        'text' => [
            'type' => 'string',
            'value' => "0"
        ],
        'answers' => [
            'type' => 'array',
            'value' => []
        ],
    ];

    public static int $maxMsgId = 0;
    public static int $maxMsgTime = 0;

    public function addAnswer(Msg $msg): void
    {
        $answers = $this->answers;
        $answers[] = $msg;
        $this->answers = $answers;
    }

    public function load(array $msg): void
    {
        $sesId = session_id();
        if (self::$maxMsgId < ($msg['id'] ?? 0))
            self::$maxMsgId = $msg['id'];
        foreach ($msg as $attr => $value) {
            switch ($attr) {
                case "answers":
                    $this->loadAnswers((array)$value);
                    break;
                case "sesId":
                    if ($value === $sesId)
                        $this::$maxMsgTime = $msg['timeCreated'];
                    $this->$attr = $value;
                    break;
                default:
                    $this->$attr = $value;
                    break;
            };
        }
    }

    private function loadAnswers(array $value)
    {
        if (isset($value['message'])) {
            $answers = [];
            if (isset($value['message'][1])) {
                foreach ($value['message'] as $val) {
                    $msg = new $this;
                    $msg->load((array)$val);
                    $answers[] = $msg;
                }
            } else {
                $msg = new $this;
                $msg->load((array)$value['message']);
                $answers[] = $msg;
            }
            $this->answers = $answers;
        }
    }
}