<?php

//  เป็น class ที่สร้างมาเพื่อเรียกใช้ในการแจ้งเตือน alert ต่างๆ
class bootstrap
{

    public function displayAlert($message, $type)
    {
        echo '<div class="alert alert-' . $type . '" role="alert">';
        echo '' . $message . '';
        echo '</div>';
    }
}
