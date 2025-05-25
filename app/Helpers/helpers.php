<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('translit')) {
    function translit($string,$ext='') {
        $charlist = array(
            "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
            "Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
            "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
            "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
            "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
            "Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
            "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
            "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
            "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
            "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
            "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
            "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
            "ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"," "=>"-"
        );
        $string = preg_replace("~\s{2,}~"," ", trim($string));
        $string = mb_strtolower(str_replace(' ','-',$string),'UTF-8');
        $string = strtr($string,$charlist);
        if(!empty($ext)) $string = $string.$ext;
        return strtr($string,$charlist);
    }
}

if (!function_exists('storeImage')) {
 function storeImage($base64,$patch,$fn)
{
    // Убираем префикс "data:image/png;base64," если он есть
    if (preg_match('/^data:image\/(\w+);base64,/', $base64, $type)) {
        $base64Image = substr($base64, strpos($base64, ',') + 1);
        $type = strtolower($type[1]); // jpg, png, gif

        // Проверяем, что это допустимый тип изображения
        if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
            return response()->json(['error' => 'Invalid image type'], 400);
        }

        // Декодируем base64 строку
        $image = base64_decode($base64Image);

        if ($image === false) {
            return response()->json(['error' => 'Failed to decode image'], 400);
        }

        // Генерируем путь до файла
        $filename = 'images/'.$patch.'/' . $fn . '.' . $type;

        // Сохраняем файл в хранилище (например, в папку storage/app/public/images)
        Storage::disk('public')->put($filename, $image);
        $src = Storage::url($filename);
        //  session()->put('user.userpic', $src);
        // Возвращаем путь к сохраненному файлу
        return $src;
    } else {
        return response()->json(['error' => 'Invalid base64 image'], 400);
    }
}

    if (!function_exists('rate')) {
        function rate($rating,$max=100)
        {
            $min = 1;
            $res = ceil(($rating - $min) / ($max - $min) * 9 + 1);
            return $res;
        }
    }

    if (!function_exists('dateAgo')) {
    function dateAgo($date){
        $today = date('d.m.Y', time());
        $yesterday = date('d.m.Y', time() - 86400);
        $dbDate = date('d.m.Y', strtotime($date));
        $dbTime = date('H:i', strtotime($date));
        switch ($dbDate)
        {
            case $today : $output = 'Сегодня в '. $dbTime; break;
            case $yesterday : $output = 'Вчера в '. $dbTime; break;
            default : $output = $dbDate.' '.$dbTime;
        }
        return $output;
       }
    }

    if (!function_exists('rusDate')) {

        function rusDate($date, $type = 'short')
        {
            $translate = array(
                "am" => "дп",
                "pm" => "пп",
                "AM" => "ДП",
                "PM" => "ПП",
                "Monday" => "Понедельник",
                "Mon" => "Пн",
                "Tuesday" => "Вторник",
                "Tue" => "Вт",
                "Wednesday" => "Среда",
                "Wed" => "Ср",
                "Thursday" => "Четверг",
                "Thu" => "Чт",
                "Friday" => "Пятница",
                "Fri" => "Пт",
                "Saturday" => "Суббота",
                "Sat" => "Сб",
                "Sunday" => "Воскресенье",
                "Sun" => "Вс",
                "January" => "Января",
                "Jan" => "Янв",
                "February" => "Февраля",
                "Feb" => "Фев",
                "March" => "Марта",
                "Mar" => "Мар",
                "April" => "Апреля",
                "Apr" => "Апр",
                "May" => "Мая",
                "June" => "Июня",
                "Jun" => "Июн",
                "July" => "Июля",
                "Jul" => "Июл",
                "August" => "Августа",
                "Aug" => "Авг",
                "September" => "Сентября",
                "Sep" => "Сен",
                "October" => "Октября",
                "Oct" => "Окт",
                "November" => "Ноября",
                "Nov" => "Ноя",
                "December" => "Декабря",
                "Dec" => "Дек",
                "st" => "ое",
                "nd" => "ое",
                "rd" => "е",
                "th" => "ое"
            );
            $date = strtotime($date);
            if ($type == 'short') {
                return strtr(date('j F Y', $date), $translate); // 2 Марта 2015
            } elseif ($type == 'shortandtime') {
                return strtr(date('j F Y, H:i', $date), $translate); // 2 Марта 2015  02:29
            } elseif ($type == 'dm') {
                return strtr(date('j F', $date), $translate); // 2 Марта
            } elseif ($type == 'number') {
                return strtr(date('d-m-Y', $date), $translate); // 02-03-2015
            } elseif ($type == 'long') {
                return strtr(date('l, j F Y', $date), $translate); // Понедельник, 2 Марта 2015
            } elseif ($type == 'longtime') {
                return strtr(date('j F Y l, H:i', $date), $translate); // Понедельник, 2 Марта 2015 02:29
            } elseif ($type == 'datetime') {
                return strtr(date('j F Y, H:i', $date), $translate); // Понедельник, 2 Марта 2015 02:29
            }

        }

    }




}
