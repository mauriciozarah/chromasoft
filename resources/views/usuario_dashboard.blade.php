@extends('template.template')

@section('content')
<div class="login_page">
    <div class="card bd-system">
        <div class="card-header">
            <div style="float:left">Cadastro de Usuários</div>
            <div style="float:right">
                <button type="button" class="btn btn-info" id="callCadastrar" data-toggle="modal" data-target=".bd-example-modal-lg">CADASTRAR NOVO</button>
            </div>
        </div>
        <div class="card-body" style="overflow:scroll">
            <table class="table" id="listar-usuarios" style="color:#fff !important;">
                <thead>
                    <tr>
                        <th style="width:320px;">NOME</th>
                        <th style="width:320px;">E-MAIL</th>
                        <th>SENHA</th>
                        <th>AÇÕES</th>
                    </tr>
                </thead>
                <tbody>
                    @if($resultSet)
                        @foreach($resultSet as $usuario)
                        <tr>
                            <td>{{$usuario->nome}}</td>
                            <td>{{$usuario->email}}</td>
                            <td>*******************</td>
                            <td>
                                <button type="button" class="btn btn-info" onclick="show('{{Crypt::encrypt($usuario->id)}}')" data-toggle="modal" data-target=".bd-example-modal-lg-edit">Editar</button>
                                 | 
                                 <button type="button" class="btn btn-info" onclick="del('{{Crypt::encrypt($usuario->id)}}')">Excluir</button>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4">
                                Nenhum Usuário Cadastrado.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="border:none !important">
    <div class="modal-dialog modal-lg" style="border:none !important">
	    <div class="modal-content" style="background:transparent; border:none !important;">
            <form id="form-cadastrar" method="post">
                @csrf
                <div class="card mt-2 ml-2 mr-2 mb-2 bd-system" style="width:100%">
                    <div class="card-header bg-custom">
                        <div style="float:left">Cadastrar Usuário</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <label class="float-label">NOME:</label>
                                <input type="text" name="nome" id="nome" class="form-control field" maxlength="255" required>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <label class="float-label">E-MAIL:</label>
                                <input type="email" name="email" id="email" class="form-control field" maxlength="255" required>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <label class="float-label">SENHA:</label>
                                <input type="password" name="senha" id="senha" class="form-control field" maxlength="255" required>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="button" id="cadastrar" class="btn btn-info">Cadastrar</button>
                    </div>
                </div>
            </form>
	    </div>
	</div>
</div>



<div class="modal fade bd-example-modal-lg-edit" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" style="border:none !important">
    <div class="modal-dialog modal-lg" style="border:none !important">
	    <div class="modal-content" style="background:transparent; border:none !important;">
            <form id="form-editar" method="post">
                <input type="hidden" name="_method" value="put">
                @csrf
                <div class="card mt-2 ml-2 mr-2 mb-2 bd-system" style="width:100%">
                    <div class="card-header bg-custom">
                        <div style="float:left">Editar Usuário</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <label class="float-label">NOME:</label>
                                <input type="text" name="nome" id="nome_edit" class="form-control field" maxlength="255" required>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <label class="float-label">E-MAIL:</label>
                                <input type="email" name="email" id="email_edit" class="form-control field" maxlength="255" required>
                            </div>
                            <div class="col-md-12 col-sm-12">
                                <label class="float-label">SENHA:</label>
                                <input type="password" name="senha" id="senha_edit" class="form-control field" maxlength="255" required>
                            </div>
                            <input type="hidden" name="email_old" id="email_old" />
                            <input type="hidden" name="id_edit" id="id_edit" value="" />
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <button type="button" id="editar" class="btn btn-info" onclick="update()">Editar</button>
                    </div>
                </div>
            </form>
	    </div>
	</div>
</div>

@endsection

