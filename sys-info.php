<?php
$verificationCode = '12345'; //please put here your verification code

/*
 * Dont touch some code unter this comment!
 */
if (!function_exists('getallheaders'))
{
    function getallheaders()
    {
           $headers = '';
       foreach ($_SERVER as $name => $value)
       {
           if (substr($name, 0, 5) == 'HTTP_')
           {
               $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
           }
       }
       return $headers;
    }
} 

if(!(isset(getallheaders()['Authorization']))) {
    http_response_code(403);
    exit;
} 

if(!(getallheaders()['Authorization'] == $verificationCode)) {
    http_response_code(403);
    exit;
}

$memInfo = null;
$cpuInfo = null;
$uptInfo = null;
$disInfo = null;

/*
 *  Memory Data Fetching
 * 
 */
preg_match_all('/(.*?):\s+(.*?) kB/m', shell_exec('cat /proc/meminfo'), $memmatch);
    for($i = 0; $i < sizeof($memmatch[1]); $i++) {
        $memInfo[$memmatch[1][$i]] = $memmatch[2][$i];
    }

/*
 * CPU Data Fetching 
 * 
 */
exec('ps aux', $processes);
$cpuUsage = 0;
foreach ($processes as $process) {
    $cols = split(' ', ereg_replace(' +', ' ', $process));
    if (strpos($cols[2], '.') > -1) {
        $cpuUsage += floatval($cols[2]);
    }
}

$cpuInfo = $cpuUsage;

/*
 * Uptime Data Fetching
 * 
 */
$uptInfo = shell_exec("cut -d. -f1 /proc/uptime");

/*
 * Disk Data Fetching
 * 
 */
$disInfo[0] = disk_free_space('/');
$disInfo[1] = disk_total_space('/');


$result = array(
   'memory' => [
       'normal' => [
           'used'   => @($memInfo['MemTotal'] - $memInfo['MemFree']- $memInfo['Cached']),
           'free'   => @($memInfo['MemFree']),
           'cached' => @($memInfo['Cached']),
           'total'  => @($memInfo['MemTotal']),
           
           'usedPercent'    => @((($memInfo['MemTotal'] - $memInfo['MemFree']- $memInfo['Cached']) / $memInfo['MemTotal']) * 100),
           'freePercent'    => @(($memInfo['MemFree'] / $memInfo['MemTotal']) * 100),
           'cachedPercent'  => @(($memInfo['Cached'] / $memInfo['MemTotal']) * 100)
       ],
       'swap' => [
           'used'   => @($memInfo['SwapTotal'] - $memInfo['SwapFree']),
           'free'   => @($memInfo['SwapFree']),
           'total'  => @($memInfo['SwapTotal']),
           
           'usedPercent'    => @((($memInfo['SwapTotal'] - $memInfo['SwapFree']) / $memInfo['SwapTotal']) * 100),
           'freePercent'    => @(($memInfo['SwapFree'] / $memInfo['SwapTotal']) * 100),
       ]
   ],
   
   'uptime' => [
       'days'       => @floor($uptInfo / 60 / 60 / 24),
       'hours'      => @($uptInfo / 60 / 60 % 24),
       'minutes'    => @($uptInfo / 60 % 60),
       'seconds'    => @($uptInfo % 60)
   ],
    
    'disk' => [
        'used'  => @($disInfo[1] - $disInfo[0]),
        'free'  => @($disInfo[0]),
        'total' => @($disInfo[1]),
        
        'usedPercent' => @((($disInfo[1] - $disInfo[0]) / $disInfo[1]) * 100),
        'freePercent' => @((($disInfo[0]) / $disInfo[21]) * 100),
    ],
    
    'os' => [
        'kernel' => [
            'name'      => @shell_exec('uname -s'),
            'release'   => @shell_exec('uname -r'),
            'version'   => @shell_exec('uname -v')
        ],
        'system' => @shell_exec('uname -o'),
        'machine' => @shell_exec('uname -m')
    ],
    
    'cpu' => [
        'usagePercent' => $cpuInfo
    ],
    
    'server' => [
        'software' => @$_SERVER['SERVER_SOFTWARE']
    ]
  
);
print_r(json_encode($result));

?>