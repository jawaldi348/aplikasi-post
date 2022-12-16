<?php

use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\Printer;

class Testprint extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('masuk') == true && $this->session->userdata('idgrup') == '1') {
            $this->load->library([
                'form_validation', 'Bcrypt'
            ]);
            return true;
        } else {
            redirect('login/logout');
        }
    }
    public function index()
    {
        // me-load library escpos
        $this->load->library('escpos');

        // membuat connector printer ke shared printer bernama "printer_a" (yang telah disetting sebelumnya)
        $connector = new Escpos\PrintConnectors\WindowsPrintConnector("printer_novinaldi");

        // membuat objek $printer agar dapat di lakukan fungsinya
        $printer = new Escpos\Printer($connector);


        /* ---------------------------------------------------------
         * Teks biasa | text()
         */
        /* Set up command */
        $source = $this->load->view('testprint/test', '', true);
        $width = 550;
        $dest = tempnam(sys_get_temp_dir(), 'escpos') . ".png";
        $command = sprintf(
            "xvfb-run wkhtmltoimage -n -q --width %s %s %s",
            escapeshellarg($width),
            escapeshellarg($source),
            escapeshellarg($dest)
        );

        /* Test for dependencies */
        foreach (array("xvfb-run", "wkhtmltoimage") as $cmd) {
            $testCmd = sprintf("which %s", escapeshellarg($cmd));
            exec($testCmd, $testOut, $testStatus);
            if ($testStatus != 0) {
                throw new Exception("You require $cmd but it could not be found");
            }
        }


        /* Run wkhtmltoimage */
        $descriptors = array(
            1 => array("pipe", "w"),
            2 => array("pipe", "w"),
        );
        $process = proc_open($command, $descriptors, $fd);
        if (is_resource($process)) {
            /* Read stdout */
            $outputStr = stream_get_contents($fd[1]);
            fclose($fd[1]);
            /* Read stderr */
            $errorStr = stream_get_contents($fd[2]);
            fclose($fd[2]);
            /* Finish up */
            $retval = proc_close($process);
            if ($retval != 0) {
                throw new Exception("Command $cmd failed: $outputStr $errorStr");
            }
        } else {
            throw new Exception("Command '$cmd' failed to start.");
        }

        /* Load up the image */
        try {
            $img = new Escpos\EscposImage($dest);
        } catch (Exception $e) {
            unlink($dest);
            throw $e;
        }
        unlink($dest);

        /* Print it */
        $printer->bitImage($img); // bitImage() seems to allow larger images than graphics() on the TM-T20. bitImageColumnFormat() is another option.
        $printer->cut();
    }
}