<?php
namespace Webit\Common\PlUploadBundle\Model;

class JsonRpcResponse {
	// {"jsonrpc" : "2.0", "result" : null, "id" : "id"}
	/**
	 * @var string
	 */
	protected $jsonrpc = '2.0';

	/**
	 * @var mixed
	 */
	protected $result;

	/**
	 * @var mixed
	 */
	protected $id;

	/**
	 * @var array
	 */
	protected $error;

	/**
	 * @return mixed
	 */
	public function getResult() {
		return $this->result;
	}

	/**
	 * @param mixed $result
	 */
	public function setResult($result) {
		$this->result = $result;
	}

	/**
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param mixed $id
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return array|null
	 */
	public function getError() {
		return $this->error;
	}
	
	/**
	 * @param array $error
	 */
	public function setError(array $error) {
		$this->error = $error;
	}
}
?>
