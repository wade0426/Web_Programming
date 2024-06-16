<!doctype html>
<html lang="en">

<!-- <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>show_error</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head> -->
<?php
// show_error($error_message)
function show_error()
{
    echo '
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>show_error</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>
<body>
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">錯誤提示訊息</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    錯誤，請先登入!
                </div>
                <div class="modal-footer">
                    <!-- <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">關閉</button> -->
                    <button type="button" class="btn btn-primary" onclick="window.location.href = \'login.php\';">登入</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script>
        // 當網頁的 DOM 元素載入完成後，執行以下程式碼
        window.addEventListener("DOMContentLoaded", function() {
            // 建立一個新的 Modal 物件，並指定要顯示的元素為 "staticBackdrop"
            var modal = new bootstrap.Modal(document.getElementById("staticBackdrop"));
            modal.show();
        });
    </script>
</body>
';
}
?>

<?php
function show_message_danger($message1, $message2, $link)
{
    // 沒有引入bootstrap.css，show_error已經引入了
    echo '
    <body>
    <div class="alert alert-danger" role="alert" style="text-align: center;">
        ' . $message1 . '<a href="' . $link . '" class="alert-link">' . $message2 . '</a>。
    </div>
    </body>
    ';
}
?>

<?php
function show_toasts_success($message1)
{
    echo '
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>show_error</title>
    <link rel="stylesheet" href="CSS/toast.css">
    </head>
    <body>
        <div aria-live="polite" aria-atomic="true" class="bg-dark position-relative bd-example-toasts">
            <!-- 定位元素 -->
            <div class="toast-container p-3 top-0 start-50 translate-middle-x" id="toastPlacement">
                <div id="liveToast" class="toast">
                    <div class="toast-header bg-success text-white">
                        <!-- <img src="..." class="rounded me-2" alt="..."> -->
                        <strong class="me-auto">通知</strong>
                        <small class="">Now</small>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                    <div class="toast-body text-success">
                        ' . $message1 . '
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener(\'DOMContentLoaded\', () => {
                const toast = new bootstrap.Toast(document.getElementById(\'liveToast\'));
                toast.show();
            });
        </script>
        </script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    </body>
    ';
}
?>

</html>