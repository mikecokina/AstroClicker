<?php

class Fn
{

    /* test started session */
    public static function is_session_started()
    {
        if (session_id() == NULL)
            return FALSE;
        else
            return TRUE;
    }

    /* variable replace */
    public static function variable_replace($str, $arr)
    {
        while ($val = current($arr)) {
            ${key($arr)} = $val;
            next($arr);
        }
        $str = preg_replace('/\{([A-Z]+)\}/e', "$$1", $str);

        reset($arr);
        while ($val = current($arr)) {
            unset(${key($arr)});
            print(key($arr));
            next($arr);
        }
        return $str;
    }

    public static function redirect($loc, $status)
    {
        header('Location: ' . $loc, $status);
    }

    public static function valid_alphanum($string)
    {
        $able = '[0-9a-zA-Z\-_\s\.]';
        if (preg_match("/^$able+$/", $string))
            return true;
        else
            return false;
    }

    public static function valid_username($username)
    {
        $able = '[0-9a-zA-Z\-_\.]';
        if (preg_match("/^$able+$/", $username))
            return true;
        else
            return false;
    }

    public static function valid_cityname($cityname)
    {
        $pattern = "/^[a-zA-Z\- ]+$/";
        if (preg_match($pattern, $cityname))
            return true;
        else
            return false;
    }

    public static function removeNumericKeys($array)
    {
        foreach ($array as $main_key => $row) {
            foreach ($row as $key => $value) {
                if (is_int($key)) {
                    unset($array[$main_key][$key]);
                }
            }
        }
        return $array;
    }

    public static function t_body($metadata, $sql_fetch)
    {

    }

    public static function kurucz_model_tablename($temperature, $gravity, $metallicity)
    {

        $metallicity = ($metallicity < 0 ? "m" . str_pad((int)(abs($metallicity) * 10), 2, '0', STR_PAD_LEFT) :
            "p" . str_pad((int)(abs($metallicity) * 10), 2, '0', STR_PAD_LEFT));
        $gravity = "g" . str_pad((int)($gravity * 10), 2, '0', STR_PAD_LEFT);
        $temperature = (int)$temperature;
        return 'kurucz_' . $temperature . '_' . $gravity . '_' . $metallicity;
    }

    public static function scientificNotation($val, $prec)
    {
        $exp = floor(log($val, 10));
        if ($exp == -INF)
            return sprintf('%.' . $prec . 'fE%+03d', 0.0, 1);
        return sprintf('%.' . $prec . 'fE%+03d', $val / pow(10, $exp), $exp);
    }

    public static function cout($var)
    {
        print "<pre style=\"z-index:1000000;position:absolute;\">";
        print_r($var);
        print "</pre>";
    }

    public static function deleteDirectory($dir)
    {
        if (!file_exists($dir)) {
            return true;
        }
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (!self::deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }
        return rmdir($dir);
    }
}
