<h2>Бан-лист по телефону
    <span id="panel-button-operation">
        <button class="button add-ban-phone-rule">+ Добавить</button>
        <button class="button-error delete-ban-phone-rule" id="button-operation-delete">
            Удалить 
            <span id="count-elements-delete"></span>
        </button>
    </span>
</h2>

<link type="text/css" rel="stylesheet" href="<?=SITE_URL?>/js/dataTables/jquery.dataTables_themeroller.css">
<script type="text/javascript" src="<?=SITE_URL?>/js/dataTables/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/js/dataTables/formatted-numbers.js"></script>
 <link rel="stylesheet" href="<?=SITE_URL?>/template/additionalFiles/banPhone/banPhone.css"> 
<script type="text/javascript" src="<?=SITE_URL?>/template/additionalFiles/banPhone/banPhone.js"></script>

<form id="form-ban-phone">  
    <table id="table-list" class="ban-phone-table" border="0" cellspacing="0">
        <thead>
            <tr>
                <td width="20px"> 
                    <div id="box-input-select-all">
                        <input type="checkbox" id="select-all-checkbox">
                        <div class="box-arrow-down"></div>
                    </div> 
                </td>
                <td></td>
                <td>Телефон</td>
                <td>Причина бана</td>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
</form>

