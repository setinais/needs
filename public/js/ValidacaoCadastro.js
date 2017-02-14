var n=0,s=0,u=0,se=0,t=0,c=0;
function onSignIn(googleUser) {
        // Useful data for your client-side scripts:
        var profile = googleUser.getBasicProfile();
        //console.log("ID: " + profile.getId()); // Don't send this directly to your server!
        //console.log('Full Name: ' + profile.getName());
        $("#nome").attr("value", profile.getGivenName());
        $("#sobrenome").attr("value", profile.getFamilyName());
        //console.log("Image URL: " + profile.getImageUrl());
        $("#exampleInputEmail1").attr("value", profile.getEmail());
        $("#usuario").attr('value', profile.getId());
        $("#senha").attr('value', profile.getId());
        $("#confirsenha").attr('value', profile.getId());
        $("#dados_logon").hide('slow/400/fast');
        c = 10;
        se = 10;
        u = 20;
        validaNome(profile.getGivenName());
        validaSobre(profile.getFamilyName());
        // The ID token you need to pass to your backend:
        var id_token = googleUser.getAuthResponse().id_token;
        console.log(googleUser.getAuthResponse());
        //console.log("ID Token: " + id_token);
       
      };
      // Id da Api do google = 1009135910247-k8693mh4cljccnju8nd2q21lpn3mu5i0.apps.googleusercontent.com
      // Chava secreta do cliente = aI30B2or5jFT5OaMoJYEqOqm
function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
    	$("#nome").attr("value", "");
        $("#sobrenome").attr("value", "");
        //console.log("Image URL: " + profile.getImageUrl());
        $("#usuario").attr('value', "");
        $("#exampleInputEmail1").attr("value", "");
        $("#senha").attr('value', "");
        $("#confirsenha").attr('value', "");
        $("#dados_logon").show('slow/400/fast');
    	c=0;
    	se=0;
    	u=0;
    	validaNome("");
        validaSobre("");
        auth2.disconnect();
    });
  }


 function validacao(){
	var width = n+s+u+se+t+c;
	$("#progress-b").attr({
		'style' : 'width: '+width+'%;min-width: 2em;',
		'aria-valuenow' : width
	});
	$("#progress-b").html(width+'%');
	if(width == 100){
		document.getElementById('btn_cad').removeAttribute('disabled');
	}else{
		$("#btn_cad").attr('disabled', 'disabled');
	}
}
function validarSenha(){
	s1 = $("#senha").val();
	s2 = $("#confirsenha").val();
	
	if(s1 == s2){
		$("#valida_confirmar-senha").attr('class', 'col-md-4 has-success');
		$("#valida_confirmar-senha_icon").attr('class','form-control-feedback glyphicon glyphicon-ok');
		c = 10;
		validacao();
	}else{
		$("#valida_confirmar-senha").attr('class', 'col-md-4 has-error');
		$("#valida_confirmar-senha_icon").attr('class','form-control-feedback glyphicon glyphicon-remove');
		c = 0;
		validacao();
	}
}

function validaNome(nome){
	//var nome = $("#")
	if(nome != ""){
		$("#valida_nome").attr('class', 'col-md-4 has-success');
		$("#valida_nome_icon").attr('class','form-control-feedback glyphicon glyphicon-ok');
		n = 20;
		validacao();
		return true;
	}else{
		$("#valida_nome").attr('class', 'col-md-4 has-error');
		$("#valida_nome_icon").attr('class','form-control-feedback glyphicon glyphicon-remove');
		n = 0;
		validacao();
		return false;
	}
}
function validaSobre(nome){
	
	if(nome != ""){
		$("#valida_sobre").attr('class', 'col-md-8 has-success');
		$("#valida_sobre_icon").attr('class','form-control-feedback glyphicon glyphicon-ok');
		s = 20;
		validacao();
		return true;
	}else{
		$("#valida_sobre").attr('class', 'col-md-8 has-error');
		$("#valida_sobre_icon").attr('class','form-control-feedback glyphicon glyphicon-remove');
		s = 0;
		validacao();
		return false;
	}
}
function validaUsu(nome){
	
	if(nome != ""){
		$("#valida_usu").attr('class', 'col-md-3 has-success');
		$("#valida_usu_icon").attr('class','form-control-feedback glyphicon glyphicon-ok');
		
		u = 20;
		validacao();
		return true;
	}else{
		$("#valida_usu").attr('class', 'col-md-3 has-error');
		$("#valida_usu_icon").attr('class','form-control-feedback glyphicon glyphicon-remove');
		
		u = 0;
		validacao();
		return false;
	}
}
function validaSenha(nome){
	
	if(nome != ""){
		$("#valida_senha").attr('class', 'col-md-3 has-success');
		$("#valida_senha_icon").attr('class','form-control-feedback glyphicon glyphicon-ok');
		
		se = 10;
		validacao();
		return true;
	}else{
		$("#valida_senha").attr('class', 'col-md-3 has-error');
		$("#valida_senha_icon").attr('class','form-control-feedback glyphicon glyphicon-remove');
		
		se = 0;
		validacao();
		return false;
	}
}
function validaTelefone(nome){
	
	if(nome != ""){
		$("#valida_telefone").attr('class', 'col-md-4 has-success');
		$("#valida_telefone_icon").attr('class','form-control-feedback glyphicon glyphicon-ok');
		
		t = 20;
		validacao();
		return true;
	}else{
		$("#valida_telefone").attr('class', 'col-md-4 has-error');
		$("#valida_telefone_icon").attr('class','form-control-feedback glyphicon glyphicon-remove');
		
		t = 0;
		validacao();
		return false;
	}
}
function chamaTudo(){
	validarSenha($("#nome").val());
	validaNome($("#sobrenome").val());
	validaSobre($("#usuario").val());
	validaUsu($("#Senha").val());
	validaSenha($("#exampleInputEmail1").val());
	validaTelefone($("#telefone_id").val());
	validacao();
}