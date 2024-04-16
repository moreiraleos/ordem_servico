<?php

defined('BASEPATH') or exit('No direct script access allowed');

// require_once('./dompdf/autoload.inc.php');


/* Esse trecho de código é preciso quando for hospedar */

use Dompdf\Dompdf;


class Pdf
{

    function createPDF($html, $filename = '', $download = TRUE, $paper = 'A4', $orientation = 'portrait')
    {
        //        $dompdf = new dompdf\DOMPDF(); //Para localhost
        // $dompdf = new Dompdf($paper); //Para hospedado
        // $dompdf->load_html($html);
        // $dompdf->set_paper($paper, $orientation);
        // $dompdf->render();
        // if ($download)
        //     $dompdf->stream($filename . '.pdf', array('Attachment' => 1));
        // else
        //     $dompdf->stream($filename . '.pdf', array('Attachment' => 0));
        $dompdf = new Dompdf();

        $dompdf->loadHtml($html);
        $dompdf->set_paper($paper, $orientation);
        $dompdf->render();
        if ($download)
            $dompdf->stream($filename . '.pdf', array('Attachment' => 1));
        else
            $dompdf->stream($filename . '.pdf', array('Attachment' => 0));
    }
}
