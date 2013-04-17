<?php
namespace Webit\Common\PlUploadBundle\Model;

use Webit\Common\PlUploadBundle\Event\FileUploadedEvent;
use Webit\Common\PlUploadBundle\Event\Events;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Storage\MetadataBag;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

class Uploader implements UploaderInterface {
	/**
	 * @var string
	 */
	protected $uploadPath;
	
	/** 
	 * @var EventDispatcherInterface
	 */
	protected $ed;
	
	public function __construct($uploadPath, EventDispatcherInterface $ed) {
		$this->uploadPath = $uploadPath;
		$this->ed = $ed;	
	}
	
	public function handleUpload(Request $request) {
		$bag = $this->getFilesBag($request);
		$file = $this->getFile($request);
		
		if(!$file) {
			// brak pliku do uploadowania
			// return coś tam
		}
		
		$chunk = (int)$request->request->get('chunk',0);
		$chunks = (int)$request->request->get('chunks',1);
		
		$arFile = $bag->get($file->getClientOriginalName(),array());
		$arFile[$chunk] = $file->getPathname();
		$bag->set($file->getClientOriginalName(),$arFile);
		
		if($chunk == ($chunks - 1)) {
			// koniec uploadu
			
			$arFiles = $bag->get($file->getClientOriginalName());
			
			if(count($arFiles) != $chunks) {
				// niekompletny
				return false;
			}
			
			$file = $this->merge($file->getClientOriginalName(), $arFiles);

			$event = new FileUploadedEvent($file, $request);
			$this->ed->dispatch(Events::EVENT_FILE_UPLOADED, $event);
		} else {
			$event = new FileUploadedEvent($file, $request);
			$this->ed->dispatch(Events::EVENT_CHUNK_UPLOADED, new FileUploadedEvent($file, $request));
		}
		
		return $event->getResponse();
	}
	
	/**
	 * @param array $files
	 * @return \Symfony\Component\HttpFoundation\File\UploadedFile
	 */
	private function merge($clientName, array $files) {
		if(is_dir($this->uploadPath) == false) {
			mkdir($this->uploadPath,0755,true);
		}
		
		$filename = $this->uploadPath .'/'.substr(md5(mt_rand(0,100000).time()),0,6);
		
		$arFile = explode(".", $clientName);
		$ext = end($arFile);
		if($ext) {
			$filename .= '.'.$ext;
		}
		
		foreach($files as $file) {
			file_put_contents($filename, file_get_contents($file), FILE_APPEND);
		}
		
		// $path, $originalName, $mimeType = null, $size = null, $error = null, $test = false
		$file = new UploadedFile($filename, $clientName, $this->getMime($filename), filesize($filename));
		
		return $file;
	}
	
	private function getMime($filename) {
		$finfo = new \finfo(FILEINFO_MIME_TYPE);
		$mimetype = $finfo->file($filename);

		return $mimetype ?: null;
	}
	
	/**
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag
	 */
	private function getFilesBag(Request $request) {
		if($request->getSession()->has('_plupload') == false) {
			$bag = new AttributeBag('_plupload');
			$request->getSession()->set('_plupload', $bag);
		}
		$bag = $request->getSession()->get('_plupload');
		return $bag;
	}
	
	/**
	 * @param Request $request
	 * @return UploadedFile|NULL
	 */
	private function getFile(Request $request) {
		$files = $request->files->all();
		if(count($files) > 0) {
			return array_shift($files);
		}
		
		return null;
	}
}
?>
