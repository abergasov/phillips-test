<?php

namespace Core\Traits;

trait ProductParams {
    
    /**
     * Prepare params array to DB commint
     */
    public function beforeSave() {
        $this->params = serialize($this->params);
    }
    
    /**
     * get array from string
     * @param type $param
     */
    public function toObject($param) {
        $this->params = unserialize($param);
        return $this->params;
    }
    
    /**
     * Получить параметр
     * @param string $key
     * Ключ параметра
     * @return null|string
     * Значение параметра или null, если параметр не найден
     */
    public function getParam($key) {
        if (!is_array($this->params)) {
            $this->toObject($this->params);
        }
        return $this->findParamByKey($key, $this->params);
    }
    /**
     * Записать параметр заказа
     * @param $key
     * @param $value
     * @return $this
     */
    public function setParam($key, $value) {
        if (!is_array($this->params)) {
            $this->toObject($this->params);
        }
        return $this->findParamByKey($key, $this->params, $value);
    }
    
    /**
     * Получить все параметры
     * @return array
     */
    public function getParams() {
        return $this->params;
    }
    
    /**
     * Установить параметры
     * @param array $params
     * Массив с параметрами
     * @param bool $merge
     * Склеить массивы
     */
    public function setParams($params, $merge = FALSE) {
        $params = is_array($params) ? $params : $this->toObject($params);
        if ($merge == FALSE) {
            $this->params = $params;
        } else {
            $this->params[] = $params;
        }
    }
    /**
     * Удалить параметр
     * @param $key
     * @return $this
     */
    public function unsetParam($key) {
        if (!is_array($this->params)) {
            $this->toObject($this->params);
        }
        return $this->findParamByKey($key, $this->params, NULL);
    }
    
    /**
     * recursive function to search value in params
     * @param string value name
     * @param array array to search
     * @param type value to set, NULL if need unset
     * @return type founded value, return FALSE if not found
     */
    private function findParamByKey ($key, &$array, $value = FALSE) {
        $result = FALSE;
        foreach ($array as $pKey => &$pValue) {
            if ($pKey == $key) {
                if ($value === NULL) {
                    unset($array[$pKey]);
                } elseif ($value !== FALSE) {
                    $pValue = $value;
                } 
                $result = $pValue;
                break;
            } elseif (is_array($pValue)) {
                $result = $this->findParamByKey($key, $pValue, $value);
                if ($result !== FALSE) {
                    break;
                }
            }
        }
        return $result;
    }
}
