<?php  

class Service {
    
    private $result;
    
    private $name;
            
    private $path;
    private $verificationCode;
    
    private $isOnline = false;
    private $ping = 0;
    private $statusCode = 0;
    
    public function __construct($name, $path, $verificationCode) {
        $this->name = $name;
        
        $this->path = $path;
        $this->verificationCode = $verificationCode;
    }

    public function fetch() {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->path);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: {$this->verificationCode}"
        ));

        $result = curl_exec($ch);
        $this->statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $this->isOnline = (($this->statusCode >= 200 && $this->statusCode < 300) ? true : false);
        $this->ping = round(curl_getinfo($ch, CURLINFO_TOTAL_TIME) * 1000, 2);
        curl_close($ch);

        return $this->result = json_decode($result);
    }

    public function render() {

        $ramPer = number_format($this->result->memory->normal->usedPercent, 2);
        $ramTotal = self::humanBytes($this->result->memory->normal->total * 1024);
        $ramUsed = self::humanBytes($this->result->memory->normal->used * 1024);
        
        $diskPer = number_format($this->result->disk->usedPercent, 2);
        $diskTotal = self::humanBytes($this->result->disk->total);
        $diskUsed = self::humanBytes($this->result->disk->used );
        
        $cpuUsage = $this->result->cpu->usagePercent; 
        
        return "<div class=\"col span_1_of_4\">
                <h1>{$this->name}</h1>
                    <!---<p class=\"equalize box\">not yet</p>-->
                    <h1 class=\"box " . ($this->isOnline ? 'up' : 'down') . "\">" . ($this->isOnline ? 'Online' : 'Offline') . "</h1>
                    <h6 class=\"box none faded\"><b>OS: <i>{$this->result->os->system} ({$this->result->os->machine}/{$this->result->os->kernel->release})</i></b></h6>
                    <h6 class=\"box none faded\"><b>Uptime: <i>{$this->result->uptime->hours}:{$this->result->uptime->minutes}h, {$this->result->uptime->days} days</i></b></h6>
                    <h6 class=\"box none faded\"><b>Software: <i>{$this->result->server->software}</i></b></h6>
                    <h6 class=\"box none faded\"><b>Ping: <i>{$this->ping}ms</i></b></h6>
                    <h6 class=\"box none faded\">
                        <p class=\"center\"><b>RAM: {$ramPer}% <i>({$ramUsed}/{$ramTotal})</i></b></p>
                        <div class=\"progress\">
                            <div class=\"progress-bar progress-bar-info\" style=\"width: {$this->result->memory->normal->usedPercent}%\">
                                <span class=\"sr-only\"></span>
                            </div>
                        </div>
                    </h6>
                    <h6 class=\"box none faded\">
                        <p class=\"center\"><b>Disk: {$diskPer}% <i>({$diskUsed}/{$diskTotal})</i></b></p>
                        <div class=\"progress\">
                            <div class=\"progress-bar progress-bar-info\" style=\"width: {$this->result->disk->usedPercent}%\">
                                <span class=\"sr-only\"></span>
                            </div>
                        </div>
                    </h6>
                    <h6 class=\"box none faded\">
                        <p class=\"center\"><b>CPU: {$cpuUsage}%</b></p>
                        <div class=\"progress\">
                            <div class=\"progress-bar progress-bar-info\" style=\"width: {$cpuUsage}%\">
                                <span class=\"sr-only\"></span>
                            </div>
                        </div>
                    </h6>
		</div>";
    }
    
    private static function humanBytes($bytes, $decimals = 2) {
        $size   = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes/pow(1024, $factor)).@$size[$factor];
    }
}

?>