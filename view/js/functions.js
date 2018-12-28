var icountModalWin = 0;
var id_project;
$.fn.hasAttr = function(name) {
   return this.attr(name) !== undefined;
};
$.fn.exists = function() {
   return $(this).length;
}
function checkNotifications(){
	setInterval(function(){
		$.ajax({
			url:"/diplom/user/checkNotifications",
			data:{'1':'1'},
			datatype:"html",
			type:"post",
			success:function(data){
				result = $.parseJSON(data);
				if(!result['error']){			
					openModal(result['html']);
						Notification.requestPermission(function(permission){
					});
					if (Notification.permission === "granted") {
						var notification = new Notification(result['notific_browser'],
							{ body: 'Хорошего рабочего дня', dir: 'auto' }
						);
					}

					if (("Notification" in window)) {
						if (Notification.permission === "granted") {
							var notification = new Notification(title, options);
						}
						else if (Notification.permission !== 'denied') {
							Notification.requestPermission(function (permission) {
								if (permission === "granted") {
									var notification = new Notification(title, options); 
								}
							});
						}
						
					}


					//$(".icon-notification").children(".count").remove();
					$(".icon-notification").append("<div class='count'>"+result['count']+"</div>");
				}
				//console.log(data);
			}
		})
	},30000);
}
checkNotifications();
function loginInSystem(login,password){
	$.ajax({
		url:"/diplom/auth/login",
		data:{'login':login,'password':password},
		datatype:"html",
		type:"post",
		success:function(data){
			result = $.parseJSON(data);
			if(!result['error']){
				location.reload();
			}
			else{
				//alert(result['error_message']);
				openModal(result['error_message']);
			}
		}
	})
}
function resetpass(form,email){
	$.ajax({
		url:"/diplom/auth/resetpass",
		data:{'email':email},
		datatype:"json",
		type:"post",
		success:function(data){
			result = $.parseJSON(data);
			console.log(result);
			if(!result['error']){
				form.html("<span>На "+email+" пришло письмо с ссылкой на восстановление вашего пароля</span>");
				console.log("/diplom/auth/resetpass?code="+result['code']);
			}
			else{
				openModal(result['error_message']);
			}
		}
	})
}
function setnewpassword(form,password,code){
	$.ajax({
		url: "/diplom/auth/setnewpassword",
		data: {'password':password,'code':code},
		datatype: "json",
		type: "post",
		success:function(data){
			result = $.parseJSON(data);
			if(!result['error']){
				form.html("<span>Ваш новый пароль был установлен.</span><input type='button' onclick=location.href='/diplom/auth' value='Войти'>");
			}
		}
	})
}
function registrationInSystem(form){
	login = form.children("input[name='login']").val();
	password = form.children("input[name='password']").val();
	email = form.children("input[name='email']").val();
	refurl = form.children("input[name='refurl']").val();
	data="login="+login+"&password="+password+"&email="+email+"&refurl="+refurl;
	$.ajax({
		url:"/diplom/auth/registration",
		data:data,
		datatype:"html",
		type:"post",
		success:function(data){
			result = $.parseJSON(data);
			if(!result['error']){
				//location.reload();
				console.log(result);
				openModal("На ваш электронный адрес был выслан ключ активации для данного аккаунта. Введите его в поле активации для дальнейшей работой с системой.")
				form.html("<input type='text' name='key_reg' placeholder='Введите ключ активации'><input type='button' name='send_key_reg' value='активировать ключ'>");
			}
			else{
				openModal(result['error_message']);
			}
		}
	})
}
function activateAccount(key){
	data="key="+key+"&password="+password;
	$.ajax({
		url:"/diplom/auth/activation",
		data:data,
		datatype:"html",
		type:"post",
		success:function(data){
			result = $.parseJSON(data);
			if(!result['error']){
				openModal(result['message']);
				setTimeout(function(){
					location.reload();
				},2000);
			}
			else{
				openModal(result['error_message']);
			}
		}
	})
}
function addproject(form){
	data = form.serialize();
	$.ajax({
		url:"/diplom/projects/addproject",
		type:"post",
		datatype:"html",
		data:data,
		success:function(data){
			result = $.parseJSON(data);
			if(!result['error']){
				openModal(result['message']);
				setTimeout(function(){
					location.reload();
				},2000);
			}
			else{
				openModal(result['error_message']);
			}
		}
	})
}
function addmaintask(form,id_project){
	tasks = new Array();
	tasks_div = $("#tasks-block > input").get().reverse();
	$(tasks_div).each(function(){
		if($(this).val()!=''){
			tasks.push($(this).val());
		}
	})
	if(tasks.length>0){
		data = form.serialize();
		data+= "&id_project="+id_project;
		for(i=0;i<tasks.length;i++){
			data+="&task["+i+"]="+tasks[i];
		}
		$.ajax({
			url:"/diplom/projects/addmaintask",
			type:"post",
			datatype:"html",
			data:data,
			success:function(data){
				result = $.parseJSON(data);
				if(!result['error']){
					openModal(result['message']);
					/*setTimeout(function(){
						page = location.href;
					    $.ajax({
							url:page,
							type:"post",
							datatype:"html",
							data:{'1':'1'},
							success:function(data){
								$("#content-page").html(data);
							}
						})
					},500);*/
					setTimeout(function(){
						location.reload();
					},2000);
					
				}
				else{
					openModal(result['error_message']);
				}
				//console.log(data);

			}
		})	
	}
	else{
		openModal("Добавьте минимум 1 мини-задачу");
	}
	//console.log(data);
}
function openModal(data,type=null,param=null){
	html = "";
	html = "<div class='wrap'><div class='modalwin'>";
	if(type==1) html += "<div class='modal-top' required>";
	else html += "<div class='modal-top'>";
	html += "<div class='modal-close'></div></div><div class='modal-content-body'>"+data+"</div></div></div>";

	$("body").append(html);	
	setTimeout(function(){
		$(".modalwin").addClass("open-modal");
	},50); 
}
function closeModal(_this=null){
	modalwin = $(".modalwin");
	wrap = $(".wrap");
	if(_this!=null){
		modalwin = _this.parent(".modalwin");
		wrap = _this.parent().parent(".wrap");
	}
	modalwin.removeClass("open-modal");
	setTimeout(function(){
		modalwin.remove();
		wrap.remove();
	},300); 
}
function setLocation(curLoc){
    try {
      history.pushState(null, null, curLoc);
      return;
    } catch(e) {}
    location.hash = '#' + curLoc;
}
window.addEventListener('popstate', function(e) {
    //console.log(location.href);	
    page = location.href;
    $.ajax({
    	
		url:page,
		type:"post",
		datatype:"html",
		data:{'1':'1'},
		success:function(data){
			$("#content-page").html(data);
			//setLocation(page);
		}
	})
});


