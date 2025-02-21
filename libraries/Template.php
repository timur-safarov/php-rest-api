<?php

namespace libraries;

/**
 * Класс для вывода шаблонов из папки Views
 */
Class Template {

    /**
     * Показать шаблон
     * @param string $templete
     * @param bool|mixed $vars
     * @param bool|mixed $layout
     * @return string
     */
    public static function show(string $templete, $vars = false, $layout = false)
    {

        $templete .= '.php';

    	if (is_array($vars)) {
    		foreach ($vars as $name => $value) {
    			${$name} = $value;
    		}
    	}

        if ($layout) {
            return include realpath("./views/layouts/{$layout}.php");
        } else {
            return include realpath('./views/'.$templete);
        }

    }


}