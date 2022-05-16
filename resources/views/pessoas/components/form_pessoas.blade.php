<style type="text/css">
    .alert {
        padding: 2px;
        margin-bottom: -5px;
    }
    .hide {
        display: none;
    }
    .center {
        text-align: center;
    }
    #msg-success {
        max-width: 81%;
        margin-top: -20px;
        margin-bottom: 15px;
        padding: 10px 20px;
    }
</style>

<div class="modal fade" role="dialog" aria-hidden="true" id="cadastroModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header merenda-adm">
                <h4 class="modal-title" id="texto-modal"></h4>
            </div>
            <div class="modal-body">
                <form id="pessoas-form">
                    @csrf
                    @if(isset($pessoa))
                        {{ method_field('PUT') }}
                    @endif
                    <div class="card-body">
                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label>Nome</label>
                                <input type="text" name="nome" class="form-control" autocomplete="off" id="nome" />
                                <div class="alert alert-danger hide" id="nome-error"></div>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Data de Nascimento</label>
                                <input type="text" name="nascimento" class="form-control" autocomplete="off" maxlength="10" onkeypress="formatar('##/##/####', this);" id="nascimento" />
                                <div class="alert alert-danger hide" id="nascimento-error"></div>
                            </div>
                            <div class="form-group col-md-12">
                                <label>Sexo</label>
                                <select name="genero" class="form-control" id="genero">
                                    <option selected="true" disabled="true">Selecione</option>
                                    <option value="1">Masculino</option>
                                    <option value="2">Feminino</option>
                                    <option value="3">Não Informado</option>
                                </select>
                                <div class="alert alert-danger hide" id="genero-error"></div>
                            </div>
                            <div class="form-group col-md-12" id="pais-wrapper">                                
                            </div>      
                        </div>
                    </div>
                </form>
                <button class="btn progress-button btn-primary btn-lg btn-block" id="btn-prosseguir">
                    <i class="fas fa-sign-out-alt fa-padding"></i>&nbsp;Prosseguir
                </button>
                <a href="#" class="btn progress-button btn-warning btn-lg btn-block" id="btn-cancelar">
                    <i class="fas fa-exclamation-circle fa-padding"></i>&nbsp;Cancelar
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>

<script type="text/javascript">
    // Máscara dos inputs
    function formatar(mascara, documento) {
        var i = documento.value.length;
        var saida = mascara.substring(0,1);
        var texto = mascara.substring(i);
        if (texto.substring(0,1) != saida) {
            documento.value += texto.substring(0,1);
        }
    }
</script>