$(document).ready(function(){
	new WOW().init();
	$("#reg-tab-item").click(function(){
		$(this).removeClass("notview");
		$("#login-tab-item").addClass("notview");
		$("section#login").removeClass("show").addClass("hide");
		$("section#registration").removeClass("hide").addClass("show");
	})
	$("#login-tab-item").click(function(){
		$(this).removeClass("notview");
		$("#reg-tab-item").addClass("notview");
		$("section#login").removeClass("hide").addClass("show");
		$("section#registration").removeClass("show").addClass("hide");
	})
	$("form[name='form-login']>input[name='btn-login']").click(function(){
		form = $(this).parent();
		login = form.children("input[name='login']").val();
		password = form.children("input[name='password']").val();
		loginInSystem(login,password);
		//console.log(login+" "+password);
	})
	$("form[name='form-login']>input[name='btn-resetpass']").click(function(){
		form = $(this).parent();
		email = form.children("input[name='email']").val();
		resetpass(form,email);
	})
	$("form[name='form-exit']>input[name='btn-exit']").click(function(){
		exitWithSystem();
	})
	$("form[name='form-registration']>input[name='btn-registration']").click(function(){
		form = $(this).parent();
		
		registrationInSystem(form);
	})
	$("form[name='form-login']>input[name='btn-resetpass_new_pass']").click(function(){
		form = $(this).parent();
		password = form.children("input[name='password']").val();
		code = form.children("input[name='code']").val();
		setnewpassword(form,password,code);
	})
	
	$("body").on('click',"form[name='form-registration']>input[name='send_key_reg']",function(){
		key = $(this).parent().children("input[name='key_reg']").val();

		activateAccount(key);
	}).on('click',"form[name='form-addproject']>input[name='btn-addproject']",function(){
		addproject($(this).parent());
	}).on('click',"form[name='form-addmaintask']>input[name='btn-addmaintask']",function(){
		id_project = $(this).attr("data-project");
		addmaintask($(this).parent(),id_project);
	}).on('click','.modal-top',function(){
		if($(this).hasAttr("required")){
			ans = confirm("Вы уверены, что хотите закрыть?");
			if(ans){
				closeModal($(this));
			}
		}
		else{
			closeModal($(this));
		}
		
	}).on('click','a',function(e){
		e.preventDefault();
		page = $(this).attr("href");
		$.ajax({
			xhr: function(){
			    var xhr = new window.XMLHttpRequest();
			    //Upload progress
			    xhr.upload.addEventListener("progress", function(evt){
			      if (evt.lengthComputable) {
			        var percentComplete = evt.loaded / evt.total;
			        //Do something with upload progress
			        console.log("up", percentComplete);
			      }
			    }, false);
			    //Download progress
			    xhr.addEventListener("progress", function(evt){
			      if (evt.lengthComputable) {
			        var percentComplete = evt.loaded / evt.total;
			        //Do something with download progress
			        console.log("down", evt.loaded / 600000);
			      }
			    }, false);
			    return xhr;
			},
			url:page,
			type:"post",
			datatype:"html",
			data:{'1':'1'},
			success:function(data){
				$("#content-page").html(data);
				setLocation(page);
			}
		})
	}).on('click','.add-maintask',function(){
		id_project = $(this).attr("data-project");
		$.ajax({
			url:"/diplom/modal/loadmodal",
			type:"post",
			datatype:"html",
			data:{'modal':'addmaintask','id_project':id_project},
			success:function(data){
				result = $.parseJSON(data);
				if(!result['error']){
					openModal(result['html'],1,id_project); 
				}
				else{
					openModal(result['error_message']); 
				}
				
			}
		})
	}).on('click','.add-project',function(){
		$.ajax({
			url:"/diplom/modal/loadmodal",
			type:"post",
			datatype:"html",
			data:{'modal':'addproject'},
			success:function(data){
				openModal(data,1);
			}
		})
	}).on('keypress',"input[name='search']",function(e){
		if(e.which==13){
			e.preventDefault();
			request = $(this).val();
			$(this).parent().find("input[name='search_type']").each(function(){
				if($(this).prop("checked")) type = $(this).val();
			})
			page = "/diplom/search?search_type="+type+"&request="+request;
			$.ajax({
				url:page,
				type:"post",
				datatype:"html",
				data:{'1':'1'},
				success:function(data){
					$("#content-page").html(data);
					setLocation(page);
				}
			})
			return false;
		}
	}).on('click','.add-to-contact',function(){
		id_user_contact = $(this).attr("data-user");
		_this = $(this);
		$.ajax({
			url:"/diplom/user/addToContact",
			datatype:"html",
			type:"post",
			data:{'id_user_contact':id_user_contact},
			success:function(data){
				result = $.parseJSON(data);
				if(!result['error']){
					_this.attr("disabled","").text("Добавлен").removeAttr("data-user");
					openModal(result['message']);
				}
				else{
					openModal(result['error_message']);
				}
			}
		})
	}).on('focus',".search-panel input[name='search']",function(){
		$(this).addClass("open");
		$("#prefilter").addClass("open");
	}).on('click',".task",function(){
		id_task = $(this).attr("data-task");
		
		$.ajax({
			url:"/diplom/modal/loadmodal",
			type:"post",
			datatype:"html",
			data:{'id_task':id_task,'modal':'viewtask'},
			success:function(data){
				//console.log(data);
				result = $.parseJSON(data);
				if(!result['error']){
					openModal(result['html']); 
				}
				else{
					openModal(result['error_message']); 
				}
			}
		})
	}).on('click',".deletefromcontacts",function(){
		id_user = $(this).attr("data-user");
		login = $(this).siblings("span").children("a").text();
		ans = confirm("Подтверждаете действие удаления контакта "+login+"?");
		if(ans){
		//console.log(id_user);
			$.ajax({
				url:"/diplom/user/deletefromcontacts",
				data:{'id_user':id_user},
				datatype:"html",
				type:"post",
				success:function(data){
					result = $.parseJSON(data);
					if(!result['error']){
						openModal(result['message']);
						$("div[data-user='"+id_user+"']").remove();
						$(".count_contacts").text(result['count']);
					}
					else{
						openModal(result['error_message']);
					}
				}
			})
		}
	}).on('click',"input[name='accept_invite']",function(){
		id_notification = $(this).attr("data-n");
		modal = $(this).attr("data-win");
		_this = $(this);
		$.ajax({
			url:"/diplom/user/acceptedinvite",
			datatype:"html",
			type:"post",
			data:{'id_notification':id_notification},
			success:function(data){
				result = $.parseJSON(data);
				if(!result['error']){
					openModal(result['message']);
					if(!$(".count").exists()==0){
						$(".icon_notification").append("<div class='count'></div>");
					}
					$(".icon-notification > .count").text(result['count']);
					if(modal!=null){
						setTimeout(function(){
							closeModal();
						},2000);
					}
					else{
						$("form[data-n='"+id_notification+"']").remove();
					}
					
				}
				else{
					closeModal();
					openModal(result['error_message']);
				}
			}
		})
	}).on('click',"input[name='cancel_invite']",function(){
		id_notification = $(this).attr("data-n");
		modal = $(this).attr("data-win");
		_this = $(this);
		$.ajax({
			url:"/diplom/user/canceledinvite",
			datatype:"html",
			type:"post",
			data:{'id_notification':id_notification},
			success:function(data){
				//console.log(data);
				result = $.parseJSON(data);
				if(!result['error']){
					openModal(result['message']);
					if(!$(".count").exists()){
						$(".icon_notification").append("<div class='count'></div>");
					}
					$(".icon-notification > .count").text(result['count']);
					if(modal!=null){
						setTimeout(function(){
							closeModal();
						},2000);
					}
					else{
						$("form[data-n='"+id_notification+"']").remove();
					}
					
				}
				else{
					closeModal();
					openModal(result['error_message']);
				}
			}
		})
	}).on('click',".selectanotheruser",function(){
		$id_task = $(this).attr("data-task");
		$.ajax({
			url: "/diplom/modal/loadmodal",
			type: "post",
			datatype: "html",
			data:{'id_task':id_task,'modal':'selectanotheruser'},
			success:function(data){
				result = $.parseJSON(data);
				if(!result['error']){
					openModal(result['html']);
				}
				else{
					openModal(result['error_message']);
				}
			}
		})
	}).on('click',"#createrefurl",function(){
		$.ajax({
			url:"/diplom/user/createrefurl",
			datatype:"html",
			type:"post",
			data:{'1':'1'},
			success:function(data){
				result = $.parseJSON(data);
				if(!result['error']){
					
					openModal(result['message']);
					setTimeout(function(){
						location.reload();
					},2000);	
				}
				else{
					openModal(result['error_message']);
				}
			}
		})
	}).on('click',"input[name='addminitask']",function(){
		$("#tasks-block").append("<input type='text'><button class='deleteminitask'>Удалить</button><button class='saveminitask'>Сохранить</button><br>");
	}).on('click',"#deletetask",function(){
		id_task = $(this).attr("data-task");
		$.ajax({
			url:"/diplom/projects/deletetask",
			type:"post",
			datatype:"html",
			data:{'id_task':id_task},
			success:function(data){
				result = $.parseJSON(data);
				if(!result['error']){
					openModal(result['message']);
					$(".task[data-task='"+id_task+"']").remove();
					setTimeout(function(){
						page = location.href;
						$.ajax({
							url:page,
							type:"post",
							datatype:"html",
							data:{'1':'1'},
							success:function(data){
								$("#content-page").html(data);
								//setLocation(page);
							}
						})
					},500);
				}
				//console.log(data);
			}
		})
	}).on('click',"#completetask",function(){
		id_task = $(this).attr("data-task");
		$.ajax({
			url:"/diplom/projects/completetask",
			type:"post",
			datatype:"html",
			data:{'id_task':id_task},
			success:function(data){
				result = $.parseJSON(data);
				if(!result['error']){
					openModal(result['message']);
					setTimeout(function(){
						page = location.href;
						$.ajax({
							url:page,
							type:"post",
							datatype:"html",
							data:{'1':'1'},
							success:function(data){
								$("#content-page").html(data);
								//setLocation(page);
							}
						})
					},500);
				}
				//console.log(data);
			}
		})
	}).on('click','.deleteminitask',function(){
		id_work = $(this).attr("data-minitask");
		id_task = $(".main-task").attr("data-task");
		_this = $(this).parent();
		$.ajax({
			url:"/diplom/projects/deleteminitask",
			type:"post",
			datatype:"html",
			data:{'id_minitask':id_work,'id_task':id_task},
			success:function(data){
				result = $.parseJSON(data);
				if(!result['error']){
					openModal(result['message']);
					_this.remove();
					setTimeout(function(){
						page = location.href;
						$.ajax({
							url:page,
							type:"post",
							datatype:"html",
							data:{'1':'1'},
							success:function(data){
								$("#content-page").html(data);
								//setLocation(page);
							}
						})
					},500);
				}
				else{
					openModal(result['error_message']);
				}
				//console.log(data);
			}
		})
	}).on('click','.completeminitask',function(){
		id_work = $(this).attr("data-minitask");
		id_task = $(".main-task").attr("data-task");
		_this = $(this);
		$.ajax({
			url:"/diplom/projects/completeminitask",
			type:"post",
			datatype:"html",
			data:{'id_minitask':id_work,'id_task':id_task},
			success:function(data){
				result = $.parseJSON(data);
				if(!result['error']){
					openModal(result['message']);
					_this.remove();
					setTimeout(function(){
						page = location.href;
						$.ajax({
							url:page,
							type:"post",
							datatype:"html",
							data:{'1':'1'},
							success:function(data){
								$("#content-page").html(data);
								//setLocation(page);
							}
						})
					},500);
				}
				else{
					openModal(result['error_message']);
				}
				//console.log(data);
			}
		})
	}).on('click','.resetminitask',function(){
		id_work = $(this).attr("data-minitask");
		id_task = $(".main-task").attr("data-task");
		_this = $(this);
		$.ajax({
			url:"/diplom/projects/resetminitask",
			type:"post",
			datatype:"html",
			data:{'id_minitask':id_work},
			success:function(data){
				result = $.parseJSON(data);
				if(!result['error']){
					openModal(result['message']);
					_this.remove();
					setTimeout(function(){
						page = location.href;
						$.ajax({
							url:page,
							type:"post",
							datatype:"html",
							data:{'1':'1'},
							success:function(data){
								$("#content-page").html(data);
								//setLocation(page);
							}
						})
					},500);
				}
				else{
					openModal(result['error_message']);
				}
				//console.log(data);
			}
		})
	}).on('click','.edit-data',function(){
		//data_item = $(this).siblings(".info-user-data").attr("data-item");
		text = $(this).siblings(".info-user-data").text();
		$(this).siblings(".info-user-data").html("<input type='text' value='"+text+"'>").attr("old-text",text);
		$(this).text("сохранить").removeClass("edit-data").addClass("save-data");
		$(this).parent().append("<span class='cancel-edit-data'>отмена</span>");
		$("span[data-item='bday'] > input").datepicker({ dateFormat: 'yy-mm-dd' }).val(); 
	}).on('click','.cancel-edit-data',function(){
		old_text = $(this).siblings(".info-user-data").attr("old-text");
		$(this).siblings(".info-user-data").html("").text(old_text);
		$(this).siblings(".save-data").addClass("edit-data").removeClass("save-data").text("редактировать");
		$(this).remove();
	}).on('click','.save-data',function(){
		text = $(this).siblings(".info-user-data").children("input").val();
		data_item = $(this).siblings(".info-user-data").attr("data-item");
		_this = $(this);
		$.ajax({
			url:"/diplom/user/editinfo",
			type:"post",
			datatype:"html",
			data:{'item':data_item,'text':text},
			success:function(data){
				result = $.parseJSON(data);
				if(!result['error']){
					_this.siblings(".info-user-data").html("").text(text);
					_this.addClass("edit-data").removeClass("save-data").text("редактировать");
					_this.siblings(".cancel-edit-data").remove();
				}
				else{
					openModal(result['error_message']);
				}
			}
		})
	}).on('click','.delete-position',function(){
		ans = confirm("Are you sure?");
		if(ans){
			_this = $(this);
			id_position = _this.parent().attr("data-position");

			$.ajax({
				url:"/diplom/user/deleteuserposition",
				type:"post",
				datatype:"html",
				data:{'id_position':id_position},
				success:function(data){
					result = $.parseJSON(data);
					if(!result['error']){
						_this.parent().remove();
					}
					else{
						openModal(result['error_message']);
					}
				}
			})
		}		
	}).on('click','#add-positions-btn',function(){
		$.ajax({
			url:"/diplom/user/getAllListPositionsForUser",
			datatype:"html",
			type:"post",
			data:{'1':'1'},
			success:function(data){
				result = $.parseJSON(data);
				if(!result['error']){
					$("#positions-area > form").html("");
					for(i=0;i<result['allpositions'].length;i++){
						if(result['allpositions'][i]['id_parent']== "0"){
							$("#positions-area > form").append("<span>"+result['allpositions'][i]['name']+"</span><br>");
							for(j=0;j<result['allpositions'].length;j++){
								console.log(result['allpositions'][j]['id']+" "+result['allpositions'][i]['id_parent']);
								if(result['allpositions'][j]['id_parent'] == result['allpositions'][i]['id']){
									if(!result['allpositions'][j]['equal']){
										$("#positions-area > form").append("<input type='checkbox' name='position-item' value='"+result['allpositions'][j]['id']+"'><span>"+result['allpositions'][j]['name']+"</span><br>");
									}
								}
							}
						}					
					}
					$("#positions-area").append("<input type='button' class='action-btn' id='add-selected-position' value='Добавить'>");
				}
				else{
					openModal(result['error_message']);
				}
			}
		})
	}).on('click','#add-selected-position',function(){
		list_positions = new Array();
		$("form[name='positions']").find("input[type='checkbox']").each(function(){
			if($(this).is(":checked")){
				list_positions.push($(this).val());
			}
		})
		if(list_positions.length>0){
			$.ajax({
				url:"/diplom/user/addUserPosition",
				type:"post",
				datatype:"html",
				data:{'list_positions':list_positions},
				success:function(data){
					result = $.parseJSON(data);
					if(!result['error']){
						$("#positions-area > form").html("");
						openModal("Специальность добавлена");
						setTimeout(function(){
							location.reload();
						},700);
					}
					else{
						openModal(result['error_message']);
					}
				}
			})
		}
		else{
			openModal("Position not selected");
		}
	}).on('click',"input[name='save_settings']",function(){
		search_user = $("input[name='search_user']").prop("checked");
		view_statistics = $("input[name='view_statistics']").prop("checked");
		send_invite = $("input[name='send_invite']").prop("checked");
		hidden_profile = $("input[name='hidden_profile']").prop("checked");
		mail_invite = $("input[name='mail_invite']").prop("checked");
		mail_new_ads = $("input[name='mail_new_ads']").prop("checked");
		$.ajax({
			url:"/diplom/user/editsettings",
			type:"post",
			datatype:"html",
			data:{
				'search_user':search_user,
				'view_statistics':view_statistics,
				'send_invite':send_invite,
				'hidden_profile':hidden_profile,
				'mail_invite':mail_invite,
				'mail_new_ads':mail_new_ads
			},
			success:function(data){
				result = $.parseJSON(data);
				if(!result['error']){
					openModal("Настройки сохранены");
				}
				else{
					openModal(result['error_message']);
				}
			}
		})
	}).on('click',"input[name='btn-addusers']",function(){
		id_project = $(this).attr("data-project");
		$.ajax({
			url:"/diplom/projects/viewcontactlist",
			datatype:"html",
			type:"post",
			data:{'id_project':id_project},
			success:function(data){
				openModal(data);
				//console.log("1");
			}
		})
	}).on('click',"input[name='select_users']",function(){
		id_project = $(this).attr("data-project");
		users_id = new Array();
		$("form[name='select_users_form']").find("input[type='checkbox']").each(function(){
			if($(this).is(":checked")){
				users_id.push($(this).val());
			}
		})
		if(users_id.length>0){
			$.ajax({
				url:"/diplom/projects/viewselectedusers",
				type:"post",
				datatype:"html",
				data:{'users_id':users_id,'id_project':id_project},
				success:function(data){
					closeModal();
					setTimeout(function(){
						openModal(data);
					},700);	
				}
			})
		}
		else{
			openModal("<span>Выберите пользователей</span>");
		}
	}).on('click','#add_users',function(){
		id_project = $(this).attr("data-project");
		selected_users = new Array();
		$(".selected_block").find(".selected_item").each(function(){
			id_user = $(this).attr("data-user");
			id_position = $(this).children("select[name='select_position']").val();
			selected_users.push({'id_user':id_user,'id_position':id_position});
		})
		console.log(selected_users);
		$.ajax({
			url:"/diplom/projects/adduserstoprojects",
			datatype:"html",
			type:"post",
			data:{'selected_users':selected_users,'id_project':id_project},
			success:function(data){
				result = $.parseJSON(data);
				if(!result['error']){
					closeModal();
					setTimeout(function(){
						openModal(result['message']);
						page = location.href;
						$.ajax({
							url:page,
							type:"post",
							datatype:"html",
							data:{'1':'1'},
							success:function(data){
								$("#content-page").html(data);
								setLocation(page);
							}
						})
					},700);
				}
				else{
					closeModal();
					setTimeout(function(){
						openModal(result['error_message']);
					},700);
				}
				console.log(data);
			}
		})
	}).on('click',"input[name='btn-start']",function(){
		id_project = $(this).attr("data-project");
		$.ajax({
			url:"/diplom/projects/startproject",
			type:"post",
			datatype:"html",
			data:{'id_project':id_project},
			success:function(data){
				result = $.parseJSON(data);
				if(!result['error']){
					openModal(result['message']);
					setTimeout(function(){
						page = location.href;
						$.ajax({
							url:page,
							type:"post",
							datatype:"html",
							data:{'1':'1'},
							success:function(data){
								$("#content-page").html(data);
								setLocation(page);
							}
						})
					},500);
					
				}
				else{
					openModal(result['error_message']);
				}
				
			}
		})
	}).on('click',"input[name='btn-stopproject']",function(){
		id_project = $(this).attr("data-project");
		$.ajax({
			url:"/diplom/projects/stopproject",
			type:"post",
			datatype:"html",
			data:{'id_project':id_project},
			success:function(data){
				result = $.parseJSON(data);
				if(!result['error']){
					openModal(result['message']);
					setTimeout(function(){
						page = location.href;
						$.ajax({
							url:page,
							type:"post",
							datatype:"html",
							data:{'1':'1'},
							success:function(data){
								$("#content-page").html(data);
								setLocation(page);
							}
						})
					},500);
				}
				else{
					openModal(result['error_message']);
				}
				//console.log(data);
				
			}
		})
	}).on('click',".deleteuserfromproject",function(){
		id_project = $(".main").attr("data-project");
		id_user = $(this).attr("data-user");
		$.ajax({
			url:"/diplom/projects/deleteuserfromproject",
			type:"post",
			datatype:"html",
			data:{'id_project':id_project,'id_user':id_user},
			success:function(data){
				result = $.parseJSON(data);
				if(!result['error']){
					openModal(result['message']);
					//$("div[data-user='"+id_user+"']").remove();	
					setTimeout(function(){
						page = location.href;
						$.ajax({
							url:page,
							type:"post",
							datatype:"html",
							data:{'1':'1'},
							success:function(data){
								$("#content-page").html(data);
								setLocation(page);
							}
						})
					},500);				
				}
				else{
					openModal(result['error_message']);
				}
			}
		})
	}).on('click','.inviteuser',function(){
		id_user = $(this).attr("data-user");
		id_project = $(".main").attr("data-project");
		$.ajax({
			url:"/diplom/projects/invivecanceleduser",
			type:"post",
			datatype:"html",
			data:{'id_project':id_project,'id_user':id_user},
			success:function(data){
				result = $.parseJSON(data);
				if(!result['error']){
					openModal(result['message']);
					setTimeout(function(){
						page = location.href;
						$.ajax({
							url:page,
							type:"post",
							datatype:"html",
							data:{'1':'1'},
							success:function(data){
								$("#content-page").html(data);
								setLocation(page);
							}
						})
					},500);
					
				}
				else{
					openModal(result['error_message']);
				}
				//console.log(data);
			}
		})
	}).on('click','.resetuser',function(){
		id_user = $(this).parent().attr("data-user");
		id_project = $(".main").attr("data-project");
		$.ajax({
			url:"/diplom/projects/resetuser",
			type:"post",
			datatype:"html",
			data:{'id_project':id_project,'id_user':id_user},
			success:function(data){
				result = $.parseJSON(data);
				if(!result['error']){
					openModal(result['message']);
					setTimeout(function(){
						page = location.href;
						$.ajax({
							url:page,
							type:"post",
							datatype:"html",
							data:{'1':'1'},
							success:function(data){
								$("#content-page").html(data);
								setLocation(page);
							}
						})
					},500);
					
				}
				else{
					openModal(result['error_message']);
				}
				//console.log(data);
			}
		})
	}).on('click','.saveanotheruser',function(){
		user = null;
		id_task = $(this).attr("data-task");
		$(this).parent().find("input[name='selectuser']").each(function(){
			if($(this).prop("checked")) user = $(this).val();
		})
		if(user!=null){
			$.ajax({
				url: "/diplom/projects/saveanotheruser",
				data: {'id_task':id_task,'id_user':user},
				datatype:"html",
				type:"post",
				success:function(data){
					result = $.parseJSON(data);
					if(!result['error']){
						closeModal($(".block-anotheruser"));
						openModal(result['message']);
					}
					else{
						closeModal($(".block-anotheruser"));
						openModal(result['error_message']);
					}
				}
			})
		}
		//console.log(user);
	}).on('click','.question',function(){
		question = $(this).attr("data-question");
		$.ajax({
			url:"/diplom/modal/loadmodal",
			datatype:"html",
			type:"post",
			data:{'modal':'question','question_type':question},
			success:function(data){
				result = $.parseJSON(data);
				openModal(result['html']);
			}
		})
	})
	$(document).click(function(e){
		var target = e.target;
	    if (!$(target).is('.search-panel') && !$(target).parents().is('.search-panel')) {	    
			$(".search-panel input[name='search']").removeClass("open");
			$("#prefilter").removeClass("open");
		}
	})
	$(".icon-user").click(function(){
		if($(this).attr("data-sub-open")=="true"){
			$(this).children(".sub-win").removeClass("open");
			$(this).attr("data-sub-open","false");
		}
		else{
			$(this).children(".sub-win").addClass("open");
			$(this).attr("data-sub-open","true");
		}
		
	})
	$(".icon-notification").click(function(){
		if($(this).attr("data-sub-open")=="true"){
			$(this).children(".sub-win").removeClass("open");
			$(this).attr("data-sub-open","false");
		}
		else{
			$(this).children(".sub-win").addClass("open");
			$(this).attr("data-sub-open","true");
			$.ajax({
				url:"/diplom/user/getallnotifications",
				type:"post",
				datatype:"html",
				data:{'1':'1'},
				success:function(data){
					$("#content-sub-win").html(data);					
				}
			})
		}
	})
	$("#btn-exit").click(function(){
		$.ajax({
			url:"/diplom/auth/authexit",
			type:"post",
			datatype:"html",
			data:{'1':'1'},
			success:function(data){
					location.reload();
				
			}
		})
	})

	


})

