<h2>Бан-лист по IP
    <span id="panel-button-operation">
        <button class="button add-ban-ip-rule">+ Добавить</button>
        <button class="button-error delete-ban-ip-rule" id="button-operation-delete">
            Удалить 
            <span id="count-elements-delete"></span>
        </button>
    </span>
</h2>

<link type="text/css" rel="stylesheet" href="<?=SITE_URL?>/js/dataTables/jquery.dataTables_themeroller.css">
<script type="text/javascript" src="<?=SITE_URL?>/js/dataTables/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?=SITE_URL?>/js/dataTables/formatted-numbers.js"></script>

<link rel="stylesheet" href="<?=SITE_URL?>/template/additionalFiles/banIp/banIp.css">
<script type="text/javascript" src="<?=SITE_URL?>/template/additionalFiles/banIp/banIp.js"></script>

<form id="form-ban-ip">  
    <table id="table-list" class="ban-ip-table" border="0" cellspacing="0">
        <thead>
            <tr>
                <td width="20px"> 
                    <div id="box-input-select-all">
                        <input type="checkbox" id="select-all-checkbox">
                        <div class="box-arrow-down"></div>
                    </div> 
                </td>
                <td></td>
                <td>IP</td>
                <td>Причина бана</td>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
</form>

