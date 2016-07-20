<h2>Задания одиночные
    <span id="panel-button-operation">
        <button class="button update-single-call-task-list">Обновить</button>
        <button class="button add-single-call-task">+ Добавить</button>
        <button class="button-error delete-single-call-task" id="button-operation-delete">
            Удалить 
            <span id="count-elements-delete"></span>
        </button>
    </span>
</h2>

<link type="text/css" rel="stylesheet" href="<?=SITE_URL?>/js/dataTables/jquery.dataTables_themeroller.css">
<script type="text/javascript" src="<?=SITE_URL?>/js/dataTables/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/js/dataTables/formatted-numbers.js"></script>
<link rel="stylesheet" href="<?=SITE_URL?>/template/additionalFiles/singleTask/singleTask.css">
<script type="text/javascript" src="<?=SITE_URL?>/template/additionalFiles/singleTask/singleTask.js"></script>

<div class="single-tasks-states-container"> 
        <label>
            <input type="checkbox" class="single-call-task-state-checkbox" value="0" checked>В ожидании<br>
        </label>
        <label>
            <input type="checkbox" class="single-call-task-state-checkbox" value="1" checked>В обработке<br>
        </label>
        <label>
            <input type="checkbox" class="single-call-task-state-checkbox" value="2">Выполненные<br>
        </label>
        <label>
            <input type="checkbox" class="single-call-task-state-checkbox" value="5">Отмененные<br>
        </label>
</div>

<div class="form-single-tasks-container">
    <form id="form-single-tasks">  
        <table id="table-list" class="single-tasks-table" border="0" cellspacing="0">
            <thead>
                <tr>
                    <td width="20px"> 
                        <div id="box-input-select-all">
                            <input type="checkbox" id="select-all-checkbox">
                            <div class="box-arrow-down"></div>
                        </div> 
                    </td>
                    <td></td>
                    <td>Заказ</td>
                    <td></td>
                    <td>Клиент</td>
                    <td>Телефон</td>
                    <td>Дата/время</td>
                    <td>Сотрудник (линия)</td>
                    <td>Приоритет</td>
                    <td>Статус</td>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
        </table>
    </form>
</div>
