<?php
namespace Controller;

use App\DAL\FeedbackService;
use Controller\BaseController;
use App\Models\ErrorResponseModel;
use \Exception;

class FileController extends BaseController {
    private $maxsize = 2048000;
    private $allowed = array("gif", "png", "jpg", "txt", "log","doc", "docx", "xls", "xlsx");
    /**
     * Метод сохранения файла
    */
    public function uploadAction() {
        $size = $_FILES["file"]["size"];
        if($size > $this->maxsize) {
            $errorResponse = new ErrorResponseModel();
            $errorResponse->description = "Отзыв не сохранен размер файла превышает 2 Мб";
            $errorResponse->header = "HTTP/1.1 400";
            $this->sendError($errorResponse);
            exit;
        }
        $filename = $_FILES["file"]["name"];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        if (!in_array($ext, $this->allowed)) {
            $errorResponse = new ErrorResponseModel();
            $errorResponse->description = "Отзыв не сохранен разрешенные расширения (gif, png, jpg, txt, log)";
            $errorResponse->header = "HTTP/1.1 400";
            $this->sendError($errorResponse);
            exit;
        }
        // генерируем уникальное имя файла
        $filename = uniqid() . "." . $ext;
        $location = PROJECT_ROOT_PATH . "Site/uploads/" . $filename;
        if(move_uploaded_file($_FILES["file"]["tmp_name"], $location)) {
            $feedbackService = new FeedbackService();
            $result = $feedbackService->addFileToFeedback($filename);
            $this->sendResult($result);
        } else {
            throw new Exception("Error with file operation");
        } 

    }
    /**
     * Метод выгрузки файла
     */
    public function downLoadAction() {
        $filename = $_GET("file");
        $location = PROJECT_ROOT_PATH . "uploads/" . $filename;
        if(file_exists($location)) {

            //Define header information
            header("Content-Description: File Transfer");
            header("Content-Type: application/octet-stream");
            header("Cache-Control: no-cache, must-revalidate");
            header("Expires: 0");
            header("Content-Disposition: attachment; filename='".basename($location)."'");
            header("Content-Length: " . filesize($location));
            header("Pragma: public");         
            flush();        
            readfile($location);         
            die();

        }}

}