<?php

namespace types\interfaces;

use types\Msg;

interface BoardInterface
{
    /** Добавление ответа к сообщению
     * @param Msg $msg
     * @param int $idMsg
     */
    public function addAnswer(Msg $msg, int $idMsg): bool;

    /** Добавление сообщения
     * @param Msg $msg
     */
    public function addMsg(Msg $msg): bool;

    /** Изменение сообщения
     * @param Msg $msg
     * @param int $idMsg
     */
    public function changeMsg(Msg $msg, int $idMsg): void;

    /** Сохранение книги в хранилище
     *
     */
    public function save(): void;

    /** Возвращаем массив сообщений как JSON
     * @return string
     */
    public function asJson(): string;

    /** Проверяем sesId и устанавливаем его в 1, если редактировать сообщение можно и в 0, если нельзя
     *
     */
    public function checkChange(): void;
}