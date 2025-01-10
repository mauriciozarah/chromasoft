@extends('template.template')

@section('content')
<div class="login_page">
    <form name="formulario" id="form" class="form-horizontal">
    @csrf
    <div class="card bd">
        <div class="card-header">
            <h3>Login Sistema</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    E-mail:<br>
                    <input type="email" name="email" id="email" class="form-control field" maxlength="200" required />
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    Senha:<br>
                    <input type="password" name="senha" id="senha" class="form-control field" maxlength="100" required />
                </div>
            </div>
        </div>
        <div class="card-footer text-center">
            <button type="submit" class="btn btn-info" id="sign_in">Entrar</button>
        </div>
    </div>
    </form>
</div>
@endsection

@section('script')
<script>
    $("#sign_in").on('click', async function (e) {
        e.preventDefault();

        await $.ajax({
            url:"{{route('usuario.do_login')}}",
            type:"POST",
            data:$("#form").serialize(),
            beforeSend: function () {
                $("#sign_in").text('Logando ...');
            },
            success: function (data) {
                if (!data.error) {
                    window.location = "{{route('usuario.dashboard')}}";
                }

                if (data.error) {
                    new Swal({
                        title:'Erro de Login',
                        text:'Erro. Usuário não encontrado.',
                        icon:'error'
                    })
                }

                $("#sign_in").text('Entrar');
            },
            error: function (data) {
                new Swal({
                    title:'Erro de Login',
                    text:data.responseJSON.message,
                    icon:'error'
                })

                $("#sign_in").text('Entrar');
            }
        });
    })
</script>
@endsection