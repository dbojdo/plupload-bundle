<?php
namespace Webit\Common\PlUploadBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Webit\Common\PlUploadBundle\Model\UploaderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\Controller\Annotations as FOS;
use FOS\RestBundle\Controller\FOSRestController;

/**
 * 
 * @author dbojdo
 * @FOS\NamePrefix("webit_plupload_")
 */
class UploadController extends FOSRestController {
	/**
   * @var UploaderInterface
	 */
	protected $uploader;
	
	public function __construct(UploaderInterface $uploader) {
		$this->uploader = $uploader;
	}
	
	public function postUploadAction() {
		$json = $this->uploader->handleUpload($this->getRequest());
		
		$response = new Response();
		$response->setStatusCode(200,'OK');
		$response->setCharset('utf-8');
		$response->headers->add(array('Content-Type'=>'text/html; charset=utf-8'));
		
		$str = $this->get('serializer')->serialize($json,'json');
		$response->setContent($str);
		
		return $response;
	}
}
?>
