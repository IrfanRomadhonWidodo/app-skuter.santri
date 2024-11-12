<?php 

if (!function_exists('statusKas')) {
    function statusKas($number)
    {

        switch ($number) {
            case 0:
                return '<span class="badge badge-dark">Draft</span>';
                break;
            case 1:
                return '<span class="badge badge-success">Submited</span>';
                break;
            case 2:
                return '<span class="badge badge-secondary>Canceled</span>';
                break;
            default:
                return 'Unknown';
                break;
        }
    }
}

?>