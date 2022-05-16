@extends('layouts.app')
@section('title', 'Joinner | Pessoas Cadastradas')

<style type="text/css">
    .k-grid {
        overflow: scroll;
    }
    .k-grid tr td {
        border-bottom: 1px solid #f2f2f2;
    }
    .bigger {
        width: 150px;
    }
</style>

@section('content')
<div class="container">
    <div class="row justify-content-center" id="msg-wrapper">
        <div class="col-md-10 alert alert-success alert-dismissible fade show hide" role="alert" id="msg-success">
            <span id="msg-text"></span> 
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

    </div>
    <div class="row justify-content-center">
        <div class="col-md-10">
            <a href="#" class="btn btn-success" id="insert-modal"><i class="fas fa-user-plus"></i> Adicionar Pessoa</a>
            @if(Session::has('success'))
                <div class="col-md-12 alert alert-success alert-dismissible fade show" role="alert">
                    <strong><i class="fas fa-check-circle"></i></strong>&nbsp;{{ Session::get('success') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header">Pessoas Cadastradas</div>
                <div class="card-body">
                    @if($pessoas->count() > 0)
                        <table id="grid">
                            <thead>
                                <tr>
                                    <th scope="col"><b>#</b></th>
                                    <th scope="col"><b>Nome</b></th>
                                    <th scope="col"><b>Data Nasc</b></th>
                                    <th scope="col"><b>Sexo</b></th>
                                    <th scope="col"><b>País</b></th>
                                    <th scope="col" colspan="2"><b>Ações</b></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    @else
                        <tr>Nenhum registro encontrado.</tr>
                    @endif
                </div>
            </div>
        </div>
        @include('pessoas.components.form_pessoas')
        @include('pessoas.components.modal_confirm')
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc=" crossorigin="anonymous"></script>
<script src="https://kendo.cdn.telerik.com/2022.2.510/js/angular.min.js"></script>
<script src="https://kendo.cdn.telerik.com/2022.2.510/js/jszip.min.js"></script>
<script src="https://kendo.cdn.telerik.com/2022.2.510/js/kendo.all.min.js"></script>
<script type="text/javascript" src="{{ asset('js/crud.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#grid").kendoGrid({
            sortable: false,
            filterable: false
        });
    });
</script>
@endsection

                         