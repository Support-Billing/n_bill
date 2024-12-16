<?php

// app/Services/MyService.php

namespace App\Services;
use Illuminate\Support\Facades\Auth;
use DB;

class MyService
{

    private $_id_role = '';
    private $_access_menu;
    private $_myService;
    private $_url_data = 'folderserver';

    public static $nama_bulan = array('Jan' => 'Januari', 'Feb' => 'Februari', 'Mar' => 'Maret', 'Apr' => 'April', 'May' => 'Mei', 'Jun' => 'Juni', 'Jul' => 'Juli', 'Aug' => 'Agustus', 'Sep' => 'September', 'Oct' => 'Oktober', 'Nov' => 'November', 'Dec' => 'Desember');

    public function __construct()
    {        

    }
    public function initialize()
    {
        // Melakukan pengecekan di sini
        if (Auth::check()) {
            $this->_id_role = Auth::user()->id_role;
        }
    }

    public static function switch_tanggal($tanggal, $format = '') {
        if (!empty($tanggal)) {
            switch ($format) {
                case 1:
                    list($a, $b, $c) = explode('-', date('d-M-Y', strtotime($tanggal)));
                    $b = self::$nama_bulan[$b];
                    $date = $a . ' ' . $b . ' ' . $c;
                    break;

                case 2:
                    list($a, $b, $c) = explode('-', $tanggal);
                    $date = $c . '/' . $b . '/' . $a;
                    break;

                case 3:
                    list($a, $b, $c) = explode('/', $tanggal);
                    $date = $c . '/' . $b . '/' . $a;
                    $date = date('Y-m-d', strtotime($date));
                    break;

                case 4:
                    $date = date('d-M-Y', strtotime($tanggal));
                    break;
                    
                case 5:
                    // Ubah format tanggal ke 'mm/dd/yyyy'
                    $tanggal = \DateTime::createFromFormat('d/m/Y', $tanggal)->format('m/d/Y');
                    $date = date('Y-m-d', strtotime($tanggal));
                    break;

                default:
                    list($a, $b, $c) = explode('-', date('Y-M-d', strtotime($tanggal)));
                    $date = $c . '-' . $b . '-' . $a;
                    break;
            }

            return $date;
        } else {
            return '';
        }
    }
    
    public function seriliaze_decode($array = array()){

        $dec = array();

        if (is_array($array)) {
            foreach ($array as $value) {
                $dec[$value['name']] = $value['value'];
            }
        }

        return $dec;
    }

    public function parser_access_menu($url = ''){
        $id_user = Auth::user()->id_role;
        // echo $id_user;exit;
        $query = "
            SELECT 
                `a`.`id_menu`,
                `a`.`id_role`,
                `a`.`view_otoritas_modul`,
                `a`.`insert_otoritas_modul`,
                `a`.`update_otoritas_modul`,
                `a`.`delete_otoritas_modul`,
                `a`.`export_otoritas_modul`,
                `a`.`import_otoritas_modul`,
                `a`.`data_otoritas_modul`
            FROM `otoritas_moduls` `a` 
            JOIN `moduls` `b` ON `a`.`id_menu` = `b`.`id` 
            WHERE 
                `a`.`id_role` = '{$id_user}' AND 
                `b`.`url` = '{$url}' ;
        ";
        
        $data  = DB::select($query);
        if ( !empty($data) ){
            return $data[0];
        }else{
            return false;

        }
    }
    

    public function generate_tombol_delete( $key){
        $return_tombol = "<a href='javascript:void(0);' class='btn btn-danger btn-xs' style='float:right'";
        $return_tombol .= "onclick=\"pagefunction('delete_value_kolom', '{$key}','delete')\"";
        $return_tombol .= "data-original-title='Delete'";
        $return_tombol .= 'rel="tooltip"';
        $return_tombol .= "data-placement=\"left\">";
        $return_tombol .= "<i class='fa fa-trash-o'></i>";
        $return_tombol .= "</a>";

        return $return_tombol;

    }

    public static function columns_align($value, $align = 'left') {
        $class = '';
        switch ($align) {
            case 'center':
                $class = 'text-align-center';
                break;
            case 'right':
                $class = 'text-align-right';
                break;
        }

        if (!empty($class)) {
            return "<div class='{$class}'>" . $value . "</div>";
        } else {
            return $value;
        }
    }

    public static function button_action($button = '', $align = 'center') {
        return self::columns_align(!empty($button) ? $button : '<i class="fa fa-lock"></i>', $align);
    }

    
    private static $st_aktif = array('t' => 'Aktif', 'f' => 'Tidak Aktif');

    public static function opt_st_aktif($kode = '', $default = '--Pilih--', $key = '') {
        if (empty($kode)) {
            if (!empty($default)) {
                return self::merge_array(array($key => $default), self::$st_aktif);
            } else {
                return self::$st_aktif;
            }
        } else {
            return isset(self::$st_aktif[$kode]) ? self::$st_aktif[$kode] : '-';
        }
    }

    
    private static $st_approve_color = array('t' => '<span class="label bg-color-green">Sudah Disetujui</span>', 'f' => '<span class="label bg-color-pink">Sedang Diproses</span>', 'r' => '<span class="label bg-color-red">Ditolak</span>');

    public static function opt_st_approve_color($kode = '', $default = '--Pilih--', $key = '') {
        if (empty($kode)) {
            if (!empty($default)) {
                return self::merge_array(array($key => $default), self::$st_approve_color);
            } else {
                return self::$st_approve_color;
            }
        } else {
            return isset(self::$st_approve_color[$kode]) ? self::$st_approve_color[$kode] : '-';
        }
    }
    
