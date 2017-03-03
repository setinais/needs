
		function search(){
			$("#search_menu").append("<input type='text' class='form-control' id='pesquisa_lo' name='search' placeholder='Qual a sua Dor?'/>");
			$("#btn-danger-logon").attr('onClick','procurar("home/search/?search=");');
		}
		function procurar(url){
			var u = $("#pesquisa_lo").val();
			window.location.href = url+u;
		}
		function informacoes(url){
			window.location.href = url;	
		}
