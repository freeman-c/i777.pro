<?php 
	require_once($_SERVER['DOCUMENT_ROOT'].'/template/additionalFiles/singleTask/singleTaskController.php');
	if (!empty($_GET['id'])){
		require_once($_SERVER['DOCUMENT_ROOT'].'/modules/callTask/callTask.php');
		$currentCallTask = getCallTaskForEditing($_GET['id']);
		$currentCallTask['date_time'] = date("d.m.Y H:i", strtotime($currentCallTask['date_time']));
	}
?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.button-save-call-task').click(function() {
			$.ajax({
				url: '/modules/callTask/callTask.php',
				type: 'POST',
				data: {
					operation: 'addCallTask',
					orderId: $('.orderId').val(),
					dateTime: $('.dateTime').val(),
					priority: $('.priority').val(),
					state: 0,
					line: $('.line').val(),
					comment: $('.comment').val()
				},
			})
			.always(function(response) {
				console.log(response);
			});
			
			CloseModal();
			getSingleCallTasks();
			$('.delete-single-call-task').fadeOut();
		});

		//$('.dateTime').mask("9999-99-99 99:99:99");

		$('.dateTime').datetimepicker({
	        format:'d.m.Y H:i',
	        minDate: 0,
	        minTime: 0,
	        onSelectDate: function(){
	            var d = $('.dateTime').datetimepicker('getValue');
	            if (d.getDate() != '<?=date('d')?>')
	                $('.dateTime').datetimepicker({minTime: '00:00:00'});   
	            else
	                $('.dateTime').datetimepicker({minTime: 0});   
	        }
	    });
	    $.datetimepicker.setLocale('ru');

	    $('.clear_datetimepicker').click(function(){
	        $('.dateTime').val('');
	    });

	});
</script>
<form id="forma-single-task">
	<table id="table-list-data" width="100%" cellpadding="0" cellspacing="0" border="0">
		<tr>
			<td class="column-1">
				Заказ №
			</td>
			<td class="column-2">
				<input type="number" class="orderId" value="<?=$currentCallTask['order_id']?>" placeholder="№ заказа">
			</td>
		</tr>
		<tr>
			<td class="column-1">
				Дата/время<br>
				выполнения
			</td>
			<td class="column-2">
                <table cellpadding="0" cellspacing="0" border="0">
                    <tr>
                        <td style="padding: 0;">
                            <input class="dateTime" type="text" value="<?=$currentCallTask['date_time']?>" placeholder="__:__ __.__">
                        </td>
                        <td style="padding: 0 0 0 5px;" valign="middle">   
                            <img class="clear_datetimepicker" src="/image/cancel.png" style=" display: block; width: 18px; cursor: pointer;" title="Очистить время автодозвона">
                        </td>
                    </tr>
                </table>
            </td>
		</tr>
		<tr>
			<td class="column-1">
				Приоритет<br>
				(от 1 до 100)
			</td>
			<td class="column-2">
				<input type="number" class="priority" value="<?=$currentCallTask['priority']?>" placeholder="Приоритет">
			</td>
		</tr>
		<tr>
			<td class="column-1">
				Комментарий
			</td>
			<td class="column-2">
				<textarea class="comment" value="<?=$currentCallTask['comment']?>"><?=$currentCallTask['comment']?></textarea>
			</td>
		</tr>

	</table>
	<input type="hidden" name="id" value="<?=$_GET['id']?>">
</form>

<hr>
<p style="text-align:center;">
    <button class="button button-save-call-task">Сохранить</button>
	<button class="disabled" onclick="CloseModal();">Отмена</button>
</p>    