    private static $st_approve = array('t' => 'Sudah Disetujui', 'f' => 'Sedang Diproses', 'r' => 'Ditolak');

    public static function opt_st_approve($kode = '', $default = '--Pilih--', $key = '') {
        if (empty($kode)) {
            if (!empty($default)) {
                return self::merge_array(array($key => $default), self::$st_approve);
            } else {
                return self::$st_approve;
            }
        } else {
            return isset(self::$st_approve[$kode]) ? self::$st_approve[$kode] : '-';
        }
    }

    
    public static function date_ago($date) {
        $ts = strtotime($date);
        $tsYmdDate = strtotime(date('Y-m-d 00:00:00', $ts));

        $tsNow = time();
        $dateNow = date('Y-m-d H:i:s', $tsNow);
        $tsYmdNow = strtotime(date('Y-m-d 00:00:00', $tsNow));

        $diff = ($tsYmdNow - $tsYmdDate) / (60 * 60 * 24);

        if ($diff == '1') {
            return "last hour " . date('H:i:s', $ts);
        } else {

            $diff = abs($tsNow - $ts);

            $seconds = $diff;
            $minutes = floor($diff / 60);
            $hours = floor($minutes / 60);
            $days = floor($hours / 24);

            if ($seconds < 60) {
                return "$seconds seconds ago";
            } elseif ($minutes < 60) {
                return ($minutes == 1) ? "a minute ago" : "$minutes minutes ago";
            } elseif ($hours < 24) {
                return ($hours == 1) ? "hour ago" : "$hours hours ago";
            } else {
                return self::switch_tanggal(date('Y-m-d', strtotime($date)), 1) . ' ' . date('H:i:s', strtotime($date));
            }
        }
    }

    
    public static function zero_fill($str, $length) {
        return str_pad($str, $length, "0", STR_PAD_LEFT);
    }

    
    public static function rupiah($number, $dec = 2) {
        return number_format($number, $dec, ',', '.');
    }

    public static function dollar($number, $dec = 2) {
        return number_format($number, $dec, '.', ',');
    }

    
    public static function datetime_diff($str_interval = '', $start = '', $end = '') {

        $start = date('Y-m-d H:i:s', strtotime($start));
        $end = date('Y-m-d H:i:s', strtotime($end));
        $d_start = new DateTime($start);
        $d_end = new DateTime($end);
        $diff = $d_start->diff($d_end);

        $year = $diff->format('%y');
        $month = $diff->format('%m');
        $day = $diff->format('%d');
        $hour = $diff->format('%h');
        $min = $diff->format('%i');
        $sec = $diff->format('%s');

        switch ($str_interval) {
            case "y":
                $month = $month > 0 ? round($month / 12, 0) : 0;
                $day = $day > 0 ? round($day / 365, 0) : 0;
                $total = $year + $month / 12 + $day / 365;
                break;
            case "m":
                $day = $day > 0 ? round($day / 30, 0) : 0;
                $hour = $hour > 0 ? round($hour / 24, 0) : 0;
                $total = $year * 12 + $month + $day + $hour;
                break;
            case "d":
                $hour = $hour > 0 ? round($hour / 24, 0) : 0;
                $min = $min > 0 ? round($min / 60, 0) : 0;
                $total = ($year * 365) + ($month * 30) + $day + $hour + $min;
                break;
            case "h":
                $min = $min > 0 ? round($min / 60, 0) : 0;
                $total = ((($year * 365) + ($month * 30) + $day) * 24) + $hour + $min;
                break;
            case "i":
                $sec = $sec > 0 ? round($sec / 60, 0) : 0;
                $total = ((((($year * 365) + ($month * 30) + $day) * 24) + $hour) * 60) + $min + $sec;
                break;
            case "s":
                $total = ((((($year * 365) + ($month * 30) + $day) * 24 + $hour) * 60 + $min) * 60) + $sec;
                break;
        }

        if (strtotime($start) < strtotime($end))
            $total = -1 * $total;

        return $total;
    }

    
    public static function getTanggalSebelumnya($minus) {
        $now = new \DateTime();
    
        // Mengurangkan jumlah bulan dari tanggal sekarang
        $dateBefore = $now->sub(new \DateInterval('P' . $minus . 'M'));
    
        // Mengembalikan hasilnya
        return $dateBefore->format('Y-m-d');
    }

    public static function minute_to_hours($time, $format) {
        settype($time, 'integer');
        if ($time < 1) {
            return;
        }
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        return sprintf($format, $hours, $minutes);
    }

    public static function switch_number($number) {
        if (!empty($number)) {
            $a = str_replace(',00', '', $number);
            $b = str_replace('.', '', $a);
            $uang = str_replace(',', '.', $b);
        } else {
            $uang = '';
        }
        return $uang;
    }

    public static function switch_numeric($number, $replace = ',') {
        if ($replace == ',')
            return str_replace(',', '.', $number);
        else if ($replace == '.')
            return str_replace('.', ',', $number);
    }

    
    public static function menghitung_hari($start = '', $end = '') {
        $total = 1;
        while ($end != $start) {
            $start = date('Y-m-d', strtotime($start . " + 1 days"));
            $total++;
        }
        return $total;
    }

    public static function menghitung_bulan($start = '', $end = '') {
        $total = 1;
        while ($end != $start) {
            $start = date('M-Y', strtotime($start . " + 1 month"));
            $total++;
        }
        return $total;
    }
}
