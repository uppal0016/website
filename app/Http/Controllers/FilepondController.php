<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;

class FilepondController extends Controller
{
    public function handle_load() {
        $filePath = urldecode(request()->query('load'));
        if (strpos($filePath, 'it_tickets_replies_attachments') !== false) {
            $fileData = Storage::get('public/it_tickets_replies_attachments/' .$filePath);
        } else {
            $fileData = Storage::get('public/it_tickets_attachments/' .$filePath);
        }
        
        $contentType = $this->getContentType($filePath);
      
        return (new Response($fileData, 200))
            ->header('Content-Type', $contentType);
    }

    public function getContentType($filePath) {
        $extension = pathinfo($filePath, PATHINFO_EXTENSION);

        switch ($extension) {
            case 'jpg':
                return 'image/jpg';
            case 'jpeg':
                return 'image/jpeg';
            case 'png':
                return 'image/png';
            case 'gif':
                return 'image/gif';
            case 'pdf':
                return 'application/pdf';
            default:
                return 'application/octet-stream';
        }
    }


}
