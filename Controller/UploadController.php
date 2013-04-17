<?php
use Symfony\Component\HttpFoundation\Response;

namespace Webit\Common\PlUploadBundle\Controller;

use Webit\Common\PlUploadBundle\Model\UploaderInterface;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UploadController extends Controller {
	/**
   * @var UploaderInterface
	 */
	protected $uploader;
	
	public function __construct(UploaderInterface $uploader) {
		$this->uploader = $uploader;
	}
	
	public function uploadAction() {
		$event = $this->uploader->handleUpload($this->getRequest());
		
		$arFiles = $this->getRequest()->files->all();
		$uploadedFile = count($arFiles) > 0 ? array_shift($arFiles) : null;
		
		$json= $event->getResponse();
		$response = new Response();
		$response->setCharset('utf-8');
		$response->headers->add(array('Content-Type'=>'text/html; charset=utf-8'));
		$response->setContent($this->get('serializer')->serialize($response,'json'));
		
		return $response;
	}
}
?>
