<?php
namespace Arista\AesaBundle\Twig\Extension;
use Symfony\Component\DependencyInjection\ContainerInterface;

class XsltExtension extends \Twig_Extension
{
    private $container;         

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;   
    }        

    public function getContainer()   
    {       
        return $this->container;   
    }

    public function getFilters()
    {
        return array(
            'xsl_transform' => new \Twig_Filter_Method($this, 'xsl_transform', array('is_safe' => array('html'))),       
        );   
    }

    public function xsl_transform($xml_string) {      	
	      $xml_string = "<?xml version='1.0' encoding='iso-8859-1'?>\n".$xml_string;

        $auth = base64_encode($this->container->getParameter('proxy.login').':'.$this->container->getParameter('proxy.password'));

        $opciones = array(
            'http' => array(
                'proxy' => $this->container->getParameter('proxy.host').':'.$this->container->getParameter('proxy.port'),
                'request_fulluri' => true,
                'header' => "Proxy-Authorization: Basic $auth",
            ),
        );

        $contexto = stream_context_create($opciones);
	      
        $xslt_string=file_get_contents($this->container->getParameter('xslt.url'), false, $contexto);  
	      $xslt_string=mb_convert_encoding($xslt_string, 'UTF-8', mb_detect_encoding($xslt_string, 'UTF-8, ISO-8859-1', true));
        $xslt = new \XSLTProcessor();
        $xslt -> importStylesheet(new \SimpleXMLElement($xslt_string));
        return $xslt -> transformToXml(new \SimpleXMLElement($xml_string));   
    }

    public function getName()   
    {       
        return 'xslt';   
    }
}
