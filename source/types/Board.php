<?php

namespace types;

use types\interfaces\BoardInterface;
use types\interfaces\ConnectorInterface;

/** Класс, содержащий в себе доску
 * @property array $messages сообщения доски
 */
class Board extends BaseType implements BoardInterface
{
    protected array $variables = [
        'messages' => [
            'type' => 'array',
            'value' => []
        ],
    ];

    protected ConnectorInterface $connector;

    /**
     * Board constructor.
     * @param string $connector Тип для сохранения данных
     */
    public function __construct(string $connector = "types\XML")
    {
        session_start();
        $this->connector = new $connector();
        $this->messages = $this->connector->load();
    }

    public function save(): void
    {
        $this->connector->save($this->asArray()['messages']);
    }

    public function addAnswer(Msg $msg, int $idMsg): bool
    {
        if (time() > Msg::$maxMsgTime + 10) {
            Msg::$maxMsgId++;
            $msg->id = Msg::$maxMsgId;
            $msg->sesId = session_id();
            $msg->timeCreated = time();
            $needMsg = $this->findMsg($idMsg, $this->messages);
            $needMsg->addAnswer($msg);
            return true;
        }
        return false;
    }

    /** Возвращает Msg текущего борда по ID записи
     * @param int $idMsg
     * @param array $arr
     * @return Msg
     */
    private function findMsg(int $idMsg, array $arr): Msg
    {
        foreach ($arr as $msg) {
            if ($msg->id === $idMsg)
                return $msg;
            if (count($msg->answers) > 0) {
                $msgTmp = $this->findMsg($idMsg, $msg->answers);
                if (($msgTmp->id ?? 0) === $idMsg)
                    return $msgTmp;
            }
        }
        return new Msg();
    }

    public function addMsg(Msg $msg): bool
    {
        if (time() > Msg::$maxMsgTime + 10) {
            $messages = $this->messages;
            Msg::$maxMsgId++;
            $msg->id = Msg::$maxMsgId;
            $msg->sesId = session_id();
            $msg->timeCreated = time();
            $messages[] = $msg;
            $this->messages = $messages;
            return true;
        }
        return false;
    }

    public function changeMsg(Msg $msg, int $idMsg): void
    {
        $needMsg = $this->findMsg($idMsg, $this->messages);
        if ($needMsg->sesId === session_id()) {
            $needMsg->text = $msg->text;
            $needMsg->nameAuthor = $msg->nameAuthor;
            $needMsg->timeUpdate = time();
        }
    }

    public function asJson(): string
    {
        return json_encode(['messages' => $this->asArray()['messages']]);
    }

    public function checkChange(): void
    {
        $this->setChange($this->messages);
    }

    private function setChange($arr): void
    {
        $sesId = session_id();
        foreach ($arr as $msg) {
            $msg->sesId = ($msg->sesId === $sesId) ? "1" : "0";
            if (count($msg->answers) > 0)
                $this->setChange($msg->answers);
        }
    }
}