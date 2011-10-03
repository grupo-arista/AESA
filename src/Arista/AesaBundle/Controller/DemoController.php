<?php

namespace Arista\AesaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DemoController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
		$client = new \SoapClient("http://10.67.0.208/ServiciosWebSCE/WSSENASA?WSDL", 
											array('proxy_host'     => "bluecoat.fomento.es",
													'proxy_port'     => 8080,
													'proxy_login'    => "arista.olc",
													'proxy_password' => "arista.olc"));
		$ws_functions = $client->__getFunctions();	
		//$verificar = $client->__soapCall('verificar', array('idcliente' => 'SENASA'));
		$verificar = $client->verificar(array('idcliente' => 'SENASA'));	
        return array("ws_functions" => $ws_functions);
    }
}
