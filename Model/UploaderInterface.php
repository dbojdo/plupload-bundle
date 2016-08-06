<?php
namespace Webit\Common\PlUploadBundle\Model;

use Symfony\Component\HttpFoundation\Request;

interface UploaderInterface
{
    /**
     * @param Request $request
     * @return FileUploadedEvent
     */
    public function handleUpload(Request $request);
}
