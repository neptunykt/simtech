<?php
define("PROJECT_ROOT_PATH", __DIR__ . "/../../");
include PROJECT_ROOT_PATH . "/vendor/autoload.php";
use App\DAL\FeedbackService;
use App\Models\UserType;
use App\Services\HelperService;
use App\Services\TokenService;


// Начинаем сессию
$userHasRights = true;
$tokenExpired = false;
session_start();
$jwt = $_GET['token'];
$tokenService = new TokenService();
if ((empty($jwt)) && !isset($_SESSION['token'])) {
    $userHasRights = false;
} else if (isset($jwt) && !empty($jwt)) {
    try {
    $roles = $tokenService->decode($jwt);
    }
    catch(Exception $ex) {
        if($ex->getMessage()=='Invalid token: Expired') {
            $tokenExpired = true;
        }
        $userHasRights = false;
    }
    if (empty($roles) || !HelperService::checkUserRole($roles, UserType::Admin)) {
        $userHasRights = false;
    }
    $_SESSION['token'] = $jwt;
} else {
    // проверка сессионного токена на просроченность
    $tokenService = new TokenService();
    try {
    $roles = $tokenService->decode($_SESSION['token']);
    }
    catch(Exception $ex) {
        if($ex->getMessage()=='Invalid token: Expired') {
            $tokenExpired = true;
        }
        $userHasRights = false;
    }
    if (empty($roles) || !HelperService::checkUserRole($roles, UserType::Admin)) {
        $userHasRights = false;
    }
}
if (isset($_POST['pageSize'])) {
    $_SESSION['pageSize'] = $_POST['pageSize'];
}


$pageSize = isset($_SESSION['pageSize']) ? $_SESSION['pageSize'] : 5;
$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
$paginationStart = ($page - 1) * $pageSize;
$feedBackService = new FeedbackService();
$feedbacks = $feedBackService->getFeedbacks($page, $pageSize);
$sqlCount = $feedBackService->getFeedbacksCount();
$allRecords = $sqlCount[0]['Id'];
$totalPages = ceil($allRecords / $pageSize);
// Предыдущий и следующий
$prev = $page - 1;
$next = $page + 1;
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="./script.js"></script>
    <link rel="stylesheet" href="./../Shared/style.css">
    <link rel="stylesheet" href="./../Header/style.css">
    <title>Отзывы</title>
    <style>
        .container {
            max-width: 1000px
        }

        .custom-select {
            max-width: 200px
        }
    </style>

    <script type="text/javascript" src="./../Header/script.js"></script>
</head>

<body>
    <?php
    include __DIR__ . './../Header/index.php';
    ?>
    <?php if($userHasRights) { ?>
    <div class="container mt-5">
        <h2 class="text-center mb-5">Данные с формы обратной связи</h2>
        <!-- Дропдаун -->
        <div class="d-flex flex-row-reverse bd-highlight mb-3">
            <form action="index.php" method="post">
                <select name="pageSize" id="pageSize" class="custom-select">
                    <option disabled selected>Число на странице</option>
                    <?php foreach ([5, 10, 15] as $pageSize) : ?>
                        <option <?php if (isset($_SESSION['pageSize']) && $_SESSION['pageSize'] == $pageSize) echo 'selected'; ?> value="<?= $pageSize; ?>">
                            <?= $pageSize; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>

        <!-- Таблица с данными -->
        <table class="table table-bordered mb-5">
            <thead>
                <tr class="table-success">
                    <th scope="col">#</th>
                    <th scope="col">Создано</th>
                    <th scope="col">Е-майл</th>
                    <th scope="col">Согласие</th>
                    <th scope="col">Пол</th>
                    <th scope="col">Сообщение</th>
                    <th scope="col">Файл</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($feedbacks as $feedback) : ?>
                    <tr>
                        <th scope="row"><?php echo $feedback['Id']; ?></th>
                        <td><?php echo $feedback['CreatedOn']; ?></td>
                        <td><?php echo $feedback['Email']; ?></td>
                        <td><?php echo $feedback['IsAgreed'] == 1 ? 'Да': 'Нет'; ?></td>
                        <td><?php echo $feedback['Sex'] == 1 ? 'Мужской' : 'Женский'; ?></td>
                        <td><?php echo $feedback['Message']; ?></td>
                        <td><a class="download-file" href="#"><?php echo $feedback['FileName']?></a></td>
                        <!--<td><?php // echo $feedback['FileName']; ?></td> -->
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Page navigation example mt-5">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php if ($page <= 1) {
                                            echo 'disabled';
                                        } ?>">
                    <a class="page-link" href="<?php if ($page <= 1) {
                                                    echo '#';
                                                } else {
                                                    echo "?page=" . $prev;
                                                } ?>">Предыдущая</a>
                </li>

                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <li class="page-item <?php if ($page == $i) {
                                                echo 'active';
                                            } ?>">
                        <a class="page-link" href="/Site/Feedbacks/index.php?page=<?= $i; ?>"> <?= $i; ?> </a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?php if ($page >= $totalPages) {
                                            echo 'disabled';
                                        } ?>">
                    <a class="page-link" href="<?php if ($page >= $totalPages) {
                                                    echo '#';
                                                } else {
                                                    echo "?page=" . $next;
                                                } ?>">Следующая</a>
                </li>
            </ul>
        </nav>
    </div>
    <?php } else if($tokenExpired) { ?>
        <div class="container mt-5">
        <h4 class="text-center mb-5">Действие токена истекло. Требуется выйти и снова залогинится</h4>
    </div> 
    <?php } else { ?>
        <div class="container mt-5">
        <h4 class="text-center mb-5">У вас недостаточно прав для просмотра данной страницы</h4>
    </div>
        <?php } ?>
    <?php
    include __DIR__ . '/../Footer/index.html';
    ?>
    <!-- jQuery + Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#pageSize').change(function() {
                $('form').submit();
            })
        });
    </script>
</body>

</html>