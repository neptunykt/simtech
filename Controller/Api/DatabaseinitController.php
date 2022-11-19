<?php
namespace Controller;

use App\DAL\DatabaseInitService;
use Controller\BaseController;
use \Exception;
/**
 * @OA\Info(title="Simtech company", version="1.0")
 */
class DatabaseinitController extends BaseController
{
    /**
     * @OA\Get(
     *  path="/api/databaseinit/createTables",
     *  @OA\Response(response="204", description="Script for create tables in database")
     * )
     */
    public function createTablesAction()
    {
        $databaseInitService = new DatabaseInitService();
        try {
            $databaseInitService->createTables();
            $this->sendOk();
        } catch (Exception $ex) {
            throw ($ex);
        }
    }
}
