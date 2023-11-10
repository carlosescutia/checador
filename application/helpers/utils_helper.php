<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('get_nom_dia') )
{
    function get_nom_dia($fecha)
    {
        switch ( date('w', strtotime($fecha)) ) {
        case 0:
            $nom_dia = "Dom";
            break;
        case 1:
            $nom_dia = "Lun";
            break;
        case 2:
            $nom_dia = "Mar";
            break;
        case 3:
            $nom_dia = "Mie";
            break;
        case 4:
            $nom_dia = "Jue";
            break;
        case 5:
            $nom_dia = "Vie";
            break;
        case 6:
            $nom_dia = "Sab";
            break;
        }
        return $nom_dia;
    }
}

if ( ! function_exists('get_nombre_dia') )
{
    function get_nombre_dia($fecha)
    {
        switch ( date('w', strtotime($fecha)) ) {
        case 0:
            $nombre_dia = "Dom";
            break;
        case 1:
            $nombre_dia = "Lun";
            break;
        case 2:
            $nombre_dia = "Mar";
            break;
        case 3:
            $nombre_dia = "Mie";
            break;
        case 4:
            $nombre_dia = "Jue";
            break;
        case 5:
            $nombre_dia = "Vie";
            break;
        case 6:
            $nombre_dia = "Sab";
            break;
        }
        return $nombre_dia;
    }
}

if ( ! function_exists('get_nom_mes') )
{
    function get_nom_mes($mes)
    {
        switch ($mes) {
        case 1:
            $nom_mes = "Ene";
            break;
        case 2:
            $nom_mes = "Feb";
            break;
        case 3:
            $nom_mes = "Mar";
            break;
        case 4:
            $nom_mes = "Abr";
            break;
        case 5:
            $nom_mes = "May";
            break;
        case 6:
            $nom_mes = "Jun";
            break;
        case 7:
            $nom_mes = "Jul";
            break;
        case 8:
            $nom_mes = "Ago";
            break;
        case 9:
            $nom_mes = "Sep";
            break;
        case 10:
            $nom_mes = "Oct";
            break;
        case 11:
            $nom_mes = "Nov";
            break;
        case 12:
            $nom_mes = "Dic";
            break;
        }
        return $nom_mes;
    }
}

if ( ! function_exists('get_nombre_mes') )
{
    function get_nombre_mes($mes)
    {
        switch ($mes) {
        case 1:
            $nombre_mes = "Enero";
            break;
        case 2:
            $nombre_mes = "Febrero";
            break;
        case 3:
            $nombre_mes = "Marzo";
            break;
        case 4:
            $nombre_mes = "Abril";
            break;
        case 5:
            $nombre_mes = "Mayo";
            break;
        case 6:
            $nombre_mes = "Junio";
            break;
        case 7:
            $nombre_mes = "Julio";
            break;
        case 8:
            $nombre_mes = "Agosto";
            break;
        case 9:
            $nombre_mes = "Septiembre";
            break;
        case 10:
            $nombre_mes = "Octubre";
            break;
        case 11:
            $nombre_mes = "Noviembre";
            break;
        case 12:
            $nombre_mes = "Diciembre";
            break;
        }
        return $nombre_mes;
    }
}
