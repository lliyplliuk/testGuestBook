<html xmlns="http://www.w3.org/1999/html" lang="RU">
<head>
    <title>Гостевая книга</title>
    <link href="https://getbootstrap.com/docs/5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script defer type="text/javascript" src="/js/board.js"></script>
</head>
<body class="py-4">

<main>
    <div class="container">
        <div class="spinner-border" role="status" id="spinner">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="row row-cols-md-1 text-left" id="main">

        </div>
    </div>
</main>
<div class="modal" tabindex="-1" id="mainModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="btn-close closeModal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input class="form-control" placeholder="Введите имя" id="msgName">
                </div>
                <div class="form-group">
                    <label>Введите сообщение</label>
                    <textarea class="form-control" id="msgText"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary closeModal">Отменить</button>
                <button type="button" class="btn btn-primary" id="saveModal">Сохранить</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>

