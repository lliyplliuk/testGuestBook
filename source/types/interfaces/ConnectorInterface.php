<?php

namespace types\interfaces;

interface ConnectorInterface
{
    /** Загружаем данные из хранилища и выдаем их в виде массива сообщений
     * @return array
     */
    public function load(): array;

    /** Сохраняем данные из масссива в хранилище
     * @param array $arr
     */
    public function save(array $arr): void;
}