@section('script')
<script>
$(document).ready(function () { 
    var datatable = $("#listar-usuarios").DataTable({
        "responsive": true,
        "processing": false,
        "serverSide": false,
        "language":{
            "decimal":        "",
            "emptyTable":     "Sem dados no Registro",
            "info":           "Mostrando _START_ até _END_ de _TOTAL_ registros",
            "infoEmpty":      "Mostrando 0 até 0 de 0 registros",
            "infoFiltered":   "(filtrado de _MAX_ total registro)",
            "infoPostFix":    "",
            "thousands":      ",",
            "lengthMenu":     "Mostrar _MENU_ registros",
            "loadingRecords": "Carregando...",
            "processing":     "",
            "search":         "BUSCAR:",
            "zeroRecords":    "Não foram Encontrados Registros",
            "paginate": {
                "first":      "Primeiro",
                "last":       "Último",
                "next":       "Próximo",
                "previous":   "Anterior"
            },
            "aria": {
                "orderable":  "Ordenar por esta coluna",
                "orderableReverse": "Ordenar o inverso desta coluna"
            }
        }
    });
            
     
});


var show = async (id) => { 
	await $.ajax({
		url:"{{route('usuario.show')}}/"+id,
		type:"get",
		success: function (data) {
			if (!data.error) {
				$("#nome_edit").val(data.resultSet.nome);
				$("#email_edit").val(data.resultSet.email);
                // $("#senha_edit").val(data.resultSet.senha);
                $("#email_old").val(data.resultSet.email);
                $("#id_edit").val(id);
			}
			if (data.error) {
				new Swal({
					title:'Error',
					text:data.msg,
					icon:'error'
				});
			}
		},
		error: function (error) {
            new Swal({
                title:'Erro',
                text:error.responseJSON.message,
                icon:'error'
            });
		}
	});
}

var update = async () => {
	await $.ajax({
		url:"{{route('usuario.update')}}",
		type:"put",
		data:$("#form-editar").serialize(),
		beforeSend: function () {
			$("#editar").text("Editando...");
            $("#editar").attr('disabled', true);
		},
		success: function (data) {
			$(".bd-modal-lg-edit").modal('hide');
			$("#form-editar input").val("");
            $("#editar").text("Editar...");
            $("#editar").attr('disabled', false);
			if (!data.error) {
				new Swal({
                    title:"Sucesso!!",
                    text:data.msg,
                    icon:'success'
                });
                setTimeout('recarregar()', 300);
			}

			if (data.error) {
				new Swal({
					title:'Erro',
					text:data.msg,
					icon:'error'
				});
			}

			$("#editar").text('Editar');
		},
        error: function (error) {
            $("#editar").attr('disabled', false);
            $("#editar").text("Editar");
            new Swal({
                title:'Erro',
                text:error.responseJSON.message,
                icon:'error'
            });
		}
	});
}

var del = async id => {
    await $.ajax({
		url:"{{route('usuario.delete')}}/"+id,
		type:"delete",
        data:{'_token':"{{csrf_token()}}"},
		success: function (data) {
			if (!data.error) {
				new Swal({
                    title:'Success',
					text:data.msg,
					icon:'success'
                });
                setTimeout('recarregar()', 300);
			}
			if (data.error) {
				new Swal({
					title:'Error',
					text:data.msg,
					icon:'error'
				});
			}
		},
		error: function (error) {
            new Swal({
                title:'Erro',
                text:error.responseJSON.message,
                icon:'error'
            });
		}
	});
}

$("#cadastrar").on("click", async function () {
	await $.ajax({
		url:"{{route('usuario.store')}}",
		type:"POST",
		data:$("#form-cadastrar").serialize(),
		beforeSend: function () {
			$("#cadastrar").text("Cadastrando...");
            $("#cadastrar").attr('disabled', true);
		},
		success: function (data) {
			$("#cadastrar").text("Cadastrar");
            $("#cadastrar").attr('disabled', false);
			$("#form-cadastrar input").val("");
			$(".bd-example-modal-lg").modal('hide');
    		if (!data.error) {
				new Swal({
					title:'Sucesso',
					text:data.msg,
					icon:'success'
				});
				setTimeout('recarregar()', 300);
			}
			if (data.error) {
				new Swal({
					title:'Erro',
					text:data.msg,
					icon:'error'
				});
                
			}
            $("#cadastrar").attr('disabled', false);
		},
		error: function (error) {
			new Swal({
                title:'Erro',
                text:error.responseJSON.message,
                icon:'error'
            });

            $("#cadastrar").text("Cadastrar");
            $("#cadastrar").attr('disabled', false);
		}
	});
});

//window.onload = listar;


var recarregar = () => {
    location.reload();
}
</script>
@endsection