             <?php

$ipAddress = $_SERVER['REMOTE_ADDR'];
$userAgent = $_SERVER['HTTP_USER_AGENT'];
function getBrowserName($userAgent) {
    if (strpos($userAgent, 'Edg') !== false) {
        return 'Microsoft Edge';
    } elseif (strpos($userAgent, 'OPR') !== false || strpos($userAgent, 'Opera') !== false) {
        return 'Opera';
    } elseif (strpos($userAgent, 'Chrome') !== false && strpos($userAgent, 'Safari') !== false) {
        return 'Google Chrome';
    } elseif (strpos($userAgent, 'Firefox') !== false) {
        return 'Mozilla Firefox';
    } elseif (strpos($userAgent, 'Safari') !== false) {
        return 'Safari';
    } elseif (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) {
        return 'Internet Explorer';
    } else {
        return 'Unknown';
    }
}





function getDeviceType($userAgent) {
    $userAgent = strtolower($userAgent);
    
    if (strpos($userAgent, 'mobile') !== false) {
        return 'Mobile';
    } elseif (strpos($userAgent, 'tablet') !== false || strpos($userAgent, 'ipad') !== false) {
        return 'Tablet';
    } elseif (strpos($userAgent, 'windows') !== false || strpos($userAgent, 'macintosh') !== false) {
        return 'Desktop';
    } else {
        return 'Unknown';
    }
}

// Example usage

$deviceType = getDeviceType($userAgent);
$browserName = getBrowserName($userAgent);

?>




