$(document).ready(function() {

    getRecords();

    function getRecords() {
        // rota para exibir as pessoas cadastradas 
        jQuery.ajax({
            method: 'GET',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '/ajax/pessoas',
            dataType: 'json',
            success: function(data) {
                $("#grid").find('tbody').html("");
                $.each(data, function(i,row) {
                    var sexo = "";
                    nascimento_formated = moment(row['nascimento'], "YYYY-MM-DD");
                    nascimento_formated = nascimento_formated.format("DD/MM/YYYY");

                    if (row['genero'] == 1) {
                        sexo = "Masculino";
                    }
                    else if(row['genero'] == 1) {
                        sexo = "Feminino";
                    }
                    else {
                        sexo = "Não informado";
                    }

                    $("#grid").find('tbody').append( 
                        `<tr>
                            <td>${row['id']}</td>
                            <td>${row['nome']}</td>
                            <td>${nascimento_formated}</td>
                            <td>${sexo}</td>
                            <td>${row['pais'].nome}</td>
                            <td class="text-center">
                                <button class= "btn btn-warning" title="Editar Cadastro" data-id="${row['id']}" id="edit-modal"><i class="fas fa-pencil-alt white"></i></button>
                            </td>
                            <td class="text-center">
                                <button class= "btn btn-danger" title="Excluir Cadastro" data-id="${row['id']}" id="delete-modal"><i class="fas fa-trash white"></i></button>
                            </td>
                        </tr>`
                    );
                });
            }// end success
        }); 
    }

    // Inserir cadastro
    $(document).on('click', '#insert-modal', function() {
        jQuery.noConflict();
        $('#cadastroModal').modal('show');
        $('#texto-modal').text("Cadastrar Pessoa");
        $("#pais-wrapper").html("");
        $('#nome-error').hide();
        $('#nascimento-error').hide();
        $('#genero-error').hide();
        var pais = '';
        // Get Data
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '/pessoas/cadastrar',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#nome').val(''); 
                $('#nascimento').val(''); 
                $('#genero').val($("#genero option:first").val());
                $('#pais').html(""); 
                if (data && data.length > 0) {
                    $("#pais-wrapper").append('<label>País</label><select name="pais_id" class="form-control" id="pais"><option value="" disabled="true" selected="true">Selecione</option></select><div class="alert alert-danger hide" id="pais_id-error"></div>'); 
                    for (var i = 0; i < data.length; i++) {
                        $("#pais").append('<option value="' + data[i].id + '">' + data[i].nome + '</option>');
                    }
                }
                else {
                    $("#pais-wrapper").append('<label>País</label><select name="pais_id" class="form-control" id="pais"><option value="" disabled="true" selected="true">Nenhum registro encontrado</option></select>');
                }

                $("#pais").change(function(){
                    pais = $("#pais").val();
                });

                // Modal Confirm  
                $('#cadastroModal').modal({
                }).on('click', '#btn-prosseguir', function(e) {
                    e.preventDefault();
                    var nome = $("#nome").val();
                    var genero = $("#genero").val();
                    var nascimento = $("#nascimento").val();
                    var nascimento_formated = "";
                    
                    // Validando a data de nascimento
                    if (nascimento.length) {
                        if (nascimento.length >= 10) {
                            nascimento_formated = moment(nascimento, "DD/MM/YYYY");
                            nascimento_formated = nascimento_formated.format("YYYY-MM-DD HH:mm:ss");
                        }
                        else {
                            $("#nascimento-error").show().html('A data de nascimento deve ser inferior à data atual.'); 
                        }
                    }
                    else {
                        $("#nascimento-error").show().html('Informe uma Data válida.');
                    }

                    // Submit Form
                    $.ajax({
                        url: '/pessoas/cadastrar',
                        type: 'POST',
                        data:{
                            "_token": $('#token').val(),
                            pais_id:pais,
                            nome:nome,
                            nascimento:nascimento_formated,
                            genero:genero,
                          },
                        success: function(data) { 
                            if (data.success) {
                                $('#msg-success').show().find('#msg-text').text(data.success); 
                                $('#cadastroModal').modal('hide');
                                setTimeout(function() {
                                    getRecords();
                                },500);
                            }
                        },
                        error: function(response) { 
                            if (response.responseJSON.errors) {
                                $.each( response.responseJSON.errors, function(key, value) {
                                    $('#'+key+'-error').show().html(value);
                                });
                            }
                        }
                    });
                }).on('click', '#btn-cancelar', function(e) {
                    $('#cadastroModal').modal('toggle');
                });
            }// end success
        });
    });

    // Editar cadastro
    $(document).on('click', '#edit-modal', function() {
        var id = $(this).data('id');
        jQuery.noConflict();
        $("#pais-wrapper").html("");
        $('#nome-error').hide();
        $('#nascimento-error').hide();
        $('#genero-error').hide();

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: '/pessoas/editar/'+id,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var nome = "";
                var genero = "";
                var nascimento = "";
            	var nascimento_formated = "";
            	var pais = "";
            	// Formatando a data de nascimento
            	nascimento_formated = moment(data.pessoa.nascimento, "YYYY-MM-DD");
    			nascimento_formated = nascimento_formated.format("DD/MM/YYYY");
    			// Pegando os valores do cadastro
                $('#pais').html("");
       			$('#cadastroModal').modal('show');
                $('#texto-modal').text("Atualizar Cadastro");
    			$("#nome").val(data.pessoa.nome);
    			$("#nascimento").val(nascimento_formated);

    			if (data.pessoa.genero != null) {
    				$("#genero").val(data.pessoa.genero).attr('selected','selected');
    			}
    			else {
    				$("#genero").val('3').attr('selected','selected');
    			}

       			if (data.paises.length > 0) {
       				$("#pais-wrapper").append('<label>País</label><select name="pais_id" class="form-control" id="pais"></select><div class="alert alert-danger hide" id="pais_id-error"></div>'); 
       				for (var i = 0; i < data.paises.length; i++) {
                    	$("#pais").append('<option value="' + data.paises[i].id + '"' + (data.pessoa.pais.id  === data.paises[i].id ? 'selected="selected"' : '') +'>' + data.paises[i].nome  + '</option>');
                	}
       			}
       			else {
                	$("#pais-wrapper").append('<label>País</label><select name="pais_id" class="form-control" id="pais"><option value="" disabled="true" selected="true">Nenhum registro encontrado</option></select>');
       			}

       			$("#pais").change(function() {
                    pais = $("#pais").val();
                });

                // Modal Confirm  
                $('#cadastroModal').modal({
                }).on('click', '#btn-prosseguir', function(e) {
                    e.preventDefault();
                    // Atriuindo novos valores aos inputs
                    nome = $("#nome").val();
                    genero = $("#genero").val();
                    nascimento = $("#nascimento").val();
                    nascimento_formated = "";
                    
                    // Validando a data de nascimento
                    if (nascimento.length) {
                    	if (nascimento.length >= 10) {
                        	nascimento_formated = moment(nascimento, "DD/MM/YYYY");
                        	nascimento_formated = nascimento_formated.format("YYYY-MM-DD HH:mm:ss");
    	                }
    	                else {
    	                    $("#nascimento-error").show().html('A data de nascimento deve ser inferior à data atual.'); 
    	                }
                    }
                    else {
                    	$("#nascimento-error").show().html('Informe uma data válida.');
                    }

                    // Submit Form
                    $.ajax({
                        url: '/pessoas/editar/'+id,
                        type: 'PUT',
                        data:{
                            "_token": $('#token').val(),
                            pais_id:pais,
                            nome:nome,
                            nascimento:nascimento_formated,
                            genero:genero,
                          },
                        success: function(data) { 
                            if (data.success) {
                                $('#msg-success').show().find('#msg-text').text(data.success); 
                                $('#cadastroModal').modal('hide');
                                setTimeout(function() {
                                    getRecords();
                                },500);
                            }
                        },
                        error: function(response) { 
                            if (response.responseJSON.errors) {
                                $.each( response.responseJSON.errors, function(key, value) {
                                    $('#'+key+'-error').show().html(value);
                                });
                            }
                        }
                    });
                }).on('click', '#btn-cancelar', function(e) {
                    $('#cadastroModal').modal('toggle');
                });

            }// end success
        });
    });

    // Deletar cadastro
    $(document).on('click', '#delete-modal', function() {
        var id = $(this).data('id');
        jQuery.noConflict();
     	$('#deleteModal').modal('show');

        // Modal confirm
        $('#deleteModal').modal({
        }).on('click', '#btn-prosseguir', function(e) {
            e.preventDefault();
            $.ajax({
            	headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: '/pessoas/deletar/'+id,
                type: 'DELETE',
                dataType: 'json',
                data: {method: '_DELETE', submit: true}, 
                success: function(data) { 
                    if (data.success) {
                        $('#msg-success').show().find('#msg-text').text(data.success); 
                        $('#deleteModal').modal('hide');
                        setTimeout(function() {
                            getRecords();
                        },500);
                    }
                }
            });
        }).on('click', '#btn-cancelar', function(e) {
            $('#deleteModal').modal('toggle'); 
        });
    });

    // Resetar Modal
    $('#cadastroModal').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
    });
});