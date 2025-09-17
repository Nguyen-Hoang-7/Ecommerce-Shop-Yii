<?php

namespace common\helper;

class StringHelper
{

    /**
     * Tạo regex pattern để tìm kiếm linh hoạt với dấu tiếng Việt
     * Ví dụ: "trần" -> "tr[aàáạảãâầấậẩẫăằắặẳẵ]n"
     */
    public static function createVietnameseSearchPattern($searchTerm) {
        if ($searchTerm === null || $searchTerm === '') {
            return $searchTerm;
        }

        $searchTerm = strtolower($searchTerm);
        $vietnameseMap = [
            'a' => '[aàáạảãâầấậẩẫăằắặẳẵ]',
            'e' => '[eèéẹẻẽêềếệểễ]',
            'i' => '[iìíịỉĩ]',
            'o' => '[oòóọỏõôồốộổỗơờớợởỡ]',
            'u' => '[uùúụủũưừứựửữ]',
            'y' => '[yỳýỵỷỹ]',
            'd' => '[dđ]',
        ];

        $pattern = '';
        for ($i = 0; $i < strlen($searchTerm); $i++) {
            $char = $searchTerm[$i];
            if (isset($vietnameseMap[$char])) {
                $pattern .= $vietnameseMap[$char];
            } else {
                $pattern .= $char;
            }
        }
        
        return $pattern;
    }
}
