<?php
namespace Webit\Common\PlUploadBundle\Event;

use Symfony\Component\EventDispatcher\Event;

use Webit\Common\PlUploadBundle\Model\JsonRpcResponse;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadedEvent extends Event {
	/**
	 * @var UploadedFile
	 */
	protected $file;
	
	/**
	 * @var Request
	 */
	protected $request;
	
	/**
	 * 
	 * @var JsonRpcResponse
	 */
	protected $response;
	
	/**
	 * @param UploadedFile $file
	 * @param Request $request
	 */
	public function __construct(UploadedFile $file, Request $request) {
		//$this->file = $file;
		$this->request = $request;
		
		$this->response = new JsonRpcResponse();
		$this->response->setId(md5($file->getClientOriginalName()));
		$this->response->setResult('OK');
	}
	
	/**
	 * @return \Symfony\Component\HttpFoundation\File\UploadedFile
	 */
	public function getFile() {
		return $this->file;
	}
	
	/**
	 * @return \Symfony\Component\HttpFoundation\Request
	 */
	public function getRequest() {
		return $this->request;
	}
	
	/**
	 * @return JsonRpcResponse
	 */
	public function getResponse() {
		return $this->response;
	}
}
?>
