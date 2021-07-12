<?php

namespace types\interfaces;

use types\Msg;

interface MsgInterface
{
    /** Добавление ответа к сообщению
     * @param Msg $msg
     */
    public function addAnswer(Msg $msg): void;

    /** Загружаем данные из массива
     * @param array $msg Сообщение
     */
    public function load(array $msg): void;
}