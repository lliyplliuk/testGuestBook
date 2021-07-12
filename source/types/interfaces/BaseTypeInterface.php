<?php


namespace types\interfaces;


interface BaseTypeInterface
{
    /** Возвращаем объект как массив
     * @return array
     */
    public function asArray(): array;
}