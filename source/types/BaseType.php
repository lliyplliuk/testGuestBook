<?php


namespace types;

use types\interfaces\BaseTypeInterface;

/**
 * Класс, содержащий в себе базовые методы и свойства работы с типами данных
 */
abstract class BaseType implements BaseTypeInterface
{

    /**
     * @var array Массив, в котором хранится описание данных нужного типа
     */
    protected array $variables;

    public function __set($name, $value)
    {
        if (isset($this->variables[$name])) {
            switch ($this->variables[$name]['type']) {
                case "unixTime":
                case "integer":
                    $this->variables[$name]['value'] = (int)$value;
                    break;
                default:
                    if (gettype($value) === $this->variables[$name]['type'])
                        $this->variables[$name]['value'] = $value;
                    break;
            }
        }
    }

    public function __get($name)
    {
        return $this->variables[$name]['value'] ?? null;
    }

    public function __isset($name)
    {
        return isset($this->variables[$name]);
    }

    public function __unset($name)
    {
        unset($this->variables[$name]);
    }

    public function asArray(): array
    {
        $arr = [];
        foreach ($this->variables as $index => $val) {
            if (!empty($val['value'])) {
                if (!is_array($val['value'])) {
                    $arr[$index] = $val['value'];
                }
                else {
                    $arr[$index] = $this->ArrayToArray($val['value']);
                }
            }
        }
        return $arr;
    }

    /** Пробегаем по массиву и если тип данных BaseType - преобразовываю эти данные в массив
     * @param array $val
     * @return array
     */
    protected function ArrayToArray(array $val): array
    {
        $ret = [];
        foreach ($val as $index => $value) {
            if (is_array($value)) {
                $ret[$index] = $this->ArrayToArray($val);
            } else {
                if (get_parent_class($value) === "types\BaseType") {
                    $ret[$index] = $value->asArray();
                }
            }
        }
        return $ret;
    }
}