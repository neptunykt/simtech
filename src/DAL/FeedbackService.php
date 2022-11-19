<?php
namespace App\DAL;

use App\DAL\DatabaseService;
use App\Models\DbRequestType;

class FeedbackService extends DatabaseService
{
    /**
     * Метод для добавления фидбека в БД
     * @param mixed $feedback
     * @return mixed Id of Feedback
     */
    public function addFeedback($feedback) {
       
        $date = date("Y-m-d H:i:s");
        $query = "INSERT INTO Feedbacks (CreatedOn, Email, IsAgreed, Sex, Message, FileName) VALUES (?,?,?,?,?,?);";
        return $this->executeQuery($query, "ssiiss", array(
                     $date,$feedback["email"]??"",
                     $feedback["isAgreed"],
                     $feedback["sexOption"], 
                     $feedback["feedbackText"]??"",
                     $feedback["fileName"]??""), 
        DbRequestType::InsertWithReturnId);
    }
     /**
     * Метод для обновления фидбека в БД
     * @param mixed $feedback
     */
    public function updateFeedback($feedback) {
        $query = "UPDATE Feedbacks SET Email=?, IsAgreed=?, Sex=?, Message=? WHERE Id=?";
                    return $this->executeQuery($query,"siisi", 
                        array($feedback["email"],
                        $feedback["isAgreed"], 
                        $feedback["sexOption"],
                        $feedback["feedbackText"],
                        $feedback["id"]), 
                        DbRequestType::Execute);          
        }


    /**
     * Метод для получения фидбеков с пагинацией
     * @param mixed $page
     * @param mixed $pageSize
     * @return array feedbacks
     */
    public function getFeedbacks($page, $pageSize = 5) {
        $paginationStart = ($page-1) * $pageSize;
        $query = "SELECT * FROM Feedbacks ORDER BY CreatedOn LIMIT ? OFFSET ?;";
        return $this->executeQuery($query, "ii", array($pageSize, $paginationStart));
    }

    /**
     * Метод для получения общего числа
     * фидбеков
     * @return mixed count
     */
    public function getFeedbacksCount() {
       return $this->executeQuery("SELECT count(Id) AS Id FROM Feedbacks;","");
    }

    /**
     * Добавление файла
     * в фидбек
     * @param mixed $fileName
     * @param mixed $feedBackId
     */
    public function addFileToFeedback($fileName) {
        $feedback = [];
        $date = date("Y-m-d H:i:s");
        $feedback["fileName"] = $fileName;
        $query = "INSERT INTO Feedbacks (CreatedOn, FileName) VALUES (?,?);";
        $result = $this->executeQuery($query,"ss", array($date,$feedback["fileName"]), DbRequestType::InsertWithReturnId);
        return $result;
    }

    /**
     * Получение одного фидбека по id
     * @param mixed $id
     */
    public function getFeedbackById($id) {
        $query = "SELECT * FROM Feedbacks WHERE Id=?;";
        $result = $this->executeQuery($query,"i", array($id), DbRequestType::Select);
        return $result;
    }

    /**
     * Удаление одного фидбека по id
     * @param mixed $id
     */
    public function deleteFeedbackById($id) {
        $query = "DELETE FROM Feedbacks WHERE Id=?;";
        $this->executeQuery($query,"i", array($id), DbRequestType::Execute);
    }
